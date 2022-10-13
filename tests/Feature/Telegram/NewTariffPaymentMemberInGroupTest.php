<?php

namespace Tests\Feature\Telegram;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NewTariffPaymentMemberInGroupTest extends TestCase
{
    /**



    INSERT INTO "telegram_users" ("id", "user_id", "telegram_id", "auth_date", "hash", "scene", "first_name", "last_name", "photo_url", "created_at", "updated_at", "user_name", "scene_for_donate") VALUES
    (2,	7,	416272404,	1660286588,	"07ef17b08ed1ce5f804147f102ed333a645086ed8fa4fad41890efd750403248",	NULL,	"Андрей",	"Долгополов",	"/storage/image/avatar/7d6e9ea49041e30bbe0aba512da6559b.jpg",	"2022-08-12 09:43:08",	"2022-08-12 09:48:09",	"a_dolgopolov",	NULL);
    INSERT INTO "telegram_connections" ("id", "user_id", "telegram_user_id", "chat_id", "chat_title", "chat_type", "isAdministrator", "botStatus", "isActive", "isChannel", "isGroup", "hash", "status", "created_at", "updated_at", "chat_invite_link", "photo_url") VALUES
    (4,	7,	416272404,	"-608142614",	"@test1.spodial.com",	"group",	"f",	"administrator",	"f",	"f",	"t",	"c0a3772c0875aa57bc4712bff430aa70",	"completed",	"2022-08-12 09:43:15",	"2022-08-12 10:05:10",	"https://t.me/+-zrPEHVgknExYjAy",	"/images/no-image.svg");

    INSERT INTO "users" ("id", "name", "email", "code", "phone", "email_verified_at", "password", "phone_confirmed", "role_index", "hash", "remember_token", "created_at", "updated_at", "locale", "api_token") VALUES
    (7,	"Andrey Dolgopolov",	"adolgopolov0@gmail.com",	7,	9511420311,	NULL,	"$2y$10$uEtjtvQCk5ZKM1MH44GFC.ERw6Kpc0pHd.OxGfVxsZ/E/VmpTbW3u",	"t",	0,	"$2y$10$TJOLtqtLRnW4Xh14tK1cse4UOjHlagPE4xFPQCKcwuM4R1SSf8gyW",	NULL,	"2022-08-12 09:39:38",	"2022-08-12 09:43:03",	"ru",	"3|wGBNDggxO87TtPbedHoeHgkowLcQeGfyqgfd7twT");
    INSERT INTO "communities" ("id", "connection_id", "owner", "title", "image", "description", "created_at", "updated_at", "hash", "balance") VALUES
    (5,	4,	7,	"@test1.spodial.com",	"/images/no-image.svg",	NULL,	"2022-08-12 09:48:28",	"2022-08-12 10:04:42",	"5ac1Nvb5",	2);

    INSERT INTO "tariffs" ("id", "community_id", "test_period", "title", "main_description", "main_image_id", "welcome_description", "welcome_image_id", "reminder_description", "reminder_image_id", "thanks_description", "thanks_image_id", "created_at", "updated_at", "tariff_notification", "publication_description", "publication_image_id") VALUES
    (5,	5,	0,	NULL,	NULL,	NULL,	"Приветствуем вас в нашем сообществе!",	NULL,	"Благодарим вас за участие в нашем сообществе! Чтобы я дальше радовал вас новинками своего сообщества, прошу меня поддержать продлением вашего тарифа",	NULL,	"Благодарим вас за подписку на мой канал! Благодаря вашей поддержке, я могу продолжать радовать вас новыми релизами. Я приложу все усилия для того, чтобы вы были довольны своим нахождением в моём сообществе!",	NULL,	"2022-08-12 09:48:28",	"2022-08-12 09:48:28",	"t",	"Доступные тарифы",	NULL);
    INSERT INTO "tarif_variants" ("id", "tariff_id", "title", "price", "period", "isActive", "created_at", "updated_at", "number_button") VALUES
    (13,	5,	"standart test",	1,	365,	"t",	"2022-08-12 09:49:05",	"2022-08-12 09:49:05",	NULL);

    INSERT INTO "users" ("id", "name", "email", "code", "phone", "email_verified_at", "password", "phone_confirmed", "role_index", "hash", "remember_token", "created_at", "updated_at", "locale", "api_token") VALUES
    (8,	"posta.vka",	"posta.vka@mail.ru",	0,	NULL,	NULL,	"$2y$10$b.yEHpj1wTLuSdS.AhOifuVWZT/LgkZkx1oaHM0kQA4IJV61M1J8u",	"f",	0,	"$2y$10$45xQWI0nm4.ZOKBXu71dpu7qt9lRiW3jziTrfjT7V74L1vBFi8e7K",	NULL,	"2022-08-12 10:04:06",	"2022-08-12 10:04:06",	"ru",	"4|9ZBAx6HcEciWfMVUIjLo3EexEaifwQ860om3e2yz")

    INSERT INTO "telegram_users" ("id", "user_id", "telegram_id", "auth_date", "hash", "scene", "first_name", "last_name", "photo_url", "created_at", "updated_at", "user_name", "scene_for_donate") VALUES
    (5,	8,	1032346420,	NULL,	NULL,	NULL,	"Дмитрий",	"Сеошин",	NULL,	"2022-08-12 10:05:10",	"2022-08-12 10:05:10",	"IDmtro",	NULL);
     *
    NSERT INTO "telegram_users_tarif_variants" ("tarif_variants_id", "telegram_user_id", "days", "prompt_time", "created_at", "updated_at", "isAutoPay") VALUES
    (13,	5,	365,	"10:05",	"2022-08-12 10:05:10",	NULL,	"t");

     *
    INSERT INTO "payments" ("id", "OrderId", "community_id", "add_balance", "from", "comment", "isNotify", "telegram_user_id", "paymentId", "amount", "paymentUrl", "response", "status", "token", "error", "created_at", "updated_at", "type", "activated", "SpAccumulationId", "RebillId", "user_id", "payable_id", "payable_type", "author") VALUES
    (18,	"18_0812_07",	5,	1,	"posta.vka",	NULL,	"f",	1032346420,	1637865687,	100,	"https://securepayments.tinkoff.ru/5nGbCzyr",	"deprecated",	"CONFIRMED",	"4ec9599fc203d176a301536c2e091a19bc852759b255bd6818810a42c5fed14a",	"0",	"2022-08-12 10:04:07",	"2022-08-12 10:05:10",	"tariff",	"t",	"1884878",	"1133561658",	8,	13,	"App\Models\TariffVariant",	7);

     *
     */

