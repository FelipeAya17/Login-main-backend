<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder{
    public function run(){
        $roleVendedor = Role::create([
            'name' => 'Vendedor'
        ]);
        $roleAdmin = Role::create([
            'name' => 'Bodega'
        ]);
        $roleAdmin = Role::create([
            'name' => 'SuperAdministrador'
        ]);
        $roleAdmin = Role::create([
            'name' => 'Tesoreria'
        ]);
    }
}
