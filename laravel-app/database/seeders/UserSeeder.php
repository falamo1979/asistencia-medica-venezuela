<?php

namespace Database\Seeders;

use App\Models\Medico;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@centro.med'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_ADMIN,
            ]
        );

        $medico = Medico::firstOrCreate(
            ['matricula' => 'MED001'],
            ['nombre' => 'Ana', 'apellido' => 'Pérez', 'especialidad' => 'Medicina General']
        );

        User::updateOrCreate(
            ['email' => 'medico@centro.med'],
            [
                'name'      => 'Dra. Ana Pérez',
                'password'  => Hash::make('password'),
                'role'      => User::ROLE_MEDICO,
                'medico_id' => $medico->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'recepcion@centro.med'],
            [
                'name'     => 'Recepción',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_RECEPCION,
            ]
        );
    }
}
