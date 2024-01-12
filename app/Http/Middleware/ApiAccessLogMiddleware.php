<?php 

namespace App\Http\Middleware;

use App\Services\ApiAccessLogService;
use Closure;

class ApiAccessLogMiddleware
{
    protected $logService;

    public function __construct(ApiAccessLogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * @param mixed $request
     * @param Closure $next
     * 
     * @return mixed
     * 
     */
    public function handle($request, Closure $next): mixed
    {
        $response = $next($request);
        $this->logService->logRequest($request, $response);

        return $response;
    }
}