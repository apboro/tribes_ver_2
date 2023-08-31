<?php

use App\Models\Webinar;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebinarUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_user', function (Blueprint $table) {
            $table->foreignIdFor(Webinar::class, 'webinar_id')->constrained('webinars');
            $table->foreignIdFor(User::class, 'user_id')->constrained('users');
            $table->integer('cost')->default(0);
            $table->dateTime('byed_at');
            $table->dateTime('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webinar_user');
    }
}
