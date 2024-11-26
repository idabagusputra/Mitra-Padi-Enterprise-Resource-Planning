<?php

// database/seeders/DebitSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DebitSeeder extends Seeder
{
    public function run()
    {
        // Hapus semua data di tabel debits sebelum menyimpan data baru
        DB::table('debits')->truncate();

        // Menambahkan data dummy ke tabel debits
        DB::table('debits')->insert([
            [
                'kredit_id' => 1,  // Sesuaikan dengan ID Kredit yang ada
                'jumlah' => 5000.00,
                'tanggal' => '2024-10-04',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kredit_id' => 2,
                'jumlah' => 15000.00,
                'tanggal' => '2024-10-04',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kredit_id' => 3,
                'jumlah' => 25000.00,
                'tanggal' => '2024-10-04',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
