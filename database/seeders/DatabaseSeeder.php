<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Grupo;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $directora = Role::create([
            'nombre' => 'Directora',
            'descripcion' => 'Acceso total al sistema'
        ]);
        $admi = Role::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Gestion administrativa'
        ]);
        $maestro = Role::create([
            'nombre' => 'Maestro',
            'descripcion' => 'Gestión de niños y asistencia'
        ]);
        User::create([
            'role_id' =>$directora->id,
            'nombre' =>'Dunia',
            'apellido' => 'Nostas',
            'email' => 'directora@test.com',
            'password' => Hash::make('12345678'),
            'activo' => true,
        ]);
        User::create([
            'role_id' =>$admi->id,
            'nombre' =>'Vania',
            'apellido' => 'Colque',
            'email' => 'admin1@test.com',
            'password' => Hash::make('12345678'),
            'activo' => true,
        ]);
                User::create([
            'role_id' =>$admi->id,
            'nombre' =>'Adela',
            'apellido' => 'Colque',
            'email' => 'admin2@test.com',
            'password' => Hash::make('12345678'),
            'activo' => true,
        ]);
        $maestroDannaGarcia = User::create([
            'role_id' =>$maestro->id,
            'nombre' =>'Danna',
            'apellido' => 'Garcia',
            'email' => 'mestro1@test.com',
            'password' => Hash::make('12345678'),
            'activo' => true,
        ]);
                $maestroDiegoChore = User::create([
            'role_id' =>$maestro->id,
            'nombre' =>'Diego',
            'apellido' => 'Chore',
            'email' => 'mestro2@test.com',
            'password' => Hash::make('12345678'),
            'activo' => true,
        ]);
                $maestroIviCondori = User::create([
            'role_id' =>$maestro->id,
            'nombre' =>'Ivi',
            'apellido' => 'Condori',
            'email' => 'mestro3@test.com',
            'password' => Hash::make('12345678'),
            'activo' => true,
        ]);
                $maestroJuanCarlosContreras = User::create([
            'role_id' =>$maestro->id,
            'nombre' =>'Juan Carlos',
            'apellido' => 'Contreras',
            'email' => 'mestro4@test.com',
            'password' => Hash::make('12345678'),
            'activo' => true,
        ]);

        // Crear grupos iniciales, cada uno con su maestro asignado
        // (antes esta asignación se inferí­a en NinoController comparando el
        // nombre del grupo contra nombre+apellido hardcodeados; ahora vive en
        // la columna grupos.maestro_id).
        Grupo::create([
            'nombre' => '6 a 8 años',
            'descripcion' => 'Niños nuevos',
            'maestro_id' => $maestroIviCondori->id,
            'activo' => true,
        ]);

        Grupo::create([
            'nombre' => '9 a 11 años',
            'descripcion' => 'Niños en progreso',
            'maestro_id' => $maestroDannaGarcia->id,
            'activo' => true,
        ]);

        Grupo::create([
            'nombre' => '12 a 14 años',
            'descripcion' => 'Niños avanzados',
            'maestro_id' => $maestroDiegoChore->id,
            'activo' => true,
        ]);
        Grupo::create([
            'nombre' => '15 a 18 años',
            'descripcion' => 'Niños en progreso',
            'maestro_id' => $maestroJuanCarlosContreras->id,
            'activo' => true,
        ]);
    }
}
