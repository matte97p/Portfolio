<?php

namespace App\Http\Controllers\Real;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AbstractCrudController;
use Spatie\Permission\Models\Role as RoleSpatie;
use Spatie\Permission\Models\Permission as PermissionSpatie;

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
                    'name' => ['required', 'string', 'max:50'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            $response = RoleSpatie::findOrCreate($request->name);

            return response()->json(["message" => "Creazione riuscita!", "data" => $response], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Creazione fallita!", "error" => $e->getMessage()], 500);
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

    public function update(Request $request): JsonResponse
    {
        try{
            $validator = Validator::make($request->all(),
                [
                    'id' => ['required', 'integer', 'exists:roles,id'],
                    'name' => ['required', 'string', 'max:50'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            $response = Role::findOrFail($request->id);

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
                    'id' => ['required', 'int', 'exists:roles,id'],
                ],
                $this::$errors,
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            Role::withTrashed()->findOrFail($request->id)->delete();

            return response()->json(["message" => "Cancellazione riuscita!"], 204);
        } catch (\Exception $e) {
            return response()->json(["message" => "Cancellazione fallita!", "error" => $e->getMessage()], 500);
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
            return response()->json(["message" => "Aggiornamento ruoli fallito!"], 500);
        }
    }
}
