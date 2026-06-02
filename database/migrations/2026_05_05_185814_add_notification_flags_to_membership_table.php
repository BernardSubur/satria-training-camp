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
        Schema::table('membership', function (Blueprint $table) {
            $table->boolean('notif_sesi_habis')->default(false)->after('status');
            $table->boolean('notif_expired')->default(false)->after('notif_sesi_habis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership', function (Blueprint $table) {
            $table->dropColumn(['notif_sesi_habis', 'notif_expired']);
        });
    }
};
