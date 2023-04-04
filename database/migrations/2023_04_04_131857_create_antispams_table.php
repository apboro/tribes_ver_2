<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntispamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antispams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner');
            $table->string('name', 120)->nullable();
            $table->boolean('del_message_with_link')->default(false);
            $table->boolean('ban_user_contain_link')->default(false);
            $table->boolean('del_message_with_forward')->default(false);
            $table->boolean('ban_user_contain_forward')->default(false);
            $table->integer('work_period')->nullable();
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
        Schema::dropIfExists('antispams');
    }
}
