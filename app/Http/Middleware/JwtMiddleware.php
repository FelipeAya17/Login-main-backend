<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware{
    public function handle($request, Closure $next){
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'response' => 400,
                    'message' => 'Token inválido'
                ], 400);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    'response' => 400,
                    'message' => 'La sesión ha expirado'
                ], 400);
            }else{
                return response()->json([
                    'response' => 400,
                    'message' => 'Autorización fallida'
                ], 400);
            }
        }
        return $next($request);
    }
}
