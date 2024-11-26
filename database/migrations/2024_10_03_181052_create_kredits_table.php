<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKreditsTable extends Migration
{
    public function up()
    {
        Schema::create('kredits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained();
            $table->foreignId('pKredit_id')->nullable()->constrained('pembayaran_kredits');
            $table->date('tanggal');
            $table->text('keterangan');
            $table->decimal('jumlah', 15, 2);
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kredits');
    }
}