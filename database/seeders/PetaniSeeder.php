<?php

namespace Database\Seeders;

use App\Models\Petani;
use Illuminate\Database\Seeder;

class PetaniSeeder extends Seeder
{
    public function run()
    {
        $namaPetaniWanaprasta = [
            'Meyu Sugi',
            'Metu Wid',
            'Pan Lisa',
            'Pan Krisna',
            'Pan Dut',
            'Men Joni',
            'Pan Rika',
            'Pan Vidia',
            'Jika Min',
            'Dewa Aji Wnp',
            'Pan Ayu Sugendro',
            'Ajin Supra',
            'Eka Nova',
            'Gus Supra',
            'Pan Gede Sandut',
        ];

        $namaPetaniPalesari = [
            "Meman Indah",
            "Men Epan",
            "Men Yadnya",
            "Bli Komang Ano",
            "Jikman Babahan",
            "Ibun Wid",
            "Men Vera",
            "Pan Gede Adi Pls",
            "Pan Gede Ari",
            "Pan Komang Adi",
            "Ajin Intan",
            "Pak Wo",
            "Pan Agus Sandipa",
            "Nini",
            "Ajin Widana",
            "Pan Erik",
            "Men Suri",
            "Gusde Giri",
            "Gus Sayang",
            "Jiktut Bali",
            "Men Repa",
            "Pan Celsi"
        ];

        $namaPetaniPenebel = [
            "Pan Cantik",
            "Pan Parel",
            "Pan Danar",
            "Kakek Ari",
            "Dadong Murni",
            "Pan Artana",
            "Men Erna",
            "Pan Dewi Polisi",
            "Pan Rio",
            "Men Ferdi",
            "Biang Gita",
            "Pan Febri",
            "Wayan Febri",
            "Men Tasya",
            "Pan Dian PB",
            "Men Dedi",
            "Men Jimi",
            "Pan Devi",
            "Pan Ica",
            "Men Siska",
            "Men Andi",
            "Pan Cita"
        ];

        $namaPetaniSausu = [
            "Pak Tambir",
            "Blebed",
            "Pande",
            "Sukrening",
            "Pan Gede Anyud",
            "Pan Iluh",
            "Ketut Nadi",
            "Pak Ketut Yase",
            "Pak Dana",
            "Komang Mario",
            "Gede Bandeso",
            "Pak Kenis",
            "Ranti",
            "Pan Manis",
            "Pan Anis",
            "Pan Melda",
            "Datuk",
            "Made Suartana",
            "Wayan Sutapa",
            "Pak Bur",
            "Pak Kardu",
            "Dewa Subrata",
            "Kadek Wijana",
            "Pak Peri",
            "Pak Oka",
            "Pak Sadre",
            "Pak Lemek",
            "Budi",
            "Pak Simin",
            "Pak Carik",
            "Bu Gelgel",
            "Yandi",
            "Pak Gelgel Baru",
            "Sukrening"
        ];

        $namaPetaniSibang = [
            "Iluh Wid",
            "Men Suki",
            "Pan Sudi",
            "Pan Satya",
            "Pan Dika SB"
        ];

        $namaPetaniSukasada = [
            "Pan Gede Adi Sksd",
            "Pan Pindi",
            "Pan Gede Antara"
        ];

        $namaPetaniPurwosari = [
            "Mas Rais"
        ];

        $namaPetaniBaliIndah = [
            "Ajin Sayu Kembar",
            "Pan Gibran",
            "Ajik Eri",
            "Pan Geral",
            "Pan Deva",
            "Ajik Yuna",
            "Ajik Prima",
            "Ajik Wira",
            "Pan Widana",
            "Pan Yudani",
            "Ajik Lingga",
            "Pan Kevin",
            "Pan Devin",
            "Pak Wahyu",
            "Pan Rio",
            "Biang Yogi",
            "Pak Brian",
            "Pak Selvi",
            "Pak Rehan",
            "Ajik Mita",
            "Men Parel",
            "Pan Dian BI"
        ];

        $namaSangehSari = [
            "Pan Sri",
            "Pan Dedi",
            "Men Bagas",
            "Pan Meri",
            "Pan Putu Sopi",
            "Me Made Malen",
            "Men Kayan",
            "Nyoman Sudana",
            "Pan Karlina",
            "Poyan Somo",
            "Pan Doni",
            "Men Agus Sangeh",
            "Men Adi",
            "Men Tania",
            "Tiaji Bali",
            "Pan Manda",
            "Liong",
            "Ketut Mencret",
            "Ajin Eka"
        ];

        $namaDoresPion = [
            "Dores Pion"
        ];

        $namaGigitSari = [
            "Ajik Yuna",
            "Pan Yena",
            "Pan Yulia",
            "Pan Nadia",
            "Pan Dewi",
            "Pan Sinta",
            "Pan Dandi",
            "Pan Sur",
            "Yogi",
            "Pan Angga",
            "Agus",
            "Pan Merlin",
            "Pan Pian",
            "Pan Yus",
            "Pan Serli",
            "Men Nita",
            "Ajik Radit",
            "Ajik Arta",
            "Pan Tiara",
            "Pan Alan",
            "Ajik Sunar",
            "Pan Wulan",
            "Kakek Dimas"
        ];


        $namaKaryawan = [
            "Pan Cika",
            "Pan Ayu",
            "Sentiong",
            "Pan Gede Jemur",
            "Men Agung",
            "Kayun Dinto",
            "Kadek Dedi",
            "Komang Sura Giri",
            "Sukri",
            "Buruh Nembak"
        ];


        $namaCandrabuana = [
            "Pan Putu Bokir",
            "Pan Dika CB",
            "Pan Desli",
            "Pan Agus Reno",
            "Pan Nia",
            "Pan Arya",
            "Pan Adi",
            "Pan Gede Antar Dwipa"
        ];

        foreach ($namaKaryawan as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Karyawan',
                'no_telepon' => '080000000000',
            ]);
        }

        foreach ($namaCandrabuana as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Candra Buana',
                'no_telepon' => '080000000000',
            ]);
        }


        foreach ($namaGigitSari as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Gigit Sari',
                'no_telepon' => '080000000000',
            ]);
        }

        foreach ($namaSangehSari as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Sangeh Sari',
                'no_telepon' => '080000000000',
            ]);
        }

        foreach ($namaDoresPion as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Taman Sari',
                'no_telepon' => '080000000000',
            ]);
        }



        foreach ($namaPetaniWanaprasta as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Wanaprasta',
                'no_telepon' => '080000000000',
            ]);
        }


        foreach ($namaPetaniPalesari as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Palesari',
                'no_telepon' => '080000000000',
            ]);
        }

        foreach ($namaPetaniPenebel as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Penebel',
                'no_telepon' => '080000000000',
            ]);
        }

        foreach ($namaPetaniSausu  as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Sausu',
                'no_telepon' => '080000000000',
            ]);
        }

        foreach ($namaPetaniSibang   as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Sibang',
                'no_telepon' => '080000000000',
            ]);
        }

        foreach ($namaPetaniSukasada  as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Sukasada',
                'no_telepon' => '080000000000',
            ]);
        }

        foreach ($namaPetaniPurwosari  as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Purwo Sari',
                'no_telepon' => '080000000000',
            ]);
        }

        foreach ($namaPetaniBaliIndah   as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Bali Indah',
                'no_telepon' => '080000000000',
            ]);
        }
    }
}
