<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthenticate
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


        if(!isset($request->header()['authorization']) || empty($request->header()['authorization'])){

            $response = [
                'status_code' => 401,
                'status_description' => 'Unauthorized',
                'data'    => [],
                'error_messages' => 'Authorization Failed',
            ];
            return response()->json($response,401);
        }

        return $next($request);
    }
}
