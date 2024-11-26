<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kredit;
use App\Models\Petani;
use Carbon\Carbon;

class KreditSeeder extends Seeder
{
    public function run()
    {
        $data = [

            // Data Hutang Petani


            // Wanaprasta
            ['nama' => 'Metu wid', 'tanggal' => '2024-10-31', 'jumlah' => 11253200],
            ['nama' => 'Metu wid', 'tanggal' => '2024-11-19', 'jumlah' => 20000000],

            ['nama' => 'Pan Krisna', 'tanggal' => '2024-06-18', 'jumlah' => 3000000],
            ['nama' => 'Pan Krisna', 'tanggal' => '2024-07-12', 'jumlah' => 2000000],
            ['nama' => 'Pan Krisna', 'tanggal' => '2024-08-01', 'jumlah' => 1000000],
            ['nama' => 'Pan Krisna', 'tanggal' => '2024-08-14', 'jumlah' => 2500000],
            ['nama' => 'Pan Krisna', 'tanggal' => '2024-09-02', 'jumlah' => 1000000],
            ['nama' => 'Pan Krisna', 'tanggal' => '2024-10-19', 'jumlah' => 1500000],

            ['nama' => 'Pan dut', 'tanggal' => '2024-11-02', 'jumlah' => 3000000],

            ['nama' => 'Pan Vidia', 'tanggal' => '2024-09-23', 'jumlah' => 10000000],

            ['nama' => 'Jika min', 'tanggal' => '2024-07-31', 'jumlah' => 11261510],
            ['nama' => 'Jika min', 'tanggal' => '2024-11-17', 'jumlah' => 2000000],

            ['nama' => 'Dewa aji wnp', 'tanggal' => '2024-08-15', 'jumlah' => 1225750],
            ['nama' => 'Dewa aji wnp', 'tanggal' => '2024-08-15', 'jumlah' => 15000000],
            ['nama' => 'Dewa aji wnp', 'tanggal' => '2024-09-17', 'jumlah' => 2000000],
            ['nama' => 'Dewa aji wnp', 'tanggal' => '2024-10-01', 'jumlah' => 3200000],
            ['nama' => 'Dewa aji wnp', 'tanggal' => '2024-10-10', 'jumlah' => 1000000],

            ['nama' => 'Ajin supra', 'tanggal' => '2024-10-17', 'jumlah' => 2500000],

            ['nama' => 'Gus supra', 'tanggal' => '2024-10-23', 'jumlah' => 2000000],
            ['nama' => 'Gus supra', 'tanggal' => '2024-11-05', 'jumlah' => 2000000],

            // Palasari
            ['nama' => 'Meman indah', 'tanggal' => '2024-10-24', 'jumlah' => 20000000],
            ['nama' => 'Meman indah', 'tanggal' => '2024-11-15', 'jumlah' => 500000],

            ['nama' => 'Men epan', 'tanggal' => '2024-10-29', 'jumlah' => 20000000],

            ['nama' => 'Men yadnya', 'tanggal' => '2024-06-30', 'jumlah' => 3000000],
            ['nama' => 'Men yadnya', 'tanggal' => '2024-11-04', 'jumlah' => 2000000],

            ['nama' => 'Ibun wid', 'tanggal' => '2024-10-22', 'jumlah' => 7000000],

            ['nama' => 'Men vera', 'tanggal' => '2024-09-11', 'jumlah' => 5000000],
            ['nama' => 'Men vera', 'tanggal' => '2024-10-22', 'jumlah' => 14000000],

            ['nama' => 'Pan gede ari', 'tanggal' => '2024-06-29', 'jumlah' => 1476578],

            ['nama' => 'Nini', 'tanggal' => '2024-10-10', 'jumlah' => 500000],
            ['nama' => 'Nini', 'tanggal' => '2024-10-12', 'jumlah' => 5000000],
            ['nama' => 'Nini', 'tanggal' => '2024-10-15', 'jumlah' => 300000],
            ['nama' => 'Nini', 'tanggal' => '2024-10-23', 'jumlah' => 5000000],
            ['nama' => 'Nini', 'tanggal' => '2024-10-25', 'jumlah' => 3000000],
            ['nama' => 'Nini', 'tanggal' => '2024-11-07', 'jumlah' => 445000],
            ['nama' => 'Nini', 'tanggal' => '2024-11-10', 'jumlah' => 2000000],

            ['nama' => 'Ajin widana', 'tanggal' => '2024-11-17', 'jumlah' => 85474180],

            ['nama' => 'Pan erik', 'tanggal' => '2024-11-17', 'jumlah' => 25000000],

            ['nama' => 'Men suri', 'tanggal' => '2024-10-09', 'jumlah' => 2000000],
            ['nama' => 'Men suri', 'tanggal' => '2024-10-25', 'jumlah' => 500000],
            ['nama' => 'Men suri', 'tanggal' => '2024-10-11', 'jumlah' => 3000000],

            ['nama' => 'Gusde giri', 'tanggal' => '2024-10-01', 'jumlah' => 15000000],
            ['nama' => 'Gusde giri', 'tanggal' => '2024-11-11', 'jumlah' => 10000000],

            ['nama' => 'Gus sayang', 'tanggal' => '2024-10-25', 'jumlah' => 5000000],
            ['nama' => 'Gus sayang', 'tanggal' => '2024-10-30', 'jumlah' => 5000000],
            ['nama' => 'Gus sayang', 'tanggal' => '2024-11-10', 'jumlah' => 5000000],
            ['nama' => 'Gus sayang', 'tanggal' => '2024-11-15', 'jumlah' => 10000000],

            // Penebel
            ['nama' => 'Pan cantik', 'tanggal' => '2024-07-09', 'jumlah' => 7000000],

            ['nama' => 'Pan artana', 'tanggal' => '2024-11-03', 'jumlah' => 5000000],

            ['nama' => 'Men erna', 'tanggal' => '2024-10-11', 'jumlah' => 3000000],

            ['nama' => 'Pan febri', 'tanggal' => '2024-11-08', 'jumlah' => 3500000],

            ['nama' => 'Men jimi', 'tanggal' => '2024-08-09', 'jumlah' => 3000000],
            ['nama' => 'Men jimi', 'tanggal' => '2024-08-21', 'jumlah' => 2000000],
            ['nama' => 'Men jimi', 'tanggal' => '2024-11-05', 'jumlah' => 5500000],

            ['nama' => 'Pan ica', 'tanggal' => '2024-10-16', 'jumlah' => 1000000],
            ['nama' => 'Pan ica', 'tanggal' => '2024-11-10', 'jumlah' => 2000000],

            ['nama' => 'Men Siska', 'tanggal' => '2024-11-15', 'jumlah' => 12000000],

            ['nama' => 'Men andi', 'tanggal' => '2024-11-08', 'jumlah' => 5000000],

            // Sausu
            ['nama' => 'Pak tambir', 'tanggal' => '2024-09-01', 'jumlah' => 10000000],

            ['nama' => 'Blebed', 'tanggal' => '2024-08-28', 'jumlah' => 15000000],
            ['nama' => 'Blebed', 'tanggal' => '2024-10-30', 'jumlah' => 2000000],

            ['nama' => 'Sukrening', 'tanggal' => '2024-10-24', 'jumlah' => 2000000],

            ['nama' => 'Pan iluh', 'tanggal' => '2024-09-19', 'jumlah' => 7000000],
            ['nama' => 'Pan iluh', 'tanggal' => '2024-11-02', 'jumlah' => 3000000],

            ['nama' => 'Ranti', 'tanggal' => '2024-10-13', 'jumlah' => 3000000],

            ['nama' => 'Pan anis', 'tanggal' => '2024-10-02', 'jumlah' => 4000000],

            ['nama' => 'Pan melda', 'tanggal' => '2024-08-20', 'jumlah' => 6000000],
            ['nama' => 'Pan melda', 'tanggal' => '2024-11-15', 'jumlah' => 4000000],

            ['nama' => 'Made suartana', 'tanggal' => '2024-09-24', 'jumlah' => 10000000],
            ['nama' => 'Made suartana', 'tanggal' => '2024-11-12', 'jumlah' => 3000000],

            ['nama' => 'Pak bur', 'tanggal' => '2024-09-23', 'jumlah' => 7000000],
            ['nama' => 'Pak bur', 'tanggal' => '2024-10-09', 'jumlah' => 3000000],

            ['nama' => 'Pak kardu', 'tanggal' => '2024-08-26', 'jumlah' => 700000],
            ['nama' => 'Pak kardu', 'tanggal' => '2024-09-19', 'jumlah' => 6000000],

            ['nama' => 'Dewa subrata', 'tanggal' => '2024-08-22', 'jumlah' => 3000000],

            ['nama' => 'Kadek wijana', 'tanggal' => '2024-02-26', 'jumlah' => 1000000],
            ['nama' => 'Kadek wijana', 'tanggal' => '2024-06-20', 'jumlah' => 4000000],

            ['nama' => 'Pak peri', 'tanggal' => '2024-09-08', 'jumlah' => 10000000],

            ['nama' => 'Pak sadre', 'tanggal' => '2024-10-05', 'jumlah' => 3000000],
            ['nama' => 'Pak sadre', 'tanggal' => '2024-10-30', 'jumlah' => 500000],

            ['nama' => 'Pak lemek', 'tanggal' => '2024-09-03', 'jumlah' => 4000000],

            ['nama' => 'Budi', 'tanggal' => '2024-09-15', 'jumlah' => 5000000],

            ['nama' => 'Pak carik', 'tanggal' => '2024-10-22', 'jumlah' => 2000000],

            ['nama' => 'Men suki', 'tanggal' => '2024-08-22', 'jumlah' => 8000000],
            ['nama' => 'Men suki', 'tanggal' => '2024-09-23', 'jumlah' => 16000000],
            ['nama' => 'Men suki', 'tanggal' => '2024-10-17', 'jumlah' => 5000000],
            ['nama' => 'Men suki', 'tanggal' => '2024-11-11', 'jumlah' => 21000000],

            ['nama' => 'Pan satya', 'tanggal' => '2024-11-17', 'jumlah' => 5000000],

            // Sukasada
            ['nama' => 'Pan gede adi sksd', 'tanggal' => '2024-11-03', 'jumlah' => 35000000],

            ['nama' => 'Pan pindi', 'tanggal' => '2024-10-19', 'jumlah' => 15000000],
            ['nama' => 'Pan pindi', 'tanggal' => '2024-10-30', 'jumlah' => 5000000],

            ['nama' => 'Pan gede antara', 'tanggal' => '2024-10-29', 'jumlah' => 11000000],

            // Bali Indah
            ['nama' => 'Ajin sayu kembar', 'tanggal' => '2024-10-13', 'jumlah' => 20000000],

            ['nama' => 'Pan gibran', 'tanggal' => '2024-08-19', 'jumlah' => 10000000],
            ['nama' => 'Pan gibran', 'tanggal' => '2024-09-04', 'jumlah' => 5000000],
            ['nama' => 'Pan gibran', 'tanggal' => '2024-11-03', 'jumlah' => 5000000],

            ['nama' => 'Ajik eri', 'tanggal' => '2024-10-01', 'jumlah' => 15000000],

            ['nama' => 'Ajik wira', 'tanggal' => '2024-07-06', 'jumlah' => 15000000],
            ['nama' => 'Ajik wira', 'tanggal' => '2024-09-18', 'jumlah' => 5000000],

            ['nama' => 'Pan widana', 'tanggal' => '2024-10-24', 'jumlah' => 30000000],

            ['nama' => 'Pan kevin', 'tanggal' => '2024-10-24', 'jumlah' => 50000000],

            ['nama' => 'Pan Rio', 'tanggal' => '2024-08-09', 'jumlah' => 17000000],
            ['nama' => 'Pan Rio', 'tanggal' => '2024-10-15', 'jumlah' => 4000000],
            ['nama' => 'Pan Rio', 'tanggal' => '2024-11-15', 'jumlah' => 1000000],

            ['nama' => 'Pak selvi', 'tanggal' => '2024-06-18', 'jumlah' => 10000000],

            ['nama' => 'Pak rehan', 'tanggal' => '2024-07-29', 'jumlah' => 40000000],

            ['nama' => 'Pan dian BI', 'tanggal' => '2024-09-13', 'jumlah' => 25000000],

            ['nama' => 'Ajik mita', 'tanggal' => '2024-10-24', 'jumlah' => 10000000],

            ['nama' => 'Men parel', 'tanggal' => '2024-10-25', 'jumlah' => 1000000],


            ['nama' => 'Pan dedi', 'tanggal' => '2024-08-07', 'jumlah' => 5000000],
            ['nama' => 'Pan dedi', 'tanggal' => '2024-07-26', 'jumlah' => 5000000],
            ['nama' => 'Pan dedi', 'tanggal' => '2024-08-27', 'jumlah' => 4000000],
            ['nama' => 'Pan dedi', 'tanggal' => '2024-09-17', 'jumlah' => 2000000],
            ['nama' => 'Pan dedi', 'tanggal' => '2024-10-30', 'jumlah' => 5000000],

            ['nama' => 'Men bagas', 'tanggal' => '2024-07-22', 'jumlah' => 5000000],
            ['nama' => 'Men bagas', 'tanggal' => '2024-08-21', 'jumlah' => 3000000],
            ['nama' => 'Men bagas', 'tanggal' => '2024-09-02', 'jumlah' => 3000000],
            ['nama' => 'Men bagas', 'tanggal' => '2024-09-29', 'jumlah' => 1000000],

            ['nama' => 'Pan Meri', 'tanggal' => '2024-06-30', 'jumlah' => 22000000],
            ['nama' => 'Pan Meri', 'tanggal' => '2024-07-31', 'jumlah' => 2000000],
            ['nama' => 'Pan Meri', 'tanggal' => '2024-09-01', 'jumlah' => 2000000],
            ['nama' => 'Pan Meri', 'tanggal' => '2024-09-23', 'jumlah' => 8000000],
            ['nama' => 'Pan Meri', 'tanggal' => '2024-10-09', 'jumlah' => 1000000],
            ['nama' => 'Pan Meri', 'tanggal' => '2024-10-17', 'jumlah' => 7000000],

            ['nama' => 'Pan putu sopi', 'tanggal' => '2024-10-10', 'jumlah' => 9247500],

            ['nama' => 'Me made malen', 'tanggal' => '2024-11-07', 'jumlah' => 15000000],

            ['nama' => 'Nyoman sudana', 'tanggal' => '2024-10-25', 'jumlah' => 10000000],

            ['nama' => 'Pan karlina', 'tanggal' => '2024-08-09', 'jumlah' => 2000000],

            ['nama' => 'Pan doni', 'tanggal' => '2024-10-17', 'jumlah' => 2000000],

            ['nama' => 'Men tania', 'tanggal' => '2024-11-08', 'jumlah' => 3000000],

            ['nama' => 'Ketut mencret', 'tanggal' => '2024-11-03', 'jumlah' => 15000000],

            ['nama' => 'Ajik yuna', 'tanggal' => '2024-11-08', 'jumlah' => 10000000],

            ['nama' => 'Pan yena', 'tanggal' => '2024-07-17', 'jumlah' => 84100000],
            ['nama' => 'Pan yena', 'tanggal' => '2024-11-12', 'jumlah' => 5000000],

            ['nama' => 'Pan nadia', 'tanggal' => '2024-11-08', 'jumlah' => 15000000],

            ['nama' => 'Yogi', 'tanggal' => '2024-11-08', 'jumlah' => 25000000],

            ['nama' => 'Pan angga', 'tanggal' => '2024-09-12', 'jumlah' => 15000000],

            ['nama' => 'Pan pian', 'tanggal' => '2024-10-24', 'jumlah' => 17000000],

            ['nama' => 'Ajik radit', 'tanggal' => '2024-08-19', 'jumlah' => 7000000],
            ['nama' => 'Ajik radit', 'tanggal' => '2024-11-03', 'jumlah' => 15000000],

            ['nama' => 'Pan wulan', 'tanggal' => '2024-10-07', 'jumlah' => 15000000],

            ['nama' => 'Kakek dimas', 'tanggal' => '2024-11-08', 'jumlah' => 4000000],


            ['nama' => 'Pan dika cb', 'tanggal' => '2024-10-03', 'jumlah' => 8000000],

            ['nama' => 'Pan desli', 'tanggal' => '2024-11-17', 'jumlah' => 30000000],

            ['nama' => 'Pan nia', 'tanggal' => '2024-11-07', 'jumlah' => 2508260],

            ['nama' => 'Pan gede antar dwipa', 'tanggal' => '2024-07-14', 'jumlah' => 15000000],
            ['nama' => 'Pan gede antar dwipa', 'tanggal' => '2024-09-26', 'jumlah' => 5000000],
            ['nama' => 'Pan gede antar dwipa', 'tanggal' => '2024-10-11', 'jumlah' => 2000000],

            ['nama' => 'Pan Arya', 'tanggal' => '2024-10-08', 'jumlah' => 25000000],

            ['nama' => 'Pan Adi', 'tanggal' => '2024-10-14', 'jumlah' => 5000000],

            ['nama' => 'Pan dika SB', 'tanggal' => '2024-10-09', 'jumlah' => 30000000],
            ['nama' => 'Pan dika SB', 'tanggal' => '2024-10-27', 'jumlah' => 15000000],

            ['nama' => 'Pan cika', 'tanggal' => '2024-11-18', 'jumlah' => 3000000],
            ['nama' => 'Pan ayu', 'tanggal' => '2024-11-18', 'jumlah' => 9985000],
            ['nama' => 'Sentiong', 'tanggal' => '2024-11-18', 'jumlah' => 1652000],
            ['nama' => 'Pan gede jemur', 'tanggal' => '2024-11-18', 'jumlah' => 1000000],
            ['nama' => 'Men agung', 'tanggal' => '2024-11-18', 'jumlah' => 3500000],
            ['nama' => 'Kadek dedi', 'tanggal' => '2024-11-18', 'jumlah' => 540000],
            ['nama' => 'Komang Sura giri', 'tanggal' => '2024-11-18', 'jumlah' => 8000000],


            ['nama' => 'pan parel', 'tanggal' => '2024-11-19', 'jumlah' => 3000000],
            ['nama' => 'dadong murni', 'tanggal' => '2024-11-19', 'jumlah' => 3000000],
            ['nama' => 'pan sri', 'tanggal' => '2024-11-19', 'jumlah' => 1000000],
            ['nama' => 'ajin eka', 'tanggal' => '2024-11-19', 'jumlah' => 3000000],
            ['nama' => 'pan pian', 'tanggal' => '2024-11-19', 'jumlah' => 5000000],
            ['nama' => 'pan krisna', 'tanggal' => '2024-11-19', 'jumlah' => 6000000],
            ['nama' => 'pan gede sandut', 'tanggal' => '2024-11-19', 'jumlah' => 3000000]
        ];


        // Membuat record untuk setiap data hutang
        foreach ($data as $hutang) {
            $petani = Petani::firstOrCreate(['nama' => $hutang['nama']]);

            Kredit::create([
                'petani_id' => $petani->id,
                'tanggal' => $hutang['tanggal'],
                'jumlah' => $hutang['jumlah'],
                'keterangan' => 'First Data',
                'status' => 0,
            ]);
        }
    }
}
