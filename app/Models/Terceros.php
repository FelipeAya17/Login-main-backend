<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terceros extends Model{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'terceros';
    public function tipoDocumento(){
        return $this->belongsTo(TiposDocumentos::class);
    }
    public static function saveData($data, $tipo_tercero, $is_crear_tercero = true ){
        $data['fecha_registro'] = Carbon::now();
        return Terceros::create($data);
    }
    public static function updateData($data, $tipo_tercero){
        $tercero = Terceros::find($data['id']);
        $tercero->tipo_documento_id = $data['tipo_documento_id'];
        $tercero->numero_documento = $data['numero_documento'];
        $tercero->nombre = $data['nombres'];
        $tercero->apellidos = $data['apellidos'];
        $tercero->correo_electronico = $data['correo_electronico'];
        $tercero->numero_telefonico = $data['numero_telefonico'];
		$tercero->numero_celular = $data['numero_celular'];
        $tercero->direccion_residencia = $data['direccion_residencia'];
        $tercero->genero_id = $data['genero_id'];
        $tercero->usuario_actualiza_id = $data['usuario_actualiza_id'];
        $tercero->fecha_modifica = Carbon::now();
        $tercero->save();
        return $tercero;
    }
}
