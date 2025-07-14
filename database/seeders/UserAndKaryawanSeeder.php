<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAndKaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('nama', 'Admin')->value('id');

        $adminUserId = DB::table('users')->insertGetId([
            'email' => 'admin@atk.com',
            'password' => Hash::make('passwordadmin'),
            'id_role' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('karyawans')->insert([
            'id_user' => $adminUserId,
            'nama' => 'Admin Utama',
            'no_hp' => '081234567891',
            'alamat' => 'UMT',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
