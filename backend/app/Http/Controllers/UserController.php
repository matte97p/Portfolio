<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AbstractCrudController;

class UserController extends AbstractCrudController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function create(Request $request): JsonResponse
    {
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

    /* @todo */
    public function index(Request $request): JsonResponse
    {
        try{
            throw new Exception('Not implemented');
        } catch (\Exception $e) {
            return response()->json(["message" => "Ricerca fallita!", "error" => $e->getMessage()], 500);
        }
    }

    /* @todo */
    public function update(Request $request): JsonResponse
    {
        try{
            throw new Exception('Not implemented');
        } catch (\Exception $e) {
            return response()->json(["message" => "Aggiornamento fallito!", "error" => $e->getMessage()], 500);
        }
    }

    /* @todo */
    public function delete(Request $request): JsonResponse
    {
        try{
            throw new Exception('Not implemented');
        } catch (\Exception $e) {
            return response()->json(["message" => "Cancellazione fallita!", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Check it's Authed
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
