<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\DonateVariant;
use App\Models\Payment;
use App\Models\Community;
use App\Models\TelegramUser;
use App\Models\TariffVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTest = $userTest ?? User::where('email' , 'test-dev@webstyle.top')->first()
            ?? User::factory()->has(TelegramUser::factory(),'telegramMeta')->create([
                'name' => 'Test Testov',
                'email' => 'test-dev@webstyle.top',
            ]);
        $teleuser = $userTest->telegramMeta ?? TelegramUser::factory()->for($userTest)->create();

        $userBuyer = User::where('id', 1)->first();

        foreach(Community::all() as $community){

            $tv = TariffVariant::whereHas('tariff', function ($query) use ($community) {
                return $query->where('community_id', $community->id);
            })->first();

            $dv = DonateVariant::whereHas('donate', function ($query) use ($community) {
                return $query->where('community_id', $community->id);
            })->first();

            $cv = Course::where('community_id', $community->id)->first();

            Payment::factory()
                ->count(3)
                ->state(new Sequence(
                    ['payable_id' => $tv->id ?? 1, 'payable_type' => 'App\Models\TariffVariant'],
                    ['payable_id' => $dv->id ?? 1, 'payable_type' => 'App\Models\DonateVariant'],
                    ['payable_id' => $cv->id ?? 1, 'payable_type' => 'App\Models\Course']
                ))
//                ->typeDonate()
//                ->typeTariffVariant($tv->id)
                ->create([
                    'from' => $teleuser->first_name,
                    'community_id' => $community->id,
                    'telegram_user_id' => $teleuser->telegram_id,
                    'user_id' => $userBuyer->id,
                    'author' => $userTest->id,
                ]);
        }


    }
}






    /*public function run()
    {
        $userTest = $userTest ?? User::where('email', 'test-dev@webstyle.top')->first()
                ?? User::factory()->has(TelegramUser::factory(), 'telegramMeta')->create([
                'name' => 'Test Testov',
                'email' => 'test-dev@webstyle.top',
            ]);
        $teleuser = $userTest->telegramMeta ?? TelegramUser::factory()->for($userTest)->create();

        foreach (Community::all() as $community) {
            $tv = TariffVariant::whereHas('tariff', function ($query) use ($community) {
                return $query->where('community_id', $community->id);
            })->first();
            Payment::factory()
                ->count(3)
                ->sequence()
                ->typeTariffVariant($tv->id)
                ->create([
                    'from' => $teleuser->first_name,
                    'community_id' => $community->id,
                    'telegram_user_id' => $teleuser->telegram_id,
                    'user_id' => $teleuser->telegram_id,
                    'author' => $teleuser->telegram_id,
                ]);
        }

    }*/


