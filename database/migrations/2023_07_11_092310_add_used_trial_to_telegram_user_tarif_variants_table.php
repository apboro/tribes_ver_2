<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('telegram_users_tarif_variants', function (Blueprint $table) {
            $table->boolean('used_trial')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('telegram_users_tarif_variants', function (Blueprint $table) {
            $table->dropColumn('used_trial');
        });
    }
};
