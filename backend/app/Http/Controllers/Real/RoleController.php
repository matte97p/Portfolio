<?php

namespace App\Http\Controllers\Real;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Exceptions\CustomHandler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\AbstractCrudController;
use Spatie\Permission\Models\Role as RoleSpatie;

class RoleController extends AbstractCrudController
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
                    'name' => ['required', 'string', 'unique:App\Models\Role', 'max:50'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            $response = RoleSpatie::updateOrCreate($request->all());

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
                    'id' => ['required', 'integer', 'exists:App\Models\Role'],
                    'name' => ['required', 'string', 'unique:App\Models\Role', 'max:50'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            $response = Role::findOrFail($request->id);

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
                    'id' => ['required', 'integer', 'exists:App\Models\Role'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) throw ValidationException::withMessages($validator->errors()->all());

            Role::findOrFail($request->id)->delete();

            return response()->json(["message" => "Cancellazione riuscita!"], 204);
        } catch (\Exception $e) {
           return CustomHandler::renderCustom($e, "Cancellazione fallita!");
        }
    }

    public function list(): object
    {
        return Role::all()->pluck('name');
    }

    /* @todo */
    public function setRole(Request $request): JsonResponse
    {
        try{
            $idUtente = $req->input('idutente');
            $ruolo = $req->input('ruolo');

            $ruoloList = new utenti_ruoli_lista;
            $ruoloList->idutente = $idUtente;
            $ruoloList->idruolo = $ruolo;
            $ruoloList->save();

            return response()->json(["message" => "Ruoli aggiornati con successo!"], 200);
        } catch (\Exception $e) {
            return CustomHandler::renderCustom($e, "Aggiornamento ruoli fallito!");
        }
    }
}
