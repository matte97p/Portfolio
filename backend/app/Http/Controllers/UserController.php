<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends AbstractCrudController
{
    /**
     * Check it's Authed
     *
     * @return Response|bool
     *
     * @throws Exception
     */
    public static function checkLogged()
    {
        if (!Auth::check()) {
            return response()->json(["message" => "Utente non loggato!"], 401);
        }

        return true;
    }

    /**
     * @var Request $request
     * @return Response
     * @throws Exception
     */
    public function create(Request $request): Response
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

    /**
     * @var Request $request
     * @return Response
     * @throws Exception
     */
    public function index(Request $request): Response
    {
        try{
            throw new Exception('Not implemented');
        } catch (\Exception $e) {
            return response()->json(["message" => "Ricerca fallito!", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * @var Request $request
     * @return Response
     * @throws Exception
     */
    public function update(Request $request): Response
    {
        try{
            throw new Exception('Not implemented');
        } catch (\Exception $e) {
            return response()->json(["message" => "Aggiornamento fallito!", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * @var Request $request
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request): Response
    {
        try{
            throw new Exception('Not implemented');
        } catch (\Exception $e) {
            return response()->json(["message" => "Cancellazione fallita!", "error" => $e->getMessage()], 500);
        }
    }
}
