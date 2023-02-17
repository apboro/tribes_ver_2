<?php

namespace Tests\old_test\Feature\Files;

use App\Models\Course;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FileUploadTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_uploadFilesImages()
    {
        $data = $this->prepareDBCommunity();

        $file1 =  UploadedFile::fake()->image('avatar.jpg')->size(1000);
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

        //Проверяем наличие записи в базе
        $file = $content['file'];
        $this->assertDatabaseHas(File::class, [
            'id' => $file['id']
        ]);
        //Проверяем наличие файла в папке
        Storage::disk('public')->assertExists(Str::replace('/storage', '', $file['url']));

        $response_delete = $this->post('/api/file/delete', [
            'id' => $file['id'],
        ],
            ['Authorization' => "Bearer {$data['api_token']}"],
        );
        $response_delete->assertStatus(200);

        //Проверяем отсутствие записи в базе
        $this->assertDatabaseMissing(File::class, [
            'id' => $file['id']
        ]);
        //Проверяем отсутствие файла в папке
        Storage::disk('public')->assertMissing(Str::replace('/storage', '', $file['url']));

    }

    /**
     * @return array|mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function prepareDBCommunity(?array $data = [])
    {
        $user = Sanctum::actingAs(
        User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top'
        ]));

        $course = Course::factory()
            ->create([
                'owner' => $user->id,
            ]);

        $data = [
            'api_token' => $user->api_token,
            'course_id' => $course->id,
        ];

        return $data;
    }
}
