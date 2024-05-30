<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jenis', function (Blueprint $table) {
            $table->id();
            $table->string('id_pajak');
            $table->enum('jenis', ['Badan', 'Pribadi']);
            $table->string('jabatan') ->nullable();
            $table->string('alamatBadan') ->nullable();
            $table->integer('npwpBadan') ->nullable();
            $table->string('saham') ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis');
    }
};
