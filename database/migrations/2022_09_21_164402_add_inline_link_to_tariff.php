<?php

use App\Models\Tariff;
use App\Repositories\Tariff\TariffRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInlineLinkToTariff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->string('inline_link',8)->default(0);
        });
        /** @var TariffRepository $rep */
        $rep = app()->make(TariffRepository::class);
        foreach (Tariff::all() as $tariff){
            $rep->generateLink($tariff);
            $tariff->save();
        }
        Schema::table('tariffs', function (Blueprint $table) {
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
        Schema::table('tariffs', function (Blueprint $table) {
            $table->dropColumn('inline_link');
        });
    }
}
