<?php

use App\Models\TariffVariant;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTarifVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_tarif_variants', function (Blueprint $table) {
            $table->foreignIdFor(TariffVariant::class, 'tarif_variants_id');
            $table->foreignIdFor(User::class, 'user_id');

            $table->integer('days')->nullable();
            $table->string('prompt_time')->default('00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_tarif_variants');
    }
}
