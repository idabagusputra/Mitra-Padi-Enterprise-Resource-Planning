<?php

// ========================
// 1. MIGRATION FILE
// database/migrations/xxxx_xx_xx_xxxxxx_create_cars_table.php
// ========================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mobil');
            $table->date('tanggal_servis');
            $table->integer('kilometer');
            $table->enum('status', ['belum_servis', 'sudah_servis'])->default('belum_servis');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
