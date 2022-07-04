<?php

namespace App\Http\Controllers;

use App\Helper\PseudoCrypt;
use App\Models\Course;
use App\Models\File;
use App\Models\Lesson;
use App\Models\User;
use App\Repositories\Follower\FollowerRepositoryContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{

    protected $followerRepo;

    public function __construct(FollowerRepositoryContract $follower)
    {
        $this->followerRepo = $follower;
    }

    public function mediaProducts()
    {
        $courses = $this->followerRepo->getAllCourses();
        return view('common.follower.product_list')->withCourses($courses);
    }

    public function product($hash, Request $request)
    {
        $course = $this->followerRepo->getFollowerCourse($hash);

        if($course === 'wait'){
            return view('common.follower.wait');
        }

        if($course === 'expired'){
            return view('common.follower.empty');
        }
        $lesson = $this->followerRepo->getLesson($request, $course);

        $template = $this->followerRepo->getTemplate($lesson);

        return view('common.follower.product')->withCourse($course)->withLesson($lesson)->withTemplate($template);
    }
}
