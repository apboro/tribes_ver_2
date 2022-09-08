<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramDictReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_dict_reactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('flag_value')->nullable();
            $table->timestamps();
        });

        $reactions = [
            ['code'=>'\ud83d\udc4d', 'name'=>'Thumbs Up',                  'flag_value'=>'1'],
            ['code'=>'\ud83d\udc4e', 'name'=>'Thumbs Down',                'flag_value'=>'0'],
            ['code'=>'\u2764',       'name'=>'Read Heart',                 'flag_value'=>'1'],
            ['code'=>'\ud83d\udd25', 'name'=>'Fire',                       'flag_value'=>'1'],
            ['code'=>'\ud83d\udc4d', 'name'=>'Smiling Face with Hearts',   'flag_value'=>'1'],
            ['code'=>'\ud83d\udc4f', 'name'=>'Clapping Hands',             'flag_value'=>'1'],
            ['code'=>'\ud83d\ude01', 'name'=>'Beaming Face',               'flag_value'=>'1'],
            ['code'=>'\ud83e\udd14', 'name'=>'Thinking Face',              'flag_value'=>'0'],
            ['code'=>'\ud83e\udd2f', 'name'=>'Exploding Head',             'flag_value'=>'0'],
            ['code'=>'\ud83d\ude31', 'name'=>'Screaming Face',             'flag_value'=>'0'],
            ['code'=>'\ud83e\udd2c', 'name'=>'Face with Symbols on Mouth', 'flag_value'=>'0'],
            ['code'=>'\ud83d\ude22', 'name'=>'Crying Face',                'flag_value'=>'0'],
            ['code'=>'\ud83c\udf89', 'name'=>'Party Popper',               'flag_value'=>'1'],
            ['code'=>'\ud83e\udd29', 'name'=>'Star-Struck',                'flag_value'=>'1'],
            ['code'=>'\ud83e\udd2e', 'name'=>'Face Vomiting',              'flag_value'=>'0'],
            ['code'=>'\ud83d\udca9', 'name'=>'Pile of Poo',                'flag_value'=>'0'],
            ['code'=>'\ud83d\ude4f', 'name'=>'Folded Hands',               'flag_value'=>'1'],
        ];

        foreach ($reactions as $reaction){
            $react = new \App\Models\TelegramDictReaction;
            $react->code = $reaction['code'];
            $react->name = $reaction['name'];
            $react->flag_value = $reaction['flag_value'];
            $react->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_dict_reactions');
    }
}
