<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Carbon;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CacheController;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller  {

    /**
     * Check it's Authed
     *
     * @return mixed
     *
     * @throws Exception
     */
    protected function checkLogin(){
        if (!Auth::check()) {
            return response()->json(["message" => "Utente non loggato!", "valid" => false], 401);
        }

        return true;
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

            $token = '';// $user->createToken('Access Token')->accessToken;

            return response()->json(["message" => "Registrazione riuscita!", "token" => $token], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "Registrazione fallita!", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Login @deprecated
     *
     * @var Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function basicAuth(Request $request){
        try{
            $params = $request->all();

            $validator = Validator::make($params,
                [
                    'email' => 'string|required',
                    'password' => 'string|required',
                ],
                [
                    'email.required' => 'Inserire l\'email',
                    'password.required' => 'Inserire la password',
                ]
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            if(!Auth::attempt($params)) return response()->json(["message" => "Credenziali errate!"], 403);

            return response()->json(["message" => "Login riuscito!"], 201);

        } catch (\Exception $e) {
            return response()->json(["message" => "Login fallito!", "error" => $e->getMessage()], 500);
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

            $oauth_clients = DB::table('oauth_clients')->where('name', 'LocalPswClient')->first();

            $request->request->add([
                'grant_type' => 'password',
                'client_id' => $oauth_clients->id,
                'client_secret' => $oauth_clients->secret,
                'username' => $params["email"],
                'password' => $params["password"],
                'scope' => '*',
            ]);

            $proxy = Request::create('oauth/token', 'post');

            $response = json_decode(Route::dispatch($proxy)->content());
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
        if ( ($check = self::checkLogin() ) !== true) { return $check; }

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
            if ( ($check = self::checkLogin() ) !== true) { return $check; }

            $token = Auth::user()->token();

            if (Carbon::parse($token->expires_at, 'UTC')->timestamp < Carbon::now()->timestamp || $token->revoked) {
                return self::refreshToken($request);
            }

            return response()->json(["message" => "Token valido!", "valid" => true], 201);

        } catch (\Exception $e) {
            return response()->json(["message" => "Recupero informazioni token fallito!", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Refresh Token
     *
     * @var Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    private function refreshToken(Request $request){
        try {
            $oauth_clients = DB::table('oauth_clients')->where('name', 'LocalPswClient')->first();

            $request->request->add([
                'grant_type' => 'refresh_token',
                'refresh_token' => json_decode(CacheController::getCache("Bearer"))->refresh_token,
                'client_id' => $oauth_clients->id,
                'client_secret' => $oauth_clients->secret,
                'scope' => '*',
            ]);

            $proxy = Request::create('oauth/token', 'post');

            $response = json_decode(Route::dispatch($proxy)->content());
            CacheController::setCache($response->token_type, json_encode($response));

            return response()->json(["message" => "Token aggiornato con successo!", "valid" => true], 201);

        } catch (\Exception $e) {
            return response()->json(["message" => "Recupero refresh token fallito!", "valid" => false, "error" => $e->getMessage()], 500);
        }
    }
}
