<?php

namespace App\Services;

use App\Models\ApiAccessLog;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class ApiAccessLogService
{
    /**
     * @param Request $request
     * @param HttpResponse $response
     * 
     * @return void
     * 
     */
    public function logRequest(Request $request, HttpResponse $response): void
    {
        ApiAccessLog::create([
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'response_status' => $response->status(),
        ]);
    }
}