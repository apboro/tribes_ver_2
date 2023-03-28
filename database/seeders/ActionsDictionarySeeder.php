<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\ActionsDictionary;
use Illuminate\Database\Seeder;

class ActionsDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = [
            ['send_message_in_chat_from_bot'],
            ['delete_message'],
            ['send_message_in_pm_from_bot'],
            ['kick_user'],
            ['ban_user'],
            ['mute_user']
        ];

        foreach ($actions as $action)
        {
            ActionsDictionary::create([
                'type'=>$action[0],
            ]);
        }
    }
}
