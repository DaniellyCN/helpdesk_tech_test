<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;

class CustomException extends ExceptionHandler
{
    public function render($request, Throwable $e): JsonResponse
    {
        if ($e instanceof ErrorException) {
            return $this->handleErrorException($request, $e);
        } elseif ($e instanceof QueryException) {
            return $this->handleQueryException($request, $e);
        }

        return parent::render($request, $e);
    }

    protected function handleErrorException($request, ErrorException $e): JsonResponse
    {
        return response()->json([
            'message' => 'Internal error',
            'status' => 500
        ], 500);
    }

    protected function handleQueryException($request, QueryException $e): JsonResponse
    {
        return response()->json([
            'message' => 'Database error',
            'status' => 500
        ], 500);
    }
}
