<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrameworkSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('frameworks')->insert([
            'name_framework' => 'NIST Cybersecurity Framework',
            'description' => 'Framework keamanan siber yang dikembangkan oleh National Institute of Standards and Technology versi 2.0.',
            'is_active' => true,
            'pic_user_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}