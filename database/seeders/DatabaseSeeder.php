<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FrameworkSeeder::class,
            DomainSeeder::class,
            KategoriSeeder::class,
            UserSeeder::class,
            PertanyaanSeeder::class,
        ]);
    }
}