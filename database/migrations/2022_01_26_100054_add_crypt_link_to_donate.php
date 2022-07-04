<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCryptLinkToDonate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donates', function (Blueprint $table) {
            $table->string('inline_link')->unique()->nullable()->after('description');
        });

        foreach(\App\Models\Donate::all() as $donate){
            $donate->generateLink();
            $donate->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donates', function (Blueprint $table) {
            $table->dropColumn('inline_link');
        });
    }
}
