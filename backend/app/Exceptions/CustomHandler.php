<?php

namespace App\Exceptions;

use Throwable;
use PDOException;
use \Psr\Log\LogLevel;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomHandler
{
    public static function renderCustom(Throwable $e, $message = "")
    {
        if ($e instanceof NotFoundHttpException) {
            return response()->json(["message" => "Pagina non trovata"], $e->status);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json(["message" => $message, "error" => "Dato non trovato."], 404);
        }

        if ($e instanceof ValidationException) {
            return response()->json(["message" => $message, "error" => $e->errors()], $e->status);
        }

        return response()->json(["message" => $message, "error" => $e->getMessage()], $e->status ?? 500);
    }
}
