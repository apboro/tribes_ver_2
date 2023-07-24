<?php

namespace Database\Seeders;

use App\Models\LMSFeedback;
use Illuminate\Database\Seeder;

class LMSFeedbackSeeder extends Seeder
{
    public function run(): void
    {
        LMSFeedback::factory()->count(50)->create();
    }
}
