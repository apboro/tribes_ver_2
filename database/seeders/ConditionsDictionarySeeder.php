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
            ['message_text', 'message_text-contain', 'message_text-contain-full_congruence'],
            ['message_text', 'message_text-contain', 'message_text-contain-part_congruence'],
            ['message_text', 'message_text-length', 'message_text-length-more_than'],
            ['message_text', 'message_text-length', 'message_text-length-less_than'],
            ['message_text', 'message_text-length', 'message_text-length-equivalent'],
            ['message_type', 'message_type-contain_url'],
            ['message_type', 'message_type-is_url'],
            ['first_name', 'rtl_symbols'],
            ['first_name', 'first_name_length', 'first_name_length_max_length'],
            ['first_name', 'first_name_length', 'first_name_length_min_length'],
            ['first_name', 'first_name_length', 'first_name_length_equivalent'],
            ['username', 'username_username_length', 'username_length_max_length'],
            ['username', 'username_username_length', 'username_length_min_length'],
            ['username', 'username_username_length', 'username_length_equivalent'],
            ['reputation', 'reputation_quantity', 'reputation_quantity_max_quantity'],
            ['reputation', 'reputation_quantity', 'reputation_quantity_min_quantity'],
            ['reputation', 'reputation_quantity', 'reputation_quantity_equivalent'],
        ];

        foreach ($type as $record) {
            ConditionsDictionary::create([
                'entity' => $record[0],
                'to_check' => $record[1] ?? null,
                'detail' => $record[2] ?? null,
            ]);
        }
    }
}
