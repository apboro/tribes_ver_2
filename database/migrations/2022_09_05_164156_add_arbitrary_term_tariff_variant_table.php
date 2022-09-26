<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArbitraryTermTariffVariantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarif_variants', function (Blueprint $table) {
            $table->boolean('arbitrary_term')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarif_variants', function (Blueprint $table) {
            $table->dropColumn('arbitrary_term');
        });
    }
}
