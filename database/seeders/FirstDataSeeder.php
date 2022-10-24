<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Terceros;
use App\Models\Tiendas;
use App\Models\TiendasUsuarios;
use App\Models\TiposDocumentos;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;


class FirstDataSeeder extends Seeder{
    public function run(){
        TiposDocumentos::create([
            'id' => 13,
            'nombre_tipo_documento' => 'Cédula de ciudadanía'
        ]);
        TiposDocumentos::create([
            'id' => 31,
            'nombre_tipo_documento' => 'NIT'
        ]);
        TiposDocumentos::create([
            'id' => 41,
            'nombre_tipo_documento' => 'Pasaporte'
        ]);
        TiposDocumentos::create([
            'id' => 22,
            'nombre_tipo_documento' => 'Cédula extranjería'
        ]);
        TiposDocumentos::create([
            'id' => 42,
            'nombre_tipo_documento' => 'Documento de identificación extranjero'
        ]);
        TiposDocumentos::create([
            'id' => 50,
            'nombre_tipo_documento' => 'NIT de otro país'
        ]);
        TiposDocumentos::create([
            'id' => 91,
            'nombre_tipo_documento' => 'NUIP'
        ]);
        TiposDocumentos::create([
            'id' => 14,
            'nombre_tipo_documento' => 'Permiso especial de permanencia PEP'
        ]);
        TiposDocumentos::create([
            'id' => 11,
            'nombre_tipo_documento' => 'Registro civil'
        ]);
        TiposDocumentos::create([
            'id' => 43,
            'nombre_tipo_documento' => 'Sin identificación del exterior'
        ]);
        TiposDocumentos::create([
            'id' => 21,
            'nombre_tipo_documento' => 'Tarjeta de extranjería'
        ]);
        TiposDocumentos::create([
            'id' => 12,
            'nombre_tipo_documento' => 'Tarjeta de identidad'
        ]);

        
        $tercero = Terceros::create([
            'tipo_documento_id' => 13,
            'numero_documento' => '1234567890',
            'nombres' => 'Administrador',
            'apellidos' => '@0',
            'correo_electronico' => 'ventas@newwayurban.com',
            'fecha_registro' => Carbon::now()
        ]);
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'ventas@newwayurban.com',
            'password' => Hash::make('newwayurban2022'),
            'tercero_id' => $tercero->id,
            'activo' => 1,
            'created_at' => Carbon::now()
        ]);
        $user->assignRole('SuperAdministrador');
        Tiendas::create([
            'codigo_tienda' => '001',
            'nombre_tienda' => 'Bodega Principal'
        ]);
        Tiendas::create([
            'codigo_tienda' => '002',
            'nombre_tienda' => 'Show room'
        ]);
    }
}
