<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        
        $rolAdmin = Role::where('nombre', 'Administrador')->first();
        $rolEmpleado = Role::where('nombre', 'Empleado')->first();

        // administrador
        User::firstOrCreate(
            ['email' => 'admin@demo.com'], 
            [
                'name' => 'Administrador Demo',
                'password' => '123', 
                'role_id' => $rolAdmin->id,
            ]
        );

        // empleado
        User::firstOrCreate(
            ['email' => 'empleado@demo.com'], 
            [
                'name' => 'Empleado Demo',
                'password' => '123', 
                'role_id' => $rolEmpleado->id,
            ]
        );
    }
}
