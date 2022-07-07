<?php

namespace Database\Seeders;

use App\Models\Knowledge\Question;
use App\Models\TelegramUser;
use App\Models\User;
use App\Models\Community;
use App\Models\Payment;
use App\Models\Statistic;
use App\Models\TelegramConnection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $user = User::factory()->createItem([
                'name' => 'Pyatak',
                'email' => 'pyatak@gmail.com',
                'phone' => 9155707971,
            ]);

        $userTest = User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top',
        ]);

        //Auth::login($user);


        $this->call([
            CommunitySeeder::class,
            KnowledgeSeeder::class,
            StatisticSeeder::class,
            TariffSeeder::class,
            DonateSeeder::class,
            DonateVariantSeeder::class,
            PaymentSeeder::class,
            TemplateSeeder::class,
            FileSeeder::class,
            CourseSeeder::class,
        ],
        [
            'user' => $user,
            'userTest' => $userTest,
        ]);

    }

    protected function statistic($userId)
    {
        /*$statistic = Statistic::create([
            'community_id' => $community->id,
        ]);

        $payment = Payment::create([
            'OrderId' => '1234',
            'community_id' => $community->id,
            'add_balance' => 700,
            'telegram_user_id' => $connection->telegram_user_id,
            'status' => 'CONFIRMED',
            'type' => 'tariff'
        ]);

        $payment = Payment::create([
            'OrderId' => '12345',
            'community_id' => $community->id,
            'add_balance' => 500,
            'telegram_user_id' => $connection->telegram_user_id,
            'status' => 'CONFIRMED',
            'type' => 'tariff'
        ]);

        $payment = Payment::create([
            'OrderId' => '12346',
            'community_id' => $community->id,
            'add_balance' => 600,
            'telegram_user_id' => $connection->telegram_user_id,
            'status' => 'CONFIRMED',
            'type' => 'tariff'
        ]);

        $payment = Payment::create([
            'OrderId' => '123467',
            'community_id' => $community->id,
            'add_balance' => 6000,
            'telegram_user_id' => $connection->telegram_user_id,
            'status' => 'CONFIRMED',
            'type' => 'donate-1'
        ]);

        $payment = Payment::create([
            'OrderId' => '123468',
            'community_id' => $community->id,
            'add_balance' => 200,
            'telegram_user_id' => $connection->telegram_user_id,
            'status' => 'CONFIRMED',
            'type' => 'donate-2'
        ]);*/
    }
}
