<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kredit_pembayaran_kredit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kredit_id')->constrained();
            $table->foreignId('pembayaran_kredit_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kredit_pembayaran_kredits');
    }
};
