<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('Request:', ['url' => $request->fullUrl(), 'method' => $request->method(), 'input' => $request->all()]);
        $response = $next($request);
        Log::info('Response:', ['status' => $response->status(), 'content' => $response->getContent()]);
        return $response;
    }
}
