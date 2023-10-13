<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebinarAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_analytics', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('room_id')->comment('ид комнаты на wbnr.su');
            $table->string('user_name')->comment('имя пользователя');
            $table->string('user_email');
            $table->bigInteger('user_outer_id')->comment('только после перехода по ссылке из вашей системы');
            $table->boolean('attend')
                ->comment('факт посещения вебинара, если просмотрено 70% и  более, то true, менее 70% - false');
            $table->string('ip')->comment('последний фиксированный ip пользователя');
            $table->string('role')->comment('роль участника user user admin');

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
        Schema::dropIfExists('webinar_analytics');
    }
}
