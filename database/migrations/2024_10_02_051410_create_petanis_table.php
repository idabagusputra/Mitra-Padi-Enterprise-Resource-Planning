<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetanisTable extends Migration
{
    public function up()
    {
        Schema::create('petanis', function (Blueprint $table) {
            $table->id(); // Ini akan membuat kolom 'id' sebagai primary key
            $table->string('nama');
            $table->string('alamat');
            $table->string('no_telepon');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('petanis');
    }
}
