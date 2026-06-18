<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [
            ['kode_domain' => 'GV', 'nama_domain' => 'Govern'],
            ['kode_domain' => 'ID', 'nama_domain' => 'Identify'],
            ['kode_domain' => 'PR', 'nama_domain' => 'Protect'],
            ['kode_domain' => 'DE', 'nama_domain' => 'Detect'],
            ['kode_domain' => 'RS', 'nama_domain' => 'Respond'],
            ['kode_domain' => 'RC', 'nama_domain' => 'Recover'],
        ];

        foreach ($domains as $domain) {
            DB::table('domains')->insert([
                'framework_id' => 1,
                'kode_domain' => $domain['kode_domain'], // fix: kode → kode_domain
                'nama_domain' => $domain['nama_domain'],
                // target_nilai dihapus
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}   