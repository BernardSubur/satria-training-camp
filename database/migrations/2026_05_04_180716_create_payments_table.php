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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->foreignId('paket_id')->constrained('paket')->onDelete('cascade');
            $table->string('metode_pembayaran')->nullable(); // qris, transfer
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['pending', 'success', 'rejected'])->default('pending');
            $table->string('role_target')->nullable(); // member, member_private
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
