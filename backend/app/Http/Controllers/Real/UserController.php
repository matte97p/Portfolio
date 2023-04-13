<?php

namespace App\Http\Controllers\Real;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AbstractCrudController;

class UserController extends AbstractCrudController
{
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
                $this::$errors,
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
            $validator = Validator::make($request->all(),
                [
                    'id' => ['required', 'integer', 'exists:users,id'],
                    'name' => ['required', 'string', 'max:50'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            $response = User::findOrFail($request->id);

            $response->update(['name' => $request->name]);

            return response()->json(["message" => "Aggiornamento riuscito!", "data" => $response], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Aggiornamento fallito!", "error" => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try{
            $validator = Validator::make($request->all(),
                [
                    'id' => ['required', 'int', 'exists:users,id'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            User::withTrashed()->findOrFail($request->id)->delete();

            return response()->json(["message" => "Cancellazione riuscita!"], 204);
        } catch (\Exception $e) {
            return response()->json(["message" => "Cancellazione fallita!", "error" => $e->getMessage()], 500);
        }
    }
}
