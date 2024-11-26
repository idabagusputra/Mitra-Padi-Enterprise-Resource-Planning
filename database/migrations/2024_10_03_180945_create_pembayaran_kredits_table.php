<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranKreditsTable extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_kredits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('giling_id')->constrained();
            $table->decimal('total_hutang', 15, 2);
            $table->decimal('dana_terbayar', 15, 2);
            $table->decimal('bunga', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_kredits');
    }
}