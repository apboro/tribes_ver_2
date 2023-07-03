<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('author_id');
            $table->uuid('uuid');
            $table->string('title', 150)->nullable();
            $table->integer('price')->nullable();
            $table->text('description')->nullable();
            $table->string('background_image')->nullable();

            $table->boolean('is_active')->default(false);

            $table->dateTime('activation_date')->nullable();
            $table->dateTime('deactivation_date')->nullable();
            $table->dateTime('publication_date')->nullable();

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
        Schema::dropIfExists('publications');
    }
}
