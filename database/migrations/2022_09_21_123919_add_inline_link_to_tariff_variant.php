<?php

use App\Models\TariffVariant;
use App\Repositories\Tariff\TariffRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInlineLinkToTariffVariant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarif_variants', function (Blueprint $table) {
            $table->string('inline_link',8)->default(0);
        });
        /** @var TariffRepository $rep */
        $rep = app()->make(TariffRepository::class);
        foreach (TariffVariant::all() as $tariffVariant){
            $rep->generateLink($tariffVariant);
            $tariffVariant->save();
        }
        Schema::table('tarif_variants', function (Blueprint $table) {
            $table->unique('inline_link');
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
            $table->dropColumn('inline_link');
        });
    }
}
