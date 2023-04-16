<?php

namespace App\Http\Controllers\Real;

use Exception;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Exceptions\CustomHandler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\AbstractCrudController;

class PermissionController extends AbstractCrudController
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
                    'name' => ['required', 'string', 'unique:App\Models\Permission', 'max:50'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            $response = Permission::create($request->all());

            return response()->json(["message" => "Creazione riuscita!", "data" => $response], 200);
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
                    'id' => ['required', 'integer', 'exists:App\Models\Permission'],
                    'name' => ['required', 'string', 'unique:App\Models\Permission', 'max:50'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            $response = Permission::findOrFail($request->id);

            $response->update(['name' => $request->name]);

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
                    'id' => ['required', 'integer', 'exists:App\Models\Permission'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            Permission::findOrFail($request->id)->delete();

            return response()->json(["message" => "Cancellazione riuscita!"], 204);
        } catch (\Exception $e) {
           return CustomHandler::renderCustom($e, "Cancellazione fallita!");
        }
    }

    public function list(): object
    {
        return Permission::all()->pluck('name', 'id');
    }

    public function give(Request $request): JsonResponse
    {
        try{
            $validator = Validator::make($request->all(),
                [
                    'users' => ['required', 'array', 'min:1'],
                    'users.*.*' => ['uuid', 'exists:App\Models\User,id'],
                    'permissions' => ['required', 'array', 'min:1'],
                    'permissions.*' => ['string', 'exists:App\Models\Permission,name'], // @todo name or uuid ??
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            foreach($request->users as $row)
            {
                $user = User::findByPrimary($row);
                $user->givePermissionTo($request->permissions);
            }

            return response()->json(["message" => "Aggiornamento permessi riuscito!"], 201);
        } catch (\Exception $e) {
            return CustomHandler::renderCustom($e, "Aggiornamento permessi fallito!");
        }
    }

    public function revoke(Request $request): JsonResponse
    {
        try{
            $validator = Validator::make($request->all(),
                [
                    'user' => ['required', 'uuid', 'exists:App\Models\User,id'],
                    'permissions' => ['required', 'array', 'min:1'],
                    'permissions.*' => ['string', 'exists:App\Models\Permission,name'], // @todo name or uuid ??
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            $user = User::findByPrimary($request->user);

            foreach($request->permissions as $permission)
            {
                $user->revokePermissionTo($permission);
            }

            return response()->json(["message" => "Aggiornamento permessi riuscito!"], 201);
        } catch (\Exception $e) {
            return CustomHandler::renderCustom($e, "Aggiornamento permessi fallito!");
        }
    }
}
