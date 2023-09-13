<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subscription::create([
            'name' => 'Пробный период',
            'slug' => 'trial_period',
            'description' => '[{"name": "Управление сообществами","description" : null},{"name": "Создание курсов (LMS)","description": null},{"name": "Аналитика сообществ","description": null},{"name": "Комиссия с продаж","description":"15%"}]',
            'is_active' => true,
            'price' => 0,
            'period_days' => 30,
            'sort_order' => 1,
            'commission' => 15,
            'file_id' => null,
        ]);
        Subscription::create([
            'name' => 'Платный период',
            'slug' => 'pay_period',
            'description' => '[{"name": "Управление сообществами","description" : null},{"name": "Создание курсов (LMS)","description": null},{"name": "Аналитика сообществ","description": null},{"name": "Комиссия с продаж","description":"15%"}]',
            'is_active' => true,
            'price' =>500,
            'period_days' => 30,
            'sort_order' => 1,
            'commission' => 10,
            'file_id' => null,
        ]);
//        Subscription::create([
//            'name' => 'Старт',
//            'slug' => 'start',
//            'description' => '[{"name": "Управление сообществами","description" : null},{"name": "Создание курсов (LMS)","description": null},{"name": "Аналитика сообществ","description": null},{"name": "Комиссия с продаж","description":"15%"}]',
//            'is_active' => true,
//            'price' => 0,
//            'period_days' => 0,
//            'sort_order' => 1,
//            'commission' => 15,
//            'file_id' => null,
//        ]);
//        Subscription::create([
//            'name' => 'Организатор',
//            'slug' => 'organizer',
//            'description' => '[{"name": "Улучшенное управление сообществами","description" : null},{"name": "Создание курсов (LMS)","description": null},{"name": "Расширенная аналитика сообществ","description": null},{"name": "Комиссия с продаж","description": "10%"}]',
//            'is_active' => true,
//            'price' => 500,
//            'period_days' => 30,
//            'sort_order' => 2,
//            'commission' => 10,
//            'file_id' => null,
//        ]);
//        Subscription::create([
//            'name' => 'Инфобизнес',
//            'slug' => 'info_business',
//            'description' => '[{"name": "Улучшенное управление сообществами","description" : null},{"name": "Улучшенные возможности LMS","description": null},{"name": "Улучшенная аналитика авторского контента","description": null},{"name": "Комиссия с продаж","description": "8%"}]',
//            'is_active' => true,
//            'price' => 1000,
//            'period_days' => 30,
//            'sort_order' => 3,
//            'commission' => 8,
//            'file_id' => null,
//        ]);
    }
}
