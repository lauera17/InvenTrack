<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
        ]);
        foreach ([
            'Elektronik',
            'Laptop',
            'Kamera',
            'Aksesoris',
            'Peralatan Kantor',
        ] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}