    public function test_example()
    {
        $this->prepareDB();

        $response = $this->post('/bot/webhook',
            $this->getDataFromFile('telegram/tariff_new_member/start_payment_command.json'));

        print_r($response->getContent());
        $response->assertStatus(200);

        $this->assertTrue(
            $this->getTestHandler()->hasRecord([
                'message' => 'send tariff pay message to own author chat bot',
                /*'context' => ['message' => 'Участник Дмитрий Сеошин оплатил standart test в сообществе @test1.spodial.com,
                                стоимость 1 рублей действует до 12.08.2023 г.']*/
                ], 'info'),
            'Сообщение не отправлено в ЛЧ'
        );

        $this->assertDatabaseCount('jobs',1);
    }

    public function prepareDB()
    {
        DB::insert('INSERT INTO "users" ("id", "name", "email", "code", "phone", "email_verified_at", "password", "phone_confirmed", "role_index", "hash", "remember_token", "created_at", "updated_at", "locale", "api_token") VALUES
    (7,	\'Andrey Dolgopolov\',	\'adolgopolov0@gmail.com\',	7,	9511420311,	NULL,	\'$2y$10$uEtjtvQCk5ZKM1MH44GFC.ERw6Kpc0pHd.OxGfVxsZ/E/VmpTbW3u\',	\'t\',	0,	\'$2y$10$TJOLtqtLRnW4Xh14tK1cse4UOjHlagPE4xFPQCKcwuM4R1SSf8gyW\',	NULL,	\'2022-08-12 09:39:38\',	\'2022-08-12 09:43:03\',	\'ru\',	\'3|wGBNDggxO87TtPbedHoeHgkowLcQeGfyqgfd7twT\')
    ');
        DB::insert('INSERT INTO "users" ("id", "name", "email", "code", "phone", "email_verified_at", "password", "phone_confirmed", "role_index", "hash", "remember_token", "created_at", "updated_at", "locale", "api_token") VALUES
    (8,	\'posta.vka\',	\'posta.vka@mail.ru\',	0,	NULL,	NULL,	\'$2y$10$b.yEHpj1wTLuSdS.AhOifuVWZT/LgkZkx1oaHM0kQA4IJV61M1J8u\',	\'f\',	0,	\'$2y$10$45xQWI0nm4.ZOKBXu71dpu7qt9lRiW3jziTrfjT7V74L1vBFi8e7K\',	NULL,	\'2022-08-12 10:04:06\',	\'2022-08-12 10:04:06\',	\'ru\',	\'4|9ZBAx6HcEciWfMVUIjLo3EexEaifwQ860om3e2yz\')
');
        DB::insert('INSERT INTO "telegram_connections" ("id", "user_id", "telegram_user_id", "chat_id", "chat_title", "chat_type", "isAdministrator", "botStatus", "isActive", "isChannel", "isGroup", "hash", "status", "created_at", "updated_at", "chat_invite_link", "photo_url") VALUES
    (4,	7,	416272404,	\'-608142614\',	\'@test1.spodial.com\',	\'group\',	\'f\',	\'administrator\',	\'f\',	\'f\',	\'t\',	\'c0a3772c0875aa57bc4712bff430aa70\',	\'completed\',	\'2022-08-12 09:43:15\',	\'2022-08-12 10:05:10\',	\'https://t.me/+-zrPEHVgknExYjAy\',	\'/images/no-image.svg\');
');
        DB::insert('INSERT INTO "communities" ("id", "connection_id", "owner", "title", "image", "description", "created_at", "updated_at", "hash", "balance") VALUES
    (5,	4,	7,	\'@test1.spodial.com\',	\'/images/no-image.svg\',	NULL,	\'2022-08-12 09:48:28\',	\'2022-08-12 10:04:42\',	\'5ac1Nvb5\',	2);
    ');
        DB::insert('INSERT INTO "telegram_users" ("id", "user_id", "telegram_id", "auth_date", "hash", "scene", "first_name", "last_name", "photo_url", "created_at", "updated_at", "user_name", "scene_for_donate") VALUES
    (2,	7,	416272404,	1660286588,	\'07ef17b08ed1ce5f804147f102ed333a645086ed8fa4fad41890efd750403248\',	NULL,	\'Андрей\',	\'Долгополов\',	\'/storage/image/avatar/7d6e9ea49041e30bbe0aba512da6559b.jpg\',	\'2022-08-12 09:43:08\',	\'2022-08-12 09:48:09\',	\'a_dolgopolov\',	NULL);
    ');
        DB::insert('INSERT INTO "telegram_users" ("id", "user_id", "telegram_id", "auth_date", "hash", "scene", "first_name", "last_name", "photo_url", "created_at", "updated_at", "user_name", "scene_for_donate") VALUES
    (5,	8,	1032346420,	NULL,	NULL,	NULL,	\'Дмитрий\',	\'Сеошин\',	NULL,	\'2022-08-12 10:05:10\',	\'2022-08-12 10:05:10\',	\'IDmtro\',	NULL);
    ');

        DB::insert('INSERT INTO "tariffs" ("id", "community_id", "test_period", "title", "main_description", "main_image_id", "welcome_description", "welcome_image_id", "reminder_description", "reminder_image_id", "thanks_description", "thanks_image_id", "created_at", "updated_at", "tariff_notification", "publication_description", "publication_image_id") VALUES
    (5,	5,	0,	NULL,	NULL,	NULL,	\'Приветствуем вас в нашем сообществе!\',	NULL,	\'Благодарим вас за участие в нашем сообществе! Чтобы я дальше радовал вас новинками своего сообщества, прошу меня поддержать продлением вашего тарифа\',	NULL,	\'Благодарим вас за подписку на мой канал! Благодаря вашей поддержке, я могу продолжать радовать вас новыми релизами. Я приложу все усилия для того, чтобы вы были довольны своим нахождением в моём сообществе!\',	NULL,	\'2022-08-12 09:48:28\',	\'2022-08-12 09:48:28\',	\'t\',	\'Доступные тарифы\',	NULL);
    ');
        DB::insert('INSERT INTO "tarif_variants" ("id", "tariff_id", "title", "price", "period", "isActive", "created_at", "updated_at", "number_button") VALUES
    (13,	5,	\'standart test\',	1,	365,	\'t\',	\'2022-08-12 09:49:05\',	\'2022-08-12 09:49:05\',	NULL);
');
        DB::insert('INSERT INTO "telegram_users_tarif_variants" ("tarif_variants_id", "telegram_user_id", "days", "prompt_time", "created_at", "updated_at", "isAutoPay") VALUES
    (13,	5,	365,	\'10:05\',	\'2022-08-12 10:05:10\',	NULL,	\'t\');
');
        DB::insert('INSERT INTO "payments" ("id", "OrderId", "community_id", "add_balance", "from", "comment", "isNotify", "telegram_user_id", "paymentId", "amount", "paymentUrl", "response", "status", "token", "error", "created_at", "updated_at", "type", "activated", "SpAccumulationId", "RebillId", "user_id", "payable_id", "payable_type", "author") VALUES
    (18,	\'18_0812_07\',	5,	1,	\'posta.vka\',	NULL,	\'f\',	1032346420,	1637865687,	100,	\'https://securepayments.tinkoff.ru/5nGbCzyr\',	\'deprecated\',	\'CONFIRMED\',	\'4ec9599fc203d176a301536c2e091a19bc852759b255bd6818810a42c5fed14a\',	\'0\',	\'2022-08-12 10:04:07\',	\'2022-08-12 10:05:10\',	\'tariff\',	\'t\',	\'1884878\',	\'1133561658\',	8,	13,	\'App\Models\TariffVariant\',	7);
');

    }
}
