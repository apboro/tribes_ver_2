<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('OrderId')->nullable();
            $table->unsignedBigInteger('community_id')->nullable()->comment('Привязка к сообществу');
            $table->unsignedInteger('add_balance')->comment('Прибавлено денег');
            $table->string('from')->nullable()->comment('ФИО от кого платеж');
            $table->text('comment')->nullable()->comment('Комментарий плательщика');
            $table->boolean('isNotify')->default(false)->comment('Уведомление о донате');
            $table->unsignedBigInteger('telegram_user_id')->nullable()->comment('Привязка к Плательщику');
            $table->bigInteger('paymentId')->nullable()->comment('ID оплаты');
            $table->bigInteger('amount')->nullable()->comment('Сумма в копейках');
            $table->char('paymentUrl')->nullable()->comment('Ссылка для оплаты');
            $table->text('response')->nullable()->comment('Ответ банка');
            $table->string('status')->nullable()->comment('Статус');
            $table->text('token')->nullable()->comment('Токен');
            $table->char('error')->nullable()->comment('Ошибка');
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
        Schema::dropIfExists('payments');
    }
}
