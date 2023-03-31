<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Seeder;

class UserSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users=User::all();
        foreach ($users as $user) {
            UserSubscription::create([
                'user_id' => $user->id,
                'subscription_id'=>rand(1,3),
                'isRecurrent'=>true,
                'expiration_date' => now(),
            ]);
        }
    }
}
