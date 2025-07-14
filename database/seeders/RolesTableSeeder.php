<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nama' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Customer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Manajer', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
