<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'      => 'Rara Super Admin',
                'username'  => 'superadmin',
                'email'     => 'superadmin@gmail.com',
                'password'  => Hash::make('Rara1357!'),
                'nik'       => '03100',
                'phone'     => '081111111111',
                'role'      => 'admin_super', // ← enum, bukan role_id
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Admin Checklist',
                'username'  => 'admin',
                'email'     => 'admin@gmail.com',
                'password'  => Hash::make('Admin@2026!'),
                'nik'       => '0299',
                'phone'     => '082222222222',
                'role'      => 'admin',        // ← enum
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Karyawan SOC',
                'username'  => 'user1',
                'email'     => 'user1@gmail.com',
                'password'  => Hash::make('User@2026!'),
                'nik'       => '9978',
                'phone'     => '083333333333',
                'role'      => 'user',          // ← enum
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Manajer SOC',
                'username'  => 'approver',
                'email'     => 'approver@gmail.com',
                'password'  => Hash::make('Approver@2026!'),
                'nik'       => '9833',
                'phone'     => '084444444444',
                'role'      => 'approver',      // ← enum
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✅ 4 user berhasil dibuat!');
    }
}