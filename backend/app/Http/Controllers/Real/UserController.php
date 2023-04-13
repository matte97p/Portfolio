<?php

namespace App\Http\Controllers\Real;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AbstractCrudController;

class UserController extends AbstractCrudController
{
    protected static $errors = [
        'name.required' => 'Inserire il nome',
        'name.string' => 'Il nome deve essere un testo',
        'name.max' => 'Nome troppo lungo',
        'email.required' => 'Inserire l\'email',
        'email.email' => 'Email errata',
        'email.unique' => 'Email già registrata',
        'password.required' => 'Inserire la password',
        'password.confirmed' => 'Password di conferma non corrisponde',
    ];

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->request();
    }

    public function create(Request $request): JsonResponse
    {
        try{
            $validator = Validator::make($request->all(),
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email', 'unique:users'],
                    'password' => ['required', 'confirmed'],
                ],
                self::$errors,
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            $data = $request->all();
            $data["password"] = bcrypt($request->password);

            $user = User::create($data);

            return response()->json(["message" => "Registrazione riuscita!", "data" => $user], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Registrazione fallita!", "error" => $e->getMessage()], 500);
        }
    }

    /* @todo */
    public function read(Request $request): JsonResponse
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
}
