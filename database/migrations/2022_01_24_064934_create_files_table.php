<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mime')->nullable()->comment('МИМ тип');
            $table->string('size')->nullable()->comment('Размер');
            $table->string('filename')->nullable()->comment('Имя файла с расширением');
            $table->string('description')->nullable()->comment('Описание');
            $table->integer('rank')->default(0)->nullable()->comment('Позиция');
            $table->boolean('isImage')->default(false)->comment('Это изображение?');
            $table->string('url')->comment('URL строка');
            $table->string('hash')->nullable();
            $table->integer('uploader_id')->unsigned()->comment('Кем загружено');
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
        Schema::dropIfExists('files');
    }
}
