<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $template = Template::create([
            'title' => 'Аудио',
            'preview' => 0,
            'html' => '<section class="baseTemplate audio_module">[[audio_1]]</section>',
        ]);

        $template = Template::create([
            'title' => 'Видео',
            'preview' => 0,
            'html' => '<section class="baseTemplate video_module">[[video_1]]</section>',
        ]);

        $template = Template::create([
            'title' => 'Текст',
            'preview' => 0,
            'html' => '<section class="baseTemplate text_module">[[text_1]]</section>',
        ]);

        $template = Template::create([
            'title' => 'Картинка',
            'preview' => 0,
            'html' => '<section class="baseTemplate img_module">[[image_1]]</section>',
        ]);
    }
}
