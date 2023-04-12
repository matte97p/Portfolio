<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AbstractCrudController;

class RoleController extends AbstractCrudController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /* @todo */
    public function create(Request $request): JsonResponse
    {
        try{
            throw new Exception('Not implemented');
        } catch (\Exception $e) {
            return response()->json(["message" => "Creazione fallita!"], 500);
        }
    }

    /* @todo */
    public function index(Request $request): JsonResponse
    {
        try{
            $this->request();
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
