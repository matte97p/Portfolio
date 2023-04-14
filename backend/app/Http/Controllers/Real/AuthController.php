<?php

namespace App\Http\Controllers\Real;

use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use App\Exceptions\CustomHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Real\CacheController;
use Illuminate\Validation\ValidationException;
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

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->oauth_clients = DB::table('oauth_clients')
        ->where('name', self::$oauth_client[ (int) $this->test_environment ] )
        ->first();
    }

    protected function getBaseUri(): string
    {
        return self::$base_uri[ (int) $this->test_environment ];
    }

    /**
     * @var Request $request
     *
     * @return JsonResponse|bool
     *
     * @throws Exception
     */
    private function basicAuth(Request $request): JsonResponse|bool
    {
        try{
            $validator = Validator::make($request->all(),
                [
                    'email' => ['required', 'email'],
                    'password' => ['required', 'string'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            if(!Auth::guard('web')->attempt($request->all())) return response()->json(["message" => "Credenziali errate!"], 403);

            return true;

        } catch (\Exception $e) {
            return CustomHandler::renderCustom($e, "Login fallito!");
        }
    }

    /**
     * @return object
     *
     * @throws Exception
     */
    private function getToken(): object
    {
        return json_decode(CacheController::getCache("Bearer." . Auth::id()));
    }

    /**
     * @return JsonResponse
     *
     * @throws Exception
     */
    private function refreshToken(): JsonResponse
    {
        try {
            $parameters =
            [
                RequestOptions::JSON =>
                [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->getToken()->refresh_token,
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
            return CustomHandler::renderCustom($e, "Recupero refresh token fallito!");
        }
    }

    /**
     * Login OAuth2 -> oauth/token Passport
     *
     * @var Request $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function oauth2(Request $request): JsonResponse
    {
        try{
            if ( ($check = self::basicAuth($request) ) !== true) { return $check; }

            $parameters =
            [
                RequestOptions::JSON =>
                [
                    'grant_type' => 'password',
                    'client_id' => $this->oauth_clients->id,
                    'client_secret' => $this->oauth_clients->secret,
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '*',
                ]
            ];

            $response = $this->request('post', 'oauth/token', $parameters);

            $response = json_decode((string) $response->getBody());

            CacheController::setCache($response->token_type . "." . Auth::guard('web')->id(), json_encode($response));

            return response()->json(["message" => "Login effettuato con successo!", "access_token" => $response->access_token], 201);

        } catch (\Exception $e) {
            return CustomHandler::renderCustom($e, "Login fallito!");
        }
    }

    /**
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function logout(): JsonResponse
    {
        if ( ($check = self::checkLogged() ) !== true) { return $check; }

        Auth::user()->token()->revoke();
        CacheController::delCache("Bearer." . Auth::id());

        return response()->json(["message" => "Logout effettuato con successo!"], 201);
    }

    /**
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function checkToken(): JsonResponse
    {
        try{
            if ( ($check = self::checkLogged() ) !== true) { return $check; }

            if (Carbon::parse(Auth::user()->token()->expires_at, 'UTC')->subMinutes(5)->timestamp < Carbon::now()->timestamp) {
                return self::refreshToken();
            }

            return response()->json(["message" => "Token valido!", "access_token" => $this->getToken()->refresh_token], 201);

        } catch (\Exception $e) {
            return CustomHandler::renderCustom($e, "Recupero informazioni token fallito!");
        }
    }

    /**
     * Check it's Authed OAuth2 -> oauth/token Passport
     *
     * @return JsonResponse|bool
     *
     * @throws Exception
     */
    public static function checkLogged(): JsonResponse|bool
    {
        if (!Auth::check()) {
            return response()->json(["message" => "Utente non loggato!"], 401);
        }

        return true;
    }
}
