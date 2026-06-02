<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('hari');

            $table->date('tanggal');

            $table->string('sesi');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};