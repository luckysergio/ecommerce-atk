<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdukTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $records = [];

        $jenis = DB::table('jenis')->pluck('id', 'nama');
        $merks = DB::table('merks')->pluck('id', 'nama');

        $data = [
            [
                'jenis' => 'Pensil',
                'merk' => 'Faber-Castell',
                'nama' => 'Pensil Kayu Faber-Castell 2B',
                'harga_beli' => 800,
                'harga_jual' => 2000,
                'qty' => 100,
            ],
            [
                'jenis' => 'Pulpen',
                'merk' => 'Standard',
                'nama' => 'Pulpen Standard 0.7mm',
                'harga_beli' => 1000,
                'harga_jual' => 2500,
                'qty' => 150,
            ],
            [
                'jenis' => 'Spidol',
                'merk' => 'Joyko',
                'nama' => 'Spidol Joyko Permanent Marker',
                'harga_beli' => 1500,
                'harga_jual' => 3000,
                'qty' => 80,
            ],
            [
                'jenis' => 'Penghapus',
                'merk' => 'Deli',
                'nama' => 'Penghapus Deli White',
                'harga_beli' => 500,
                'harga_jual' => 1200,
                'qty' => 200,
            ],
        ];

        foreach ($data as $item) {
            $id_jenis = $jenis[$item['jenis']] ?? null;
            $id_merk = $merks[$item['merk']] ?? null;

            if (!$id_jenis || !$id_merk) {
                throw new \Exception("ID jenis atau merk tidak ditemukan untuk {$item['nama']}");
            }

            for ($i = 1; $i <= 1; $i++) {
                $records[] = [
                    'id_jenis' => $id_jenis,
                    'id_merk' => $id_merk,
                    'nama' => "{$item['nama']} #$i",
                    'status' => 'tersedia',
                    'harga_beli' => $item['harga_beli'],
                    'harga_jual' => $item['harga_jual'],
                    'qty' => $item['qty'],
                    'foto' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('products')->insert($records);
    }
}
