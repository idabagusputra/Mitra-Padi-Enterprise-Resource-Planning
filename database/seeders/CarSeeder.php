<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use Carbon\Carbon;

class CarSeeder extends Seeder
{
    public function run()
    {
        $cars = [
            [
                'nama_mobil' => 'Toyota Avanza',
                'tanggal_servis' => Carbon::now()->subMonths(2),
                'kilometer' => 45000,
                'status' => 'belum_servis'
            ],
            [
                'nama_mobil' => 'Honda Civic',
                'tanggal_servis' => Carbon::now()->subMonth(),
                'kilometer' => 32000,
                'status' => 'sudah_servis'
            ],
            [
                'nama_mobil' => 'Suzuki Ertiga',
                'tanggal_servis' => Carbon::now()->subMonths(3),
                'kilometer' => 67000,
                'status' => 'belum_servis'
            ],
            [
                'nama_mobil' => 'Mitsubishi Xpander',
                'tanggal_servis' => Carbon::now()->subWeeks(2),
                'kilometer' => 28000,
                'status' => 'sudah_servis'
            ],
            [
                'nama_mobil' => 'Daihatsu Terios',
                'tanggal_servis' => Carbon::now()->subMonths(4),
                'kilometer' => 89000,
                'status' => 'belum_servis'
            ],
            [
                'nama_mobil' => 'Toyota Rush',
                'tanggal_servis' => Carbon::now()->subDays(10),
                'kilometer' => 23000,
                'status' => 'sudah_servis'
            ]
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
