<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSemanticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('semantic_table', function (Blueprint $table){
            $table->dropColumn(['class_name','class_probability']);
            $table->unsignedBigInteger('class_id')->nullable();
        });
    }

}
