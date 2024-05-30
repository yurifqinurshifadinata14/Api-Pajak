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
        Schema::create('pajaks', function (Blueprint $table) {
            $table->id();
            $table->string('id_pajak');
            $table->string('id_user')->nullable();
            $table->string('nama_wp');
            $table->integer('npwp');
            $table->string('no_hp');
            $table->string('no_efin');
            $table->string('gmail');
            $table->string('password');
            $table->integer('nik');
            $table->string('alamat');
            $table->string('merk_dagang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pajaks');
    }
};
