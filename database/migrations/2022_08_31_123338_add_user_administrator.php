<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserAdministrator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->insert([
            'name' => 'mail-park.fabit.ru',
            'email' => 'suppport@tribes.fabit.ru',
            'code' => NULL,
            'phone' => NULL,
            'email_verified_at' => NULL,
            'password' => '$2y$10$8495tMFO2MYsJ0Xx1/oFbuoR8btEOsj5a7UN9DDwdEzQi9Ffxs8g6',
            'phone_confirmed' => 'f',
            'role_index' => 0,
            'hash' => '$2y$10$7EWGrOH6j3pIx.LMRn8HV.6xbHgUc/.6eymU7Adh2hL7HG.SqrGhK',
            'remember_token' => NULL,
            'created_at' => '2022-08-26 12:53:04',
            'updated_at' => '2022-08-29 22:34:16',
            'locale' => 'ru',
            'api_token' => '290|IfCgJYRRrzF93oz7BYwczSBAKemYT7wQVJvSUJtm',
        ]);
        DB::table('users')->insert([
            'name' => 'mail.yandex.ru',
            'email' => 'support@trbs.co',
            'code' => NULL,
            'phone' => NULL,
            'email_verified_at' => NULL,
            'password' => '$2y$10$va.98Hpju55W45nIrI20/eaqs9kbdjSi.FC9lOBjD95coUDcQy95W',
            'phone_confirmed' => 'f',
            'role_index' => 0,
            'hash' => '$2y$10$gdeyLvVcpmmbtXGCXDsfEuCoa7.2538KAXmnGq8xI7s050Yb/bskm',
            'remember_token' => 'iScPWwW1kgS5f2pR9H3fkHWcHWk71yRiMNwfacgiYjBMgW8HSyGTlLU0lE3c',
            'created_at' => '2022-08-26 12:54:48',
            'updated_at' => '2022-08-26 13:03:28',
            'locale' => 'ru',
            'api_token' => '260|nAYVOcXotwMJLdTNKEiCmu8IbE5AIx2VJREAFAHM',
        ]);
        DB::table('administrators')->insert([
            'user_id' => \App\Models\User::where('email', 'suppport@tribes.fabit.ru')->first()->id,
            'created_at' => '2022-08-26 12:56:11',
            'updated_at' => '2022-08-26 12:56:11',
        ]);
        DB::table('administrators')->insert([
            'user_id' => \App\Models\User::where('email', 'support@trbs.co')->first()->id,
            'created_at' => '2022-08-26 12:56:11',
            'updated_at' => '2022-08-26 12:56:11',
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $user1 = \App\Models\User::where('email', 'suppport@tribes.fabit.ru')->first()->id;
        $user2 = \App\Models\User::where('email', 'support@trbs.co')->first()->id;
        DB::table('users')->where('id', $user1)->delete();
        DB::table('users')->where('id', $user2)->delete();
        DB::table('administrators')->where('user_id', $user1)->delete();
        DB::table('administrators')->where('user_id', $user2)->delete();
    }
}
