<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * Return a success JSON response.
     *
     * @param mixed $data    The data to be returned.
     * @param string $message The success message.
     * @param int $code      The HTTP status code.
     *
     * @return JsonResponse
     */
    public static function success($data, string $message = 'Operation Successful', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status' => $code,
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message The error message.
     * @param int $code      The HTTP status code.
     *
     * @return JsonResponse
     */
    public static function error(string $message = 'An error occurred', int $code = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status' => $code,
        ], $code);
    }
}
