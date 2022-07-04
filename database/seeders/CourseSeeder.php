<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Course;
use App\Models\File;
use App\Models\TelegramUser;
use App\Models\User;

use App\Repositories\File\FileRepositoryContract;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File as FFile;

use App\Http\Controllers\API\FileController;

class CourseSeeder extends Seeder
{
    private $fileRepo;

    public function __construct( FileRepositoryContract $fileRepo )
    {
        $this->fileRepo = $fileRepo;
    }
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

///////////////////////////////////////////////////////////////////////////////////////////////////
        $imgs = glob('public/testData/files/testImages/*');
        $img =  $imgs[array_rand($imgs)];
//        dd($img);
        $file = FileController::pathToUploadedFile($img, false);

        $fileData['file'] = $file;
        $fileData['crop'] = false;
        $fileData['cropData'] = null;
        $preview = $this->fileRepo->storeFile($fileData);
///////////////////////////////////////////////////////////////////////////////////////////////////

        Course::factory()
            ->count(5)
            ->loadImage($preview)
            ->create([
                'owner' => $userTest->id,
            ]);
    }
}
