<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

    /**
     * Check it's Authed
     *
     * @return Response|bool
     *
     * @throws Exception
     */
    public static function checkLogged(){
        if (!Auth::check()) {
            return response()->json(["message" => "Utente non loggato!"], 401);
        }

        return true;
    }

    /**
     * Register
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
