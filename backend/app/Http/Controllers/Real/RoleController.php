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
    protected static $errors = [
        'id.required' => 'Inserire l\'id',
        'id.integer' => 'Errore nella modifica',
        'name.required' => 'Inserire il nome',
        'name.max' => 'Nome troppo lungo',
        'name.string' => 'Il nome deve essere un testo',
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
                    'name' => ['required', 'string', 'max:50'],
                ],
                self::$errors,
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            $role = RoleSpatie::findOrCreate($request->name);

            return response()->json(["message" => "Creazione riuscita!", "data" => $role], 200);
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
                    'id' => ['required', 'integer'],
                    'name' => ['required', 'string', 'max:50'],
                ],
                self::$errors,
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            $role = Role::findOrFail($request->id);

            $role->update(['name' => $request->name]);

            return response()->json(["message" => "Aggiornamento riuscito!", "data" => $role], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Aggiornamento fallito!", "error" => $e->getMessage()], 500);
        }
    }

    /* @todo */
    public function delete(Request $request): JsonResponse
    {
        try{
            $validator = Validator::make($request->all(),
                [
                    'id' => ['required', 'integer'],
                ],
                self::$errors,
            );

            if ($validator->fails()) return response()->json(["message" => $validator->errors()->all()], 406);

            $role = Role::findOrFail($request->id)->softDeletes();

            return response()->json(["message" => "Aggiornamento riuscito!"], 204);
        } catch (\Exception $e) {
            return response()->json(["message" => "Cancellazione fallita!", "error" => $e->getMessage()], 500);
        }
    }

    public function list()
    {
        return RoleSpatie::all()->pluck('name');
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
