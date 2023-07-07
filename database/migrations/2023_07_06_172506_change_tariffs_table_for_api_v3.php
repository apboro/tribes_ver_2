<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->boolean('test_period_is_active')->default(false);
            $table->boolean('tariff_is_payable')->default(false);
            $table->boolean('thanks_message_is_active')->default(false);
            $table->renameColumn('thanks_description', 'thanks_message');
            $table->dropColumn('main_image_id');
            $table->string('main_image')->nullable();
            $table->dropColumn('thanks_image_id');
            $table->string('thanks_image')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->dropColumn('test_period_is_active');
            $table->dropColumn('tariff_is_payable');
            $table->dropColumn('thanks_message_is_active');
            $table->renameColumn('thanks_message', 'thanks_description');
            $table->dropColumn(['main_image', 'thanks_image']);
            $table->unsignedBigInteger('main_image_id')->nullable();
            $table->unsignedBigInteger('thanks_image_id')->nullable();
        });

    }
};
