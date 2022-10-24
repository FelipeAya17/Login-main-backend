<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Carbon\Carbon;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthApiController extends Controller{
    public function authenticate(Request $request){
        $credentials = $request->only('email', 'password');
        //$credentials['activo'] = true;
        $token = JWTAuth::attempt($credentials, Carbon::now()->addDays(7)->timestamp);
        try{
            if(!$token) {
                return response()->json([
                    'response' => 500,
                    'message' => 'Credenciales no validas'
                ], 500);
            }
        } catch(JWTException $e){
            return response()->json([
                'response' => 500,
                'message' => 'Error al acceder con las credenciales'
            ], 500);
        }
        User::actualizarUltimoAcceso($request->email);
        $user = \Auth::user();
        return response()->json([
            'response' => 200,
            'message' => 'Ingreso exitoso',
            'data' => [
                'token' => $token,
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
            ], 200);
    }
}
