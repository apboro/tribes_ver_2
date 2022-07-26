<?php

use App\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MediaStatisticTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('m_product', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('type',50)->default('course')
                ->comment('тип медиа товара');
            $table->integer('c_uniq_buyers')->default(0)
                ->comment('количество уникальных покупателей');
            $table->integer('c_time_view')->default(0)
                ->comment('общее время просмотра медиа товара');

        });

        Schema::table('m_product', function (Blueprint $table) {
            $table->foreign('uuid')->references('uuid')->on('courses');
        });

        Schema::create('m_product_sale', function (Blueprint $table) {
            $table->bigInteger('payment_id')->primary()->comment('один к одному с платежами в статусе complete');
            $table->foreign('payment_id')->on('payments')->references('id');

            $table->uuid('uuid');
            $table->foreign('uuid')->references('uuid')->on('courses');

            $table->foreignId('user_id')->comment('покупатель')->constrained('users','id');

            $table->integer('price')->default(0)
                ->comment('стоимость медиатора на время продажи в копейках');
        });

        Schema::create('m_product_user_views', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->foreign('uuid')->references('uuid')->on('courses');

            $table->foreignId('user_id')->comment('покупатель')->constrained('users','id');

            $table->integer('c_time_view')->default(0)
                ->comment('счетчиков просмотра покупателем медиа в секундах');

            $table->primary(['uuid','user_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('main')->dropIfExists('m_product_user_views');
        Schema::connection('main')->dropIfExists('m_product_sale');
        Schema::connection('main')->dropIfExists('m_product');

    }
}
