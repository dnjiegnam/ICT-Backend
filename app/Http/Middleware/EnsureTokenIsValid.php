<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $authToken = $_SERVER['HTTP_AUTHENTICATION'];
            $uri = $_SERVER['REQUEST_URI'];
            $token = explode(" ", $authToken)[1];
            if ($user = PersonalAccessToken::findToken($token)) {
                $role = (strtolower($user->tokenable->with('roles')->first()->roles->name));
                if (sizeof(explode($role, $uri)) > 1 || sizeof(explode("all", $uri)) > 1 ) {
                    return $next($request);
                }

                return response([
                    'error' => 'error',
                    'message' => 'You dont have permission to access to this page!',
                    'icon' => 'error',
                    'route' => '/pages/' . $role
                ], 401);
            }
            return response([
                'error' => 'error',
                'message' => 'You need to login before access to this page!',
                'icon' => 'error',
                'route' => '/'
            ], 401);
        } catch (\Throwable $th) {
            return response([
                'error' => 'error',
                'message' => 'Oops someting where wrong!!' . $th->getMessage(),
                'icon' => 'error',
                'route' => '/'
            ], 401);
        }
    }
}
