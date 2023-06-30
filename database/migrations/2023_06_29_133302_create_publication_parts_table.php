<?php

use App\Models\Publication;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publication_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('order');
            $table->unsignedTinyInteger('type');

            $table->string('file_path')->nullable();
            $table->text('text')->nullable();

            $table->foreignIdFor(Publication::class);
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
        Schema::dropIfExists('publication_parts');
    }
}
