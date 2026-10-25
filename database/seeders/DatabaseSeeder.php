<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@inventrack.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pegawai@inventrack.test'],
            [
                'name' => 'Pegawai Demo',
                'password' => Hash::make('Pegawai123!'),
                'role' => 'pegawai',
            ]
        );
    }
}