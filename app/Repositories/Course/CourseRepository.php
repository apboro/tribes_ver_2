<?php

namespace App\Repositories\Course;

use App\Models\Course;
use App\Models\Lesson;
use App\Repositories\Lesson\LessonRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseRepository implements CourseRepositoryContract
{
    public $lessonRepo;

    public function __construct(LessonRepositoryContract $lessonRepo)
    {
        $this->lessonRepo = $lessonRepo;
    }

    public function store($requestData)
    {
        $course = $requestData['id'] !== 0 ? Course::find($requestData['id']) : new Course();

        if(!$course){
            return response()->json([
                "status" => "error",
                "message" => "Курс не найден",
                "details" => "Курс не найден или у вас нет прав для его редактирования",
            ], 200);
        }

        $course->title =                    $requestData['course_meta']['title'];
        $course->cost =                     $requestData['course_meta']['cost'];
        $course->access_days =              $requestData['course_meta']['access_days'];
        $course->isPublished =              $requestData['course_meta']['isPublished'];
        $course->isActive =                 $requestData['course_meta']['isActive'];
        $course->isEthernal  =              $requestData['course_meta']['isEthernal'];
        $course->payment_title =            $requestData['course_meta']['payment_title'];
        $course->payment_description =      $requestData['course_meta']['payment_description'];
        $course->preview =                  $requestData['course_meta']['preview'];

        $course->thanks_text =              $requestData['course_meta']['thanks_text'];
        $course->shipping_noty =            $requestData['course_meta']['shipping_noty'];
        $course->activation_date =          $requestData['course_meta']['activation_date'];
        $course->deactivation_date =        $requestData['course_meta']['deactivation_date'];
        $course->publication_date =         $requestData['course_meta']['publication_date'];

        $course->owner =                    Auth::user()->id;

        $course->save();

        $this->syncLessons($requestData, $course);

        return $course;
    }

    protected function syncLessons($requestData, $course)
    {

        DB::transaction(function () use ($requestData, $course) {
            if($requestData['lessons'] && count($requestData['lessons'])){

                $lessonsToRemove = Lesson::where('course_id', $course->id)->get()->pluck('id');
                $lessons = Lesson::whereIn('id', array_column($requestData['lessons'], 'id'))->get();

                $uselessLessons = array_diff($lessonsToRemove->toArray(), $lessons->pluck('id')->toArray());
                Lesson::whereIn('id', $uselessLessons)->delete();
                Lesson::whereIn('id', $lessons->pluck('id'))->update(['course_id' => $course->id]);
                foreach ($requestData['lessons'] as $l){
                    $lesson = $this->lessonRepo->store($l);
                }
            }
        });
    }
}