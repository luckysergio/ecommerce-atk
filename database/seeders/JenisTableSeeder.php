<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis')->insert([
            ['nama' => 'Kertas', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pulpen', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pensil', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Penghapus', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Spidol', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Map / Ordner', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
