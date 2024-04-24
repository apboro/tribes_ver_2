<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserLegalInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_legal_info', function (Blueprint $table) {
            $table->text('address')->default('');
            $table->bigInteger('ogrn')->default(0);
            $table->text('additionally')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_legal_info', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('ogrn');
            $table->dropColumn('additionally');
        });
    }
}