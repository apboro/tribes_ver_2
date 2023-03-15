<?php

namespace Tests\Feature\Api\v3;

use App\Helper\PseudoCrypt;
use App\Models\Course;
use Tests\TestCase;

class ApiCourseTest extends TestCase
{

    private $url = [
        'create_course' => 'api/v3/courses',
        'show_courses' => 'api/v3/courses',
        'update_course' => 'api/v3/courses/',
        'show_course_for_all' => 'api/v3/courses/show',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'create_course_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    'id',
                    'title',
                    'cost',
                    'access_days',
                    'payment_title',
                    'payment_description',
                    'payment_link',
                    'preview_link',
                    'isActive',
                    'isPublished',
                    'isEthernal',
                    'price',
                    'attachments',
                    'thanks_text',
                    'shipping_noty',
                    'activation_date',
                    'deactivation_date',
                    'publication_date'
                ],
            ],
        ],
        'show_courses_error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'show_courses_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [[
                    "id",
                    "title",
                    "cost",
                    "access_days",
                    "payment_title",
                    "payment_description",
                    "payment_link",
                    "preview_link",
                    "isActive",
                    "isPublished",
                    "isEthernal",
                    "price",
                    "attachments",
                    "thanks_text",
                    "shipping_noty",
                    "activation_date",
                    "deactivation_date",
                    "publication_date",
                ]
                ],
            ],
        ],
        'show_courses_success_empty_set' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data',
            ],
        ],
        'update_course_id_not_int'=>[
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'update_course_id_not_exists'=>[
            'expected_status' => 404,
            'expected_structure' => [
                'message',
            ],
        ],
        'update_course_not_authorize'=>[
            'expected_status' => 404,
            'expected_structure' => [
                'message',
            ],
        ],
        'update_course_success'=>[
            'course_meta'=>[
                'title' => 'testNew',
                'cost' => 1000,
                'access_days' =>200,
                'isPublished' => true,
                'isActive' => true,
                'isEthernal'=>true,
                'shipping_noty'=>true,
                'payment_title' => 'testPaymentTitle',
                'payment_description' => 'paymentDescription',
                'thanks_text' =>'thanksText',
            ],
            'expected_status' => 200,
            'expected_structure' => [
            ],
        ],
        'show_course_for_all_not_exist'=>[
            'expected_status' => 404,
            'expected_structure' => [
                'message',
            ],
        ],
        'show_course_for_all_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    "id",
                    "title",
                    "cost",
                    "access_days",
                    "payment_title",
                    "payment_description",
                    "payment_link",
                    "preview_link",
                    "isActive",
                    "isPublished",
                    "isEthernal",
                    "price",
                    "attachments",
                    "thanks_text",
                    "shipping_noty",
                    "activation_date",
                    "deactivation_date",
                    "publication_date",
                ],
            ],
        ],
    ];

    public function test_create_course_error_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['create_course']);

        $response
            ->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_create_course_success()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_course']);
        $response->assertStatus($this->data['create_course_success']['expected_status'])
            ->assertJsonStructure($this->data['create_course_success']['expected_structure']);
    }


    public function test_get_users_course_error_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['show_courses']);

        $response
            ->assertStatus($this->data['show_courses_error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['show_courses_error_not_auth']['expected_structure']);
    }

    public function test_get_user_course_success()
    {
        for ($z = 0; $z < 3; $z++) {
            Course::create([
                'owner' => $this->custom_user->id,
                'title' => 'Новый курс'
            ]);
        }

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_courses']);

        $response->assertStatus($this->data['show_courses_success']['expected_status'])
            ->assertJsonStructure($this->data['show_courses_success']['expected_structure']);
    }

    public function test_get_user_course_success_empty_set()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_courses']);

        $response->assertStatus($this->data['show_courses_success_empty_set']['expected_status'])
            ->assertJsonStructure($this->data['show_courses_success_empty_set']['expected_structure']);
    }


    public function test_update_course_id_not_int()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_course'].'test');

        $response->assertStatus($this->data['update_course_id_not_int']['expected_status'])
            ->assertJsonStructure($this->data['update_course_id_not_int']['expected_structure']);
    }

    public function test_update_course_id_not_exists()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_course'].'9999999');

        $response->assertStatus($this->data['update_course_id_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['update_course_id_not_exists']['expected_structure']);
    }

    public function test_update_course_not_authorize()
    {
        $course = Course::create([
            'owner' => $this->custom_user->id,
            'title' => 'Новый курс'
        ]);
        $this->createUserForTest();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_course'].$course->id);

        $response->assertStatus($this->data['update_course_not_authorize']['expected_status'])
            ->assertJsonStructure($this->data['update_course_not_authorize']['expected_structure']);
    }

    public function test_update_course_success()
    {
        $course = Course::create([
            'owner' => $this->custom_user->id,
            'title' => 'testlOld',
            'cost' => 0,
            'access_days' =>100,
            'isPublished' => false,
            'isActive' => false,
            'payment_title' => 'testPaymentTitleOld',
            'payment_description' => 'paymentDescriptionOld',
            'thanks_text' =>'thanksTextOld',
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_course'].$course->id,$this->data['update_course_success']);

        $response->assertStatus($this->data['update_course_success']['expected_status'])
            ->assertJsonStructure($this->data['update_course_success']['expected_structure']);
        $after_update = Course::where('id','=',$course->id)->first();

        $this->assertEquals($this->data['update_course_success']['course_meta']['title'],$after_update->title);
        $this->assertEquals($this->data['update_course_success']['course_meta']['access_days'],$after_update->access_days);
        $this->assertEquals($this->data['update_course_success']['course_meta']['payment_title'],$after_update->payment_title);
        $this->assertEquals($this->data['update_course_success']['course_meta']['payment_description'],$after_update->payment_description);
        $this->assertEquals($this->data['update_course_success']['course_meta']['thanks_text'],$after_update->thanks_text);
        $this->assertEquals($this->data['update_course_success']['course_meta']['isPublished'],$after_update->isPublished);
        $this->assertEquals($this->data['update_course_success']['course_meta']['isPublished'],$after_update->isActive);
        $this->assertEquals($this->data['update_course_success']['course_meta']['cost'],$after_update->cost);

    }

    public function test_show_course_for_all_not_exist()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['show_course_for_all'].'/999999999');

        $response
            ->assertStatus($this->data['show_course_for_all_not_exist']['expected_status'])
            ->assertJsonStructure($this->data['show_course_for_all_not_exist']['expected_structure']);
    }


    public function test_show_course_for_all_success()
    {
        $course = Course::create([
            'owner' => $this->custom_user->id,
            'title' => 'testlOld',
            'cost' => 0,
            'access_days' =>100,
            'isPublished' => false,
            'isActive' => false,
            'payment_title' => 'testPaymentTitleOld',
            'payment_description' => 'paymentDescriptionOld',
            'thanks_text' =>'thanksTextOld',
        ]);
        $id = PseudoCrypt::hash($course->id);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['show_course_for_all'].'/'.$id);
        $course_is_created = Course::where('id','=',$course->id)->first();

        $response
            ->assertStatus($this->data['show_course_for_all_success']['expected_status'])
            ->assertJsonStructure($this->data['show_course_for_all_success']['expected_structure']);

        $this->assertEquals($course->id,$course_is_created->id);
    }

}
