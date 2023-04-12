<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\CacheController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AbstractApiController;

class AuthController extends AbstractApiController
{
    /**
     * @var OauthClient
     */
    private $oauth_clients;

    private static $oauth_client = [
        0 => 'ProdOauthClient', //prod
        1 => 'LocalPswClient'   //test
    ];

    protected static $base_uri = [
        0 => 'https://backend-portfolio.com',   //prod
        1 => 'https://backend-portfolio.test/'  //test
    ];

    /**
     * Custom __construct + parent
     *
     * @var Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->oauth_clients = DB::table('oauth_clients')
        ->where('name', self::$oauth_client[ (int) $this->test_environment ] )
        ->first();
    }

    /**
     * Get Controller $base_uri
     *
     * @return string
     */
    protected function getBaseUri(): string
    {
        return self::$base_uri[ (int) $this->test_environment ];
    }

    /**
     * Login password
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
     * @return Response
     *
     * @throws Exception
     */
    private function refreshToken(){
        try {
            $parameters =
            [
                RequestOptions::JSON =>
                [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => json_decode(CacheController::getCache("Bearer." . Auth::id()))->refresh_token,
                    'client_id' => $this->oauth_clients->id,
                    'client_secret' => $this->oauth_clients->secret,
                    'scope' => '*',
                ]
            ];

            $response = $this->request('post', 'oauth/token', $parameters);

            $response = json_decode((string) $response->getBody());
            CacheController::setCache("Bearer." . Auth::id(), json_encode($response));

            return response()->json(["message" => "Token aggiornato con successo!", "access_token" => $response->access_token], 201);

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

            CacheController::setCache($response->token_type . "." . Auth::guard('web')->id(), json_encode($response));

            return response()->json(["message" => "Login effettuato con successo!", "access_token" => $response->access_token], 201);

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
        if ( ($check = UserController::checkLogged() ) !== true) { return $check; }

        Auth::user()->token()->revoke();
        CacheController::delCache("Bearer." . Auth::id());

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
            if ( ($check = UserController::checkLogged() ) !== true) { return $check; }

            $token = Auth::user()->token();

            if (Carbon::parse(Auth::user()->token()->expires_at, 'UTC')->subMinutes(5)->timestamp < Carbon::now()->timestamp) {
                return self::refreshToken();
            }

            return response()->json(["message" => "Token valido!", "access_token" => json_decode(CacheController::getCache("Bearer." . Auth::id()))->access_token], 201);

        } catch (\Exception $e) {
            return response()->json(["message" => "Recupero informazioni token fallito!", "error" => $e->getMessage()], 500);
        }
    }
}
