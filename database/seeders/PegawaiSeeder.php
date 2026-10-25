<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pegawai@inventrack.test'],
            [
                'name' => 'Pegawai InvenTrack',
                'password' => Hash::make('Pegawai123!'),
                'role' => 'pegawai',
            ]
        );
    }
}