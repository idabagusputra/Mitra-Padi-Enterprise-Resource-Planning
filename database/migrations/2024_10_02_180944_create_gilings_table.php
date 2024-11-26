<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGilingsTable extends Migration
{
    public function up()
    {
        Schema::create('gilings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained();
            $table->float('giling_kotor');
            $table->float('biaya_giling');
            $table->float('pulang');
            $table->float('pinjam');
            $table->float('biaya_buruh_giling');
            $table->float('biaya_buruh_jemur');
            $table->float('jemur');
            $table->float('jumlah_konga');
            $table->float('harga_konga');
            $table->float('jumlah_menir');
            $table->float('harga_menir');
            $table->float('harga_jual');
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('gilings');
    }
}
