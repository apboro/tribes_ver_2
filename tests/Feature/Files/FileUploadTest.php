<?php

namespace Tests\Feature\Files;

use App\Models\Community;
use App\Models\Course;
use App\Models\TelegramConnection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example11111()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_uploadFiles()
    {
        $data = $this->prepareDBCommunity();
//dd($data);
        $response = $this->post('/api/file/upload', $data);

//        dd($response);

        $response->assertStatus(302);
    }



    /**
     * @return array|mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function prepareDBCommunity(?array $data = [])
    {
//        $data = $this->getDataFromFile('reply_text_message.json');
        $user = User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top',
        ]);
        $course = Course::factory()
            ->create([
                'owner' => $user->id,
            ]);

        $uploadFiles = [
            new UploadedFile( Storage::disk('test_data')->path('files/Screenshot_1.png'), 'Screenshot_1.png', null, true ),
            new UploadedFile( Storage::disk('test_data')->path('files/Screenshot_4.png'), 'Screenshot_4.png', null, true  )
        ];


        $data = [
            'course_id' => $course->id,
            'file' => $uploadFiles
        ];

        return $data;
    }
}
