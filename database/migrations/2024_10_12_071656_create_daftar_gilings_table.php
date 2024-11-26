<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('daftar_gilings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('giling_id')->constrained();
            $table->float('giling_kotor');
            $table->float('biaya_giling');
            $table->float('ongkos_giling');
            $table->float('beras_bersih');
            $table->float('beras_jual');
            $table->float('total_hutang');
            $table->float('total_pengambilan');
            $table->float('pinjam');
            $table->float('pulang');
            $table->float('harga_jual');
            $table->float('dana_jual_beras');
            $table->float('dana_penerima');
            $table->float('biaya_buruh_giling');
            $table->float('total_biaya_buruh_giling');
            $table->float('jemur');
            $table->float('biaya_buruh_jemur');
            $table->float('total_biaya_buruh_jemur');
            $table->float('jumlah_konga');
            $table->float('harga_konga');
            $table->float('dana_jual_konga');
            $table->float('jumlah_menir');
            $table->float('harga_menir');
            $table->float('dana_jual_menir');
            $table->float('bunga');
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_gilings');
    }
};
