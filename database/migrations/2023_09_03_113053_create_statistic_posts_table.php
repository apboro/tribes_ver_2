<?php

use App\Models\Webinar;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticPublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_publications', function (Blueprint $table) {
            $table->id();
            $table->date('current_date');
            $table->integer('publication_id');
            $table->integer('view')->nullable();
            $table->integer('seconds')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistic_publications');
    }
}
