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
            'name' => 'Доступ на 14 дней',
            'slug' => 'trial_plan',
            'description' => '[
                {"name": "Создайте в своем магазине до 50 товаров","description" : null},
                {"name": "Модули модерация, платный доступ, донаты включены в тариф ограничение до 3-х чатов", "description": null},
                {"name": "Размещайте данные объёмом трафика для хранения медиа - 10 ГБ и 10 постов в мес.", "description":  null},
                {"name": "Комиссия при выводе денежных средств: ","description":  "7%"}
            ]',
            'is_active' => true,
            'price' => 0,
            'period_days' => 14,
            'sort_order' => 0,
            'file_id' => null,
            'commission' => 7,
        ]);
        Subscription::create([
            'name' => 'На месяц',
            'slug' => 'pay_plan',
            'description' => '[
                {"name": "Создайте в своем магазине до 50 товаров", "description" : null},
                {"name": "Модули модерация, платный доступ, донаты включены в тариф ограничение до 3-х чатов", "description": null},
                {"name": "Размещайте данные объёмом трафика для хранения медиа - 10 ГБ и 10 постов в мес.", "description": null},
                {"name": "Комиссия при выводе денежных средств: ", "description": "7%"}
            ]',
            'is_active' => true,
            'price' => 100,
            'period_days' => 30,
            'sort_order' => 2,
            'file_id' => null,
            'commission' => 7,
            'badge' => 'акция до 1 июля',
        ]);
        Subscription::create([
            'name' => 'На месяц',
            'slug' => 'pay_juri_plan',
            'description' => '[
                {"name": "Создайте в своем магазине до 50 товаров","description" : null},
                {"name": "Модули модерация, платный доступ, донаты включены в тариф ограничение до 3-х чатов", "description": null},
                {"name": "Размещайте данные объёмом трафика для хранения медиа - 10 ГБ и 10 постов в мес.","description": null},
                {"name": "Комиссия при выводе денежных средств: ", "description": "7%"} 
            ]',
            'is_active' => true,
            'price' => 2500,
            'period_days' => 30,
            'sort_order' => 3,
            'file_id' => null,
            'commission' => 7,
            'badge' => 'акция до 1 июля',
        ]);
    }
}
