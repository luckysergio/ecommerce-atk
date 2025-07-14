<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerksTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('merks')->insert([
            ['nama' => 'Joyko', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Faber-Castell', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kenko', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Standard', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Deli', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
