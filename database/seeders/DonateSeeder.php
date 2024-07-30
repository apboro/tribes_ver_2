<?php

namespace Database\Seeders;

use App\Models\Donate;
use App\Models\Community;
use Illuminate\Database\Seeder;

class DonateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Community::all() as $community){
            Donate::factory()
                ->count(5)
                ->create();
        }
    }
}
