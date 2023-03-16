<?php

namespace Database\Seeders;

use App\Models\ConditionsDictionary;
use Illuminate\Database\Seeder;

class ConditionsDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = [
                  ['message','message_contain','full_congruence'],
                  ['message','message_contain','part_congruence'],
                  ['message','message_length','more_than'],
                  ['message','message_length','less_than'],
                  ['message','message_length','equivalent'],
                  ['username', 'rtl_symbols'],
                  ['username', 'too_long_first_name'],
                  ['username', 'too_long_second_name']
                ];

        foreach ($type as $record)
        {
            ConditionsDictionary::create([
                'type1'=>$record[0],
                'type2'=>$record[1] ?? null,
                'type3'=>$record[2] ?? null,
            ]);
        }


    }
}
