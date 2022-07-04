<?php

namespace Database\Seeders;

use App\Models\Donate;
use App\Models\DonateVariant;
use Illuminate\Database\Seeder;

class DonateVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Donate::all() as $key => $donate){
            for ($i = 1; $i <= rand(1,4); $i++) {
                if ($i != 4) {
                    DonateVariant::factory()
                        ->create([
                            'donate_id' => $donate->id,
                            'price' => rand(100, 1000)
                        ]);
                } else {
                    DonateVariant::factory()
                        ->create([
                            'donate_id' => $donate->id,
                            'min_price' => 500,
                            'max_price' => 1000,
                        ]);
                }
            }
        }
    }
}
