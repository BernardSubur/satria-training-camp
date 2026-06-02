<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create('transaksi', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->unsignedBigInteger('paket_id');

            $table->string('order_id')->unique();

            $table->integer('harga');

            $table->string('status')->default('pending');

            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');

            $table->foreign('paket_id')->references('id')->on('paket')->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
