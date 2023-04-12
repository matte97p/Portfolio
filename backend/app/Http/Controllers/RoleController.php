<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AbstractCrudController;

class RoleController extends AbstractCrudController {

    /**
     * Custom __construct + parent
     *
     * @var Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @var Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        try{
            $idUtente = $req->input('idutente');
            $ruolo = $req->input('ruolo');

            $ruoloList = new utenti_ruoli_lista;
            $ruoloList->idutente = $idUtente;
            $ruoloList->idruolo = $ruolo;
            $ruoloList->save();

            return response()->json(["message" => "Ruoli aggiornati con successo!"], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "Aggiornamento ruoli fallito!"], 500);
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
