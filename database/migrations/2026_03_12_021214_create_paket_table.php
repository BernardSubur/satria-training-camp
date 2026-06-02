<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create('paket', function (Blueprint $table) {

            $table->id();

            $table->string('nama_paket');

            $table->string('kategori');

            $table->integer('harga');

            $table->integer('jumlah_sesi');

            $table->integer('durasi_bulan')->nullable();

            $table->timestamps();
        });
    }


    public function down(): void
    {

        Schema::dropIfExists('paket');
    }
};
