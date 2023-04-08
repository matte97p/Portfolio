<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller  {

    /**
     * Register @todo spostare su UserController
     *
     * @var Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function register(Request $request)
    {
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
            return response()->json(["message" => "Registrazione fallita!", "error" => $e->getResponse()->getBody()->getContents()], 500);
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

            if(!Auth::attempt($params)) return response()->json(["message" => "Login fallito!"], 403);

            return response()->json(["message" => "Login riuscito!"], 201);

        } catch (\Exception $e) {
            return response()->json(["message" => "Login fallito!", "error" => $e->getResponse()->getBody()->getContents()], 500);
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

            if(!Auth::attempt($params)) return response()->json(["message" => "Login fallito!"], 403);

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

            return Route::dispatch($proxy);

        } catch (\Exception $e) {
            return response()->json(["message" => "Login fallito!", "error" => $e->getResponse()->getBody()->getContents()], 500);
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
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(["message" => "Logout effettuato con successo!"], 200);
        }

        return response()->json(["message" => "Utente non loggato!"], 200);
    }

    /**
     * Check Token @todo
     *
     * @var Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function checkToken(Request $request){
        try{
            throw new Exception("Not Implemented!", 501);

            return response()->json(["message" => ""], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "TODO fallito!", "error" => $e->getResponse()->getBody()->getContents()], 500);
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
                'refresh_token' => $request->refresh_token,
                'client_id' => $oauth_clients->id,
                'client_secret' => $oauth_clients->secret,
                'scope' => '*',
            ]);

            $proxy = Request::create('oauth/token', 'post');

            return Route::dispatch($proxy);

        } catch (\Exception $e) {
            return response()->json(["message" => "Recupero refresh token fallito!", "error" => $e->getResponse()->getBody()->getContents()], 500);
        }
    }
}
