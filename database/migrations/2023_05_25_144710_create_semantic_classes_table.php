<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemanticClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semantic_classes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('class_id');
            $table->string('class_name')->nullable()->comment('название класса');
            $table->string('class_probability')->nullable()->comment('вероятность с которой текст принадлежит этому классу');
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
        Schema::dropIfExists('semantic_classes');
    }
}
