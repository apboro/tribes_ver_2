<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //'id' => '',
            'mime' => '',
            'size' => '',
            'filename' => '',
            'rank' => 0,
            'isImage' => '',
            'url' => '',
            'hash' => null,
            'uploader_id' => '',
            'isVideo' => '',
            'isAudio' => '',
            'remoteFrame' => '',
            'webcaster_event_id' => null,
            'description' => '',
            'iframe' => ''
        ];
    }



    public function urlImage()
    {
        return $this->state([
            'url' => $this->faker->imageUrl($width = rand(300,700), $height = rand(300,700),null,true,null,false),
        ]);
    }
}
