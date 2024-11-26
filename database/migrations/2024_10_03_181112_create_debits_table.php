<?php

// database/migrations/2024_10_03_180948_create_debits_table.php

// database/migrations/2024_10_03_180948_create_debits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebitsTable extends Migration
{
    public function up()
    {
        Schema::create('debits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id');  // Hanya kredit_id, hapus petani_id
            $table->decimal('jumlah', 15, 2); // Tipe data decimal untuk jumlah
            $table->float('bunga');
            $table->text('keterangan'); // Tipe data decimal untuk jumlah
            $table->date('tanggal');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('debits');
    }
}
