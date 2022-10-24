<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Tiendas;
use App\Models\TiendasUsuarios;
use Illuminate\Http\Request;

class TiendasController extends Controller{
    public function listData(Request $request){
        $tiendas = Tiendas::get();
        return response()->json([
            'response'=> 200,
            'message' => 'datos listados correctamente',
            'data' => [
                'tiendas' => $tiendas
            ]
            ],200);
    }

    // public function listDataByUsuario(Request $request){
    //      $tiendas_usuarios = TiendasUsuarios::leftJoin('tiendas', 'tiendas.codigo_tienda', 'tiendas_usuarios.codigo_tienda')
    //      ->select('tiendas.*')
    //      ->where('tiendas_usuarios.usuario_id', $request->usuario_id)->get();
    //      return response()->json([
    //          'response' => 200,
    //          'message' => 'Datos listados con éxito',
    //          'data' => [
    //              'tiendas_usuarios' => $tiendas_usuarios
    //          ]
    //          ], 200);
    //  }
}
