<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MockSmsController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return mixed
     * 
     */
    public function send(Request $request): mixed
    {
        // Mock response
        return response()->json([
            'message' => 'SMS sent successfully', 
            'status' => 'success', 
            'request' => $request->all(), 
            'time' => time()
        ], 200);
    }
}