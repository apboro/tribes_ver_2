<?php

use App\Models\Community;
use App\Models\TelegramUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramUserReputationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_users_reputation', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Community::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(TelegramUser::class)->constrained('telegram_users', 'telegram_id')->cascadeOnDelete();
            $table->unsignedSmallInteger('messages_count')->nullable();
            $table->unsignedSmallInteger('reputation_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_users_reputation');
    }
}
