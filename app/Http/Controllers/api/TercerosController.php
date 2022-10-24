<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Terceros;
use App\Models\TiendasUsuarios;
use App\Models\TercerosDirecciones;

use App\Http\Requests\TercerosFormRequest;
use App\Http\Requests\TercerosDireccionFormRequest;

use JWTAuth;
use Illuminate\Support\Facades\DB;


class TercerosController extends Controller{
    public function listData(Request $request){
        $terceros = Terceros::join('tipos_documentos', 'tipos_documentos.id', 'terceros.tipo_documento.id')
        ->leftJoin('users AS user_insert', 'user_insert.id', 'terceros.usuario_inserta_id')
        ->leftJoin('users', 'users.tercero_id', 'terceros.id')
        ->leftJoin('model_has_role', function($join){
            $join->on('model_has_role.model_id', 'users.id');
            $join->where('model_has_role.model_type', '=', 'App/Models/User');
        })
        ->lefJoin('roles', 'roles.id', 'model_has_roles.role_id')
        ->select(
            'terceros.*',
            'tipos_documento.nombre_tipo_documento',
            'users.activo',
            'users.id as user.id',
            'roles.name as nombre_perfil',
            'user_insert.name AS usuario_crear' 
        );
        $query = $request->input('query');
        if($query != null){
            $terceros = $terceros->whereRaw('LOWER(terceros.nombres) LIKE ?', ['%'.strtolower($query).'%'])
                ->orWhereRaw('LOWER(terceros.apellidos) LIKE ?', ['%'.strtolower($query).'%'])
                ->orWhereRaw('LOWER(terceros.correo_electronico) LIKE ?', ['%'.strtolower($query).'%'])
                ->orWhereRaw('terceros.numero_documento = ?', [$query]);
        }
        return response()->json([
            'response' => 200,
            'message' => 'Datos listados con éxito',
            'data' => [
                'terceros' => $terceros->paginate(50)
            ]
        ], 200);
    }
    public function dataDireccionesByTercero(Request $request) {
        $terceros_direcciones = TercerosDirecciones::join('paises_departamentos_ciudades as ubicacion', function($join){
            $join->on('terceros_direcciones.city_code', 'ubicacion.city_code');
            $join->on('terceros_direcciones.state_code', 'ubicacion.state_code');
            $join->on('terceros_direcciones.country_code', 'ubicacion.country_code');
        })
        ->select(
            'terceros_direcciones.id',
            'terceros_direcciones.nombre_personalizado',
            'terceros_direcciones.direccion',
            'terceros_direcciones.barrio',
            'terceros_direcciones.numero_contacto',
            'ubicacion.country_name',
            'ubicacion.state_name',
            'ubicacion.city_name'
        )->where('tercero_id', $request->tercero)->get();
        return response()->json([
            'response' => 200,
            'message' => 'Datos listados con éxito',
            'data' => [
                'terceros_direcciones' => $terceros_direcciones
            ]
        ], 200);
    }
    public function saveOrUpdateData(TercerosFormRequest $tercerosFormRequest, Terceros $tercero){
        $tercero = null;
        $message = '';
        DB::beginTransaction();
        $usuario_in = JWTAuth::parseToken()->authenticate();
        if($tercerosFormRequest->tercero){
            $usuario = User::where('tercero_id', $tercerosFormRequest->id)->first();
            $tercero = Terceros::updateData([
                'id' => $tercerosFormRequest->id,
                'tipo_documento_id' => $tercerosFormRequest->tipo_documento_id,
                'numero_documento' => $tercerosFormRequest->numero_documento,
                'nombres' => $tercerosFormRequest->nombres,
                'apellidos' => $tercerosFormRequest->apellidos,
                'correo_electronico' => $tercerosFormRequest->correo_electronico,
                'numero_telefonico' => $tercerosFormRequest->numero_telefonico,
                'numero_celular' => $tercerosFormRequest->numero_celular,
                'direccion_residencia' => $tercerosFormRequest->direccion_residencia,
                //'mayorista' => $tercerosFormRequest->mayorista,
                'genero_id' => $tercerosFormRequest->genero,
                'usuario_actualiza_id' => $usuario_in->id
            ], $tercerosFormRequest->tipo_tercero);
            if($usuario){
                User::updateData([
                    'id' => $usuario->id,
                    'email' => $tercerosFormRequest->correo_electronico,
                    'activo' => $tercerosFormRequest->activo,
                    'password' => $tercerosFormRequest->password_usuario,
                    'name' => $tercerosFormRequest->nombres.' '.$tercerosFormRequest->apellidos
                ]);
            }
            $message = 'Tercero actualizado con éxito';
        }else{
            $tercero = Terceros::saveData([
                'tipo_documento_id' => $tercerosFormRequest->tipo_documento_id,
                'numero_documento' => $tercerosFormRequest->numero_documento,
                'nombres' => $tercerosFormRequest->nombres,
                'apellidos' => $tercerosFormRequest->apellidos,
                'correo_electronico' => $tercerosFormRequest->correo_electronico,
                'numero_telefonico' => $tercerosFormRequest->numero_telefonico,
                'numero_celular' => $tercerosFormRequest->numero_celular,
                'direccion_residencia' => $tercerosFormRequest->direccion_residencia,
                //'mayorista' => $tercerosFormRequest->mayorista,
                'genero_id' => $tercerosFormRequest->genero,
                'usuario_inserta_id' => $usuario_in->id
            ], $tercerosFormRequest->tipo_tercero);
            if($tercerosFormRequest->tipo_tercero != 'C'){
                // $role = $tercerosFormRequest->tipo_tercero == 'A' ? 'Administrador' : 'Vendedor';
                $role = null;
                switch($tercerosFormRequest->tipo_tercero) {
                    case 'A':
                        $role = 'SuperAdministrador';
                        break;
                    case 'V':
                        $role = 'Vendedor';
                        break;
                    case 'B':
                        $role = 'Bodega';
                        break;
                    case 'T':
                        $role = 'Tesoreria';
                        break;
                    case 'CON':
                        $role = 'Contador';
                        break;
                    case 'AUX':
                        $role = 'Auxiliar diseño';
                        break;
                    case 'AUD':
                        $role = 'Auditoria';
                        break;
                    case 'EMB':
                        $role = 'Embajador New WAY';
                    case 'REP':
                        $role = 'Repartidor';
                        break;
                }
                $user = User::createUser([
                    'email' => $tercerosFormRequest->correo_electronico,
                    'name' => $tercerosFormRequest->nombres.' '.$tercerosFormRequest->apellidos,
                    'tercero_id' => $tercero->id,
                    'activo' => 1
                ], $tercerosFormRequest->password_usuario);
                $user->assignRole($role);
                TiendasUsuarios::create(['usuario_id' => $user->id, 'codigo_tienda' => $tercerosFormRequest->codigo_tienda]);
            }
            $message = 'Tercero creado con éxito';
        }
        DB::commit();
        return response()->json([
            'response' => 200,
            'message' => $message,
            'data' => Terceros::join('tipos_documentos', 'tipos_documentos.id', 'terceros.tipo_documento_id')
                ->leftJoin('users', 'users.tercero_id', 'terceros.id')
                ->leftJoin('model_has_roles', function($join){
                    $join->on('model_has_roles.model_id', 'users.id');
                    $join->where('model_has_roles.model_type', '=', 'App\Models\User');
                })
                ->leftJoin('roles', 'roles.id', 'model_has_roles.role_id')
                ->select('terceros.*', 'tipos_documentos.nombre_tipo_documento', 'users.activo', 'users.id as user_id', 'roles.name as nombre_perfil')
                ->whereRaw('terceros.id = ?', $tercero->id)->first()
        ], 200);
    }
    public function saveDataDireccionTercero(Terceros $tercero, TercerosDireccionFormRequest $tercerosDireccionFormRequest) {
        DB::beginTransaction();
        $usuario = JWTAuth::parseToken()->authenticate();
        $tercero_direccion = TercerosDirecciones::saveData([
            'tercero_id' => $tercerosDireccionFormRequest->tercero_id,
            'nombre_personalizado' => $tercerosDireccionFormRequest->nombre_personalizado,
            'direccion' => $tercerosDireccionFormRequest->direccion,
            'barrio' => $tercerosDireccionFormRequest->barrio,
            'numero_contacto' => $tercerosDireccionFormRequest->numero_contacto,
            'city_code' => $tercerosDireccionFormRequest->city_code,
            'state_code' => $tercerosDireccionFormRequest->state_code,
            'country_code' => $tercerosDireccionFormRequest->country_code,
            'usuario_inserta_id' => $usuario->id,
        ]);
        DB::commit();
        return response()->json([
            'response' => 200,
            'message' => 'Dirección almacenada con éxito',
            'tercero_direccion_id' => $tercero_direccion->id
        ], 200);
    }
}
