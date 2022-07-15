<?php

namespace Tests\Feature\Files;

use App\Models\Community;
use App\Models\Course;
use App\Models\File;
use App\Models\TelegramConnection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

use Illuminate\Support\Str;

class FileUploadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');

//        dd($file);
        $response = $this->post(route('fileUpload'), [
            'avatar' => $file,
        ]);

        Storage::disk('avatars')->assertExists($file->hashName());
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_uploadFiles()
    {
        $data = $this->prepareDBCommunity();

//        Storage::fake('local');

        $file1 =  UploadedFile::fake()->image('avatar.jpg');
        $file2 =  UploadedFile::fake()->image('avatar1.jpg');
        $response = $this->post('/api/file/upload', [
            'course_id' => $data['course_id'],
            'file' => [
                $file1,
                $file2
            ],
        ],
            ['Authorization' => "Bearer {$data['api_token']}"],
        );

        $content = json_decode($response->getContent(), true);
        $response->assertStatus(200);
        foreach ($content['file'] as $file) {
            $this->assertDatabaseHas(File::class, [
                'id' => $file['id']
            ]);
//            dd(Storage::disk('local'));
//            'storage\app\public\image\15_07_22\4c20dcb2ecfa83d3eb5d87f8c4a71c19.jpg'
//            Storage::disk('local')->assertExists( storage_path('app/public/image/15_07_22/' . $file['filename']) );
        }


//        dd($response->assertStatus(500));

//        $response->assertStatus(401);


//        Storage::disk('public')->assertExists('file/' . $file->hashName());


    }



    /**
     * @return array|mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function prepareDBCommunity(?array $data = [])
    {
//        $data = $this->getDataFromFile('reply_text_message.json');
        $user = Sanctum::actingAs(
        User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top'
        ]));

        $course = Course::factory()
            ->create([
                'owner' => $user->id,
            ]);

        $uploadFiles = [
            new UploadedFile( Storage::disk('test_data')->path('files/Screenshot_1.png'), 'Screenshot_1.png', null, true ),
            new UploadedFile( Storage::disk('test_data')->path('files/Screenshot_4.png'), 'Screenshot_4.png', null, true  )
        ];

        /*$stub1 = Storage::disk('test_data')->path('files/Screenshot_1.png');
        $name1 = Str::random(8).'.png';
        $path1 = sys_get_temp_dir().'/'.$name1;
        copy($stub1, $path1);
        //////
        $stub2 = Storage::disk('test_data')->path('files/Screenshot_4.png');
        $name2 = Str::random(8).'.png';
        $path2 = sys_get_temp_dir().'/'.$name2;
        copy($stub2, $path2);*/

        /*$uploadFiles = [
            new UploadedFile($path1, $name1,'image/png', null, true),
            new UploadedFile($path2, $name2,'image/png', null, true)
        ];*/

        $data = [
            'api_token' => $user->api_token,
            'course_id' => $course->id,
        ];
//dd($data);
        return $data;
    }
}
