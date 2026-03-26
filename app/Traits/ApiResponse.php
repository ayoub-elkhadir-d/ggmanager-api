<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success($data = null, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $code);
    }

    protected function error(string $message = '', int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors
        ], $code);
    }

    protected function forbidden(string $message = 'Forbidden', $errors = null): JsonResponse
    {
        return $this->error($message, 403, $errors);
    }

    protected function notFound(string $message = 'Not Found', $errors = null): JsonResponse
    {
        return $this->error($message, 404, $errors);
    }

    protected function validationError($errors, string $message = 'Validation error'): JsonResponse
    {
        return $this->error($message, 422, $errors);
    }

    protected function unauthorized(string $message = 'Unauthorized', $errors = null): JsonResponse
    {
        return $this->error($message, 401, $errors);
    }

    protected function conflict(string $message = 'Conflict', $errors = null): JsonResponse
    {
        return $this->error($message, 409, $errors);
    }
}
