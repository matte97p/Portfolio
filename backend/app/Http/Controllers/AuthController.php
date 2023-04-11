<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Carbon;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\AbstractController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CacheController;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\RequestOptions;

class AuthController extends AbstractController  {

    private static $oauth_client = [
        // 0 => 'ProdOauthClient', //prod @todo decommentare
        0 => 'LocalPswClient',  //prod
        1 => 'LocalPswClient'   //test
    ];

    protected static $base_uri = [
        // 0 => 'https://backend-portfolio.com',   //prod @todo decommentare
        0 => 'https://backend-portfolio.test/',  //prod
        1 => 'https://backend-portfolio.test/'  //test
    ];

    /**
     * @var OauthClient
     */
    private $oauth_clients;

    /**
     * Set the $oauth_clients
     *
     * @param bool $test_environment
     *
     * @return void
     */
    private function getOauthClients()
    {
        $this->oauth_clients = DB::table('oauth_clients')
        ->where('name', self::$oauth_client[ (int) $this->test_environment ] )
        ->first();
    }

    /**
     * Get Controller BaseUri
     *
     * @return string
     */
    protected function getBaseUri(): string
    {
        return self::$base_uri[ (int) $this->test_environment ];
    }

    /**
     * Check it's Authed
     *
     * @return Response|bool
     *
     * @throws Exception
     */
    private function checkLogged(){
        if (!Auth::check()) {
            return response()->json(["message" => "Utente non loggato!"], 401);
        }

        return true;
    }

    /**
     * Login
     *
     * @var Request $request
     *
     * @return Response|bool
     *
     * @throws Exception
     */
    private function basicAuth(Request $request){
        try{
            $params = $request->all();

            $validator = Validator::make($params,
                [
                    'email' => ['required', 'email'],
                    'password' => 'string|required',
                ],
                [
                    'email.required' => 'Inserire l\'email',
                    'email.email' => 'Email non corretta',
                    'password.required' => 'Inserire la password',
                ]
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            if(!Auth::guard('web')->attempt($params)) return response()->json(["message" => "Credenziali errate!"], 403);

            return true;

        } catch (\Exception $e) {
            return response()->json(["message" => "Login fallito!", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Refresh Token
     *
     * @var Request $request
     * @var obj $token
     *
     * @return Response
     *
     * @throws Exception
     */
    private function refreshToken(Request $request, $token){
        try {
            self::getOauthClients(true); /* @todo da fare nel costructor */

            $parameters =
            [
                RequestOptions::JSON =>
                [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => json_decode(CacheController::getCache("Bearer"))->refresh_token,
                    'client_id' => $this->oauth_clients->id,
                    'client_secret' => $this->oauth_clients->secret,
                    'scope' => '*',
                ]
            ];

            $response = $this->request('post', 'oauth/token', $parameters);

            $response = json_decode((string) $response->getBody());

            CacheController::setCache($response->token_type, json_encode($response));

            return response()->json(["message" => "Token aggiornato con successo!"], 201);

        } catch (\Exception $e) {
            return response()->json(["message" => "Recupero refresh token fallito!", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Login OAuth2
     *
     * oauth/token Passport
     *
     * @var Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function oauth2(Request $request){
        try{
            $params = $request->all();

            if ( ($check = self::basicAuth($request) ) !== true) { return $check; }

            self::getOauthClients(true); /* @todo da fare nel costructor */

            $parameters =
            [
                RequestOptions::JSON =>
                [
                    'grant_type' => 'password',
                    'client_id' => $this->oauth_clients->id,
                    'client_secret' => $this->oauth_clients->secret,
                    'username' => $params["email"],
                    'password' => $params["password"],
                    'scope' => '*',
                ]
            ];

            $response = $this->request('post', 'oauth/token', $parameters);

            $response = json_decode((string) $response->getBody());

            CacheController::setCache($response->token_type, json_encode($response));

            return response()->json(["message" => "Login effettuato con successo!", "access_token" => $response->access_token/*, "refresh_token" => $response->refresh_token*/], 201);

        } catch (\Exception $e) {
            return response()->json(["message" => "Login fallito!", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Logout
     *
     * @return Response
     *
     * @throws Exception
     */
    public function logout(){
        if ( ($check = self::checkLogged() ) !== true) { return $check; }

        Auth::user()->token()->revoke();
        CacheController::delCache("Bearer");

        return response()->json(["message" => "Logout effettuato con successo!"], 200);
    }

    /**
     * Check Token
     *
     * @var Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function checkToken(Request $request){
        try{
            if ( ($check = self::checkLogged() ) !== true) { return $check; }

            $token = Auth::user()->token();

            // if (Carbon::parse($token->expires_at, 'UTC')->timestamp < Carbon::now()->timestamp || $token->revoked) {
                return self::refreshToken($request, $token);
            // }

            return response()->json(["message" => "Token valido!"], 201);

        } catch (\Exception $e) {
            return response()->json(["message" => "Recupero informazioni token fallito!", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Register @todo spostare su UserController
     *
     * @var Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function register(Request $request){
        try{
            $data = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed'
            ]);

            $data['password'] = bcrypt($request->password);

            $user = User::create($data);

            return response()->json(["message" => "Registrazione riuscita!"], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "Registrazione fallita!", "error" => $e->getMessage()], 500);
        }
    }
}
