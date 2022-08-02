<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Statistic\MProduct;
use App\Models\Statistic\MProductSale;
use App\Models\Statistic\MProductUserView;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class StatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Course $course */
        /** @var User $owner */
        $course = Course::where('isActive', 1)->get()->first();
        if (empty($course)) {
            $owner = $userTest ?? User::factory()->has(Community::factory())->create();
            $course = Course::factory()->create([
                'owner' => $owner->id,
                'isActive' => 1,
                'cost' => rand(200, 5000),
            ]);
        } else {
            $owner = $course->author()->get()->first();
        }

        $buyers = User::factory()->count(3)
            ->has(Payment::factory()->typeCourse($course->id)->state([
                'status' => 'CONFIRMED',
                'community_id' => $owner->communities()->first()->id,
                'author' => $owner->id,
            ]))
            ->create();


        $product = MProduct::factory()->create([
            'uuid' => $course->uuid,
            'type' => 'course',
        ]);
        $bayer1 = $buyers->get(0);
        $bayer2 = $buyers->get(1);
        $bayer3 = $buyers->get(2);

        MProductSale::factory()
            ->count(3)
            ->state(new Sequence(
                [
                    'payment_id' => $bayer1->payments()->first()->id,
                    'uuid' => $course->uuid,
                    'user_id' => $bayer1->id,
                    'price' => $course->cost,
                ],
                [
                    'payment_id' => $bayer2->payments()->first()->id,
                    'uuid' => $course->uuid,
                    'user_id' => $bayer2->id,
                    'price' => $course->cost,
                ],
                [
                    'payment_id' => $bayer3->payments()->first()->id,
                    'uuid' => $course->uuid,
                    'user_id' => $bayer3->id,
                    'price' => $course->cost,
                ],
            ))
            ->create();

        MProductUserView::factory()->count(3)
            ->state(new Sequence(
                ['uuid' => $course->uuid, 'user_id' =>  $bayer1->id],
                ['uuid' => $course->uuid, 'user_id' =>  $bayer2->id],
                ['uuid' => $course->uuid, 'user_id' =>  $bayer3->id],
            ))
            ->create();

    }
}
