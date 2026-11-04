<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ConsultaUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'consulta@transportesrbl.com'],
            [
                'name' => 'Usuario Consulta',
                'password' => Hash::make('consulta123'),
                'rol' => 'consulta',
            ]
        );
    }
}
