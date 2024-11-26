<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengambilansTable extends Migration
{
    public function up()
    {
        Schema::create('pengambilans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('giling_id')->constrained()->onDelete('cascade');
            $table->string('keterangan')->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('harga')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengambilans');
    }
}
