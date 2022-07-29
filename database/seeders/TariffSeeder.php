<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    public function run()
    {
        /* @var User $userTest */
        $userTest = $userTest ?? User::where('email' , 'test-dev@webstyle.top')->first()
            ?? User::factory()->has(TelegramUser::factory(),'telegramMeta')->create([
                'name' => 'Test Testov',
                'email' => 'test-dev@webstyle.top',
            ]);
        /* @var Community $community */
        $community = $community ?? Community::where('owner' , $userTest->id)->first();
        if(empty($community)) {
            throw new Exception('Не создано сообщество для пользователя $userTest');
        }


        foreach (Community::all() as $community) {
            $tariff = env('USE_TRIAL_PERIOD', true) ?  $this->tariffCreate(20, $community)
                : $this->tariffCreate(0, $community);

            TariffVariant::factory()->active()->count(3)
                ->sequence(fn ($sequence) => [
                    'price' => ($sequence->index + 1) * 100,
                    'title' => 'Вариант для тарифа №'.$sequence->index,
                ])
                ->create([
                    'tariff_id' => $tariff->id,
                ]);
        }

    }

    private function tariffCreate($days, $community)
    {
        return Tariff::factory()->tariffNotification()->TestPeriod($days)->create([
            'community_id' => $community->id,
        ]);
    }
}
