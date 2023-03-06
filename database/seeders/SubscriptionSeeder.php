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
            'name' => 'Старт',
            'slug' => 'start',
            'description' => 'Управление сообществами
            Создание курсов (LMS)
            Аналитика сообществ
            Комиссия с продаж 15%',
            'is_active' => true,
            'price' => 0,
            'period_days' => 0,
            'sort_order' => 1,
            'commission' => 15,
            'file_id' => null,
        ]);
        Subscription::create([
            'name' => 'Организатор',
            'slug' => 'organizer',
            'description' => 'Улучшенное управление сообществами
            Создание курсов (LMS)
            Расширенная аналитика сообществ
            Комиссия с продаж 10%',
            'is_active' => true,
            'price' => 500,
            'period_days' => 30,
            'sort_order' => 2,
            'commission' => 10,
            'file_id' => null,
        ]);
        Subscription::create([
            'name' => 'Инфобизнес',
            'slug' => 'info_business',
            'description' => 'Улучшенное управление сообществами
            Улучшенные возможности LMS
            Улучшенная аналитика вторского контента
            Комиссия с продаж 8%',
            'is_active' => true,
            'price' => 1000,
            'period_days' => 30,
            'sort_order' => 3,
            'commission' => 8,
            'file_id' => null,
        ]);
    }
}
