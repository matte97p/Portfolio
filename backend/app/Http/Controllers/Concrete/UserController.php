<?php

namespace App\Http\Controllers\Concrete;

use Exception;
use Illuminate\Http\Request;
use App\Exceptions\CustomHandler;
use Illuminate\Http\JsonResponse;
use App\Models\UsersCurrent as User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\AbstractCrudController;

class UserController extends AbstractCrudController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->request();
        /* EXAMPLE */
        // $this->middleware(['role_or_permission:Docente|Scrivere Ruoli'];
    }

    public function create(Request $request): JsonResponse
    {
        try{
            $password_role = Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised();
            $validator = Validator::make($request->all(),
                [
                    'name' => ['required', 'string', 'max:100'],
                    'surname' => ['required', 'string', 'max:100'],
                    'taxid' => ['required', 'string', 'unique:App\Models\UsersCurrent', 'min:16', 'max:16'],
                    // 'email' => ['required', 'email', 'unique:App\Models\UsersCurrent', 'min:7'],
                    // 'phone' => ['required', 'integer', 'unique:App\Models\UsersCurrent', 'digits_between:6,15'],
                    'gender' => ['required', 'string', 'in:m,f'],
                    'birth_date' => ['required', 'date', 'before:today', 'min:10', 'max:10'],
                    // 'password' => ['required', 'confirmed', $password_role],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            $data = $request->all();
            // $data["password"] = bcrypt($request->password);

            $user = User::create($data);

            return response()->json(["message" => "Creazione riuscita!", "data" => $user], 200);
        } catch (\Exception $e) {
            return CustomHandler::renderCustom($e, "Creazione fallita!");
        }
    }

    /* @todo */
    public function read(Request $request): JsonResponse
    {
        try{
            throw new Exception('Not implemented');
        } catch (\Exception $e) {
            return CustomHandler::renderCustom($e, "Ricerca fallita!");
        }
    }

    public function update(Request $request): JsonResponse
    {
        try{
            $validator = Validator::make($request->all(),
                [
                    'id' => ['required', 'uuid', 'exists:App\Models\UsersCurrent,id,deleted_at,NULL'],
                    'name' => ['required', 'string', 'max:100'],
                    'surname' => ['required', 'string', 'max:100'],
                    'taxid' => ['required', 'string', 'unique:App\Models\UsersCurrent', 'min:16', 'max:16'],
                    // 'email' => ['required', 'email', 'unique:App\Models\UsersCurrent', 'min:7'],
                    // 'phone' => ['required', 'integer', 'unique:App\Models\UsersCurrent', 'digits_between:6,15'],
                    'gender' => ['required', 'string', 'in:m,f'],
                    'birth_date' => ['required', 'date', 'before:today', 'min:10', 'max:10'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            $response = User::findOrFail($request->id);

            $response->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'taxid' => $request->taxid,
                // 'email' => $request->email,
                // 'phone' => $request->phone,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
            ]);

            return response()->json(["message" => "Aggiornamento riuscito!", "data" => $response], 200);
        } catch (\Exception $e) {
            return CustomHandler::renderCustom($e, "Aggiornamento fallito!");
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try{
            $validator = Validator::make($request->all(),
                [
                    'id' => ['required', 'uuid', 'exists:App\Models\UsersCurrent,id,deleted_at,NULL'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            User::findOrFail($request->id)->delete();

            return response()->json(["message" => "Cancellazione riuscita!"], 204);
        } catch (\Exception $e) {
           return CustomHandler::renderCustom($e, "Cancellazione fallita!");
        }
    }

    public static function list(): array
    {
        return User::all()->pluck('taxid', 'id')->toArray();
    }
}
