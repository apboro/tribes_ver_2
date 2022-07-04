<?php

namespace Database\Factories;

use App\Http\Controllers\API\FileController;
use App\Repositories\File\FileRepositoryContract;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
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
            'title' => 'Курс ' . $this->faker->text(8),
            'owner' => null,
            'preview' => null,//Не готово
            'description' => null,
            'isActive' => array_rand([0,1]),
            'price' => null,
            'cost' => rand(200, 5000),
            'access_days' => rand(0, 30),
            'isPublished' => array_rand([0,1]),
            'payment_title' => 'Заголовок платежной страницы' . $this->faker->text(5),
            'payment_description' => '<p>' . $this->faker->text(250) . '</p>',
            'isEthernal' => array_rand([0,1]),
            'community_id' => null,
            'thanks_text' => 'Спасибо за покупку! ' . $this->faker->text(5),
            'shipping_noty' => array_rand([0,1]),
            'shipping_views' => rand(0, 300),
            'shipping_clicks' => rand(0, 300),
            'views' =>  rand(0, 300),
            'clicks' =>  rand(0, 300),
            'shipped_count' =>  rand(0, 300)
        ];
    }

    /*public function loadImage()
    {

        $imgs = glob('public/testData/files/testImages/*');

        $img =  $imgs[0]

        dd($img);
    }*/

    /*public function withImage($img): CourseFactory
    {
        return $this->state(function (array $attributes) use ($img) {
            return [
                'preview' => $img,
            ];
        });
    }*/

    public function loadImage($preview): CourseFactory
    {
        return $this->state(function (array $attributes) use ($preview) {
            return [
                'preview' => $preview->id,
            ];
        });
    }
}
