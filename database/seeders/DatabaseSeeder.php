<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\Knowledge\Question;
use App\Models\SmsConfirmations;
use App\Models\TelegramUser;
use App\Models\TelegramUserReputation;
use App\Models\User;
use App\Models\Community;
use App\Models\Payment;
use App\Models\Statistic;
use App\Models\TelegramConnection;
use App\Models\UserSubscription;
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
        $user->createTempToken();
/** @var User $userTest */
        $userTest = User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top',
        ]);
        $userTest->createTempToken();

        Administrator::factory()->create([
            'user_id' => $userTest->id
        ]);

        (new SmsConfirmations([
            'user_id' => $user->id,
            'phone' => 79056714805,
            'status' => 'OK',
            'code' => 3221,
            'status_code' => null,
            'sms_id' => '6599552632944675770001',
            'cost' => 3.5,
            'ip' => '95.71.116.119',
            'attempts' => 1,
            'isblocked' => 0,
            'confirmed' => 1,
            'created_at' => '2022-08-08 13:41:03',
            'updated_at' => '2022-08-08 13:41:03',
        ]))->save();

        (new SmsConfirmations([
            'user_id' => $userTest->id,
            'phone' => 79056714805,
            'status' => 'OK',
            'code' => 3221,
            'status_code' => null,
            'sms_id' => '6599552632944675770001',
            'cost' => 3.5,
            'ip' => '95.71.116.119',
            'attempts' => 1,
            'isblocked' => 0,
            'confirmed' => 1,
            'created_at' => '2022-08-08 13:41:03',
            'updated_at' => '2022-08-08 13:41:03',
        ]))->save();


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
            SubscriptionSeeder::class,
//            TelegramStatisticSeeder::class,
            TelegramBotActionLogSeeder::class,
            TelegramUserListSeeder::class,
            TelegramUserReputationSeeder::class,
        ],
        [
            'user' => $user,
            'userTest' => $userTest,
        ]);

        UserSubscription::create([
            'user_id'=>$userTest->id,
            'subscription_id' => 1,
            'created_at'=>now(),
            'isRecurrent' => true,
            'isActive' =>true,
            'expiration_date'=>now()->addDays(30)->timestamp,
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
