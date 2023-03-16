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
            ['delete'],
            ['send_message_in_pm_from_bot'],
            ['kick'],
            ['ban'],
            ['mute']
        ];

        foreach ($actions as $action)
        {
            ActionsDictionary::create([
                'type'=>$action[0],
            ]);
        }
    }
}
