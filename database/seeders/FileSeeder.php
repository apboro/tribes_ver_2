<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Database\Seeder;

use Faker;

class FileSeeder extends Seeder
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

        $faker = Faker\Factory::create();

        File::factory()
            ->state([
                'mime' => 'video/mp4',
                'size' => 0,
                'filename' => '932DACD4-683E-463A-98B2-98FE64147503',
                'isImage' => 0,
                'url' => 'https://bl.webcaster.pro/file/start/api_free_e795d0e3345d15d723c60e1b1a0cde9b_hd/817_9558134457/b0e5fd1ad044431de7eff2fb69b3f600/4809571506.m3u8',
                'isVideo' => 1,
                'isAudio' => 0,
                'remoteFrame' => 1685613,
                'description' => '["https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/1.jpg","https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/2.jpg","https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/3.jpg","https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/4.jpg","https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/5.jpg"]',
                'iframe' => '<iframe referrerpolicy=\'no-referrer-when-downgrade\' frameborder=\'0\' width=\'640\' height=\'480\' scrolling=\'no\' src=\'//fit-univ42.clients.webcaster.pro/iframe/feed/start/api_free_5aca09d08d209f77db007c8bead61c4d_hd/817_9558134457/63aa363def5d79556aabb108d4a2dd9f/4809571506?sr=1113&type_id=&width=640&height=480&lang=ru\' allowfullscreen allow=\'encrypted-media\' allow=\'autoplay\'></iframe>'
            ])
            ->create([
                'uploader_id' => $userTest->id,
            ]);


        /*File::factory()
            ->state([
                'mime' => 'image/jpg',
                'size' => 0,
                'filename' => '932DACD4-683E-463A-98B2-98FE64147503',
                'isImage' => 1,
                'isVideo' => 0,
                'isAudio' => 0,
                'remoteFrame' => null,
                'description' => null,
                'iframe' => null
            ])
            ->urlImage()
            ->count(10)
            ->create([
                'url' => $faker->imageUrl($width = rand(300,700), $height = rand(300,700),null,true,null,false),
                'uploader_id' => $userTest->id,
            ]);*/
    }


        /*
        Тестовое видео url
            https://bl.webcaster.pro/file/start/api_free_e795d0e3345d15d723c60e1b1a0cde9b_hd/817_9558134457/b0e5fd1ad044431de7eff2fb69b3f600/4809571506.m3u8

        Тестовое видео iFrame
            <iframe referrerpolicy='no-referrer-when-downgrade' frameborder='0' width='640' height='480' scrolling='no' src='//fit-univ42.clients.webcaster.pro/iframe/feed/start/api_free_5aca09d08d209f77db007c8bead61c4d_hd/817_9558134457/63aa363def5d79556aabb108d4a2dd9f/4809571506?sr=1113&type_id=&width=640&height=480&lang=ru' allowfullscreen allow='encrypted-media' allow='autoplay'></iframe>

        Тестовое видео description
            ["https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/1.jpg","https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/2.jpg","https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/3.jpg","https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/4.jpg","https:\/\/bl.webcaster.pro\/events\/1685613\/preset_thumbnail\/big\/5.jpg"]
        */
}
