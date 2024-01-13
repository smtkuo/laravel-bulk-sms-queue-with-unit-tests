<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success($data, $message = 'Operation Successful', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function error($message = 'An error occurred', $code = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}
