<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mediacontent\CourseStoreRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Repositories\Course\CourseRepositoryContract;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{

    protected $courseRepo;

    public function __construct(CourseRepositoryContract $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    public function store(CourseStoreRequest $request)
    {
        $course = $this->courseRepo->store($request);

        if ($course instanceof Response){
            return $course;
        } else {
            return response()->json([
                "status" => "ok",
                "id" => $course->id,
                "message" => !$course->wasRecentlyCreated ? "Медиаконтент успешно обновлён" : "Медиаконтент успешно создан",
                "details" => "",
                "course" => new CourseResource($course)
            ], 200);
        }
    }

    public function edit(Request $request)
    {
        $course = Course::whereId($request['id'])->first();

        if(!$course){
            return response()->json([
                'status' => 'error',
                'message' => 'Не найдено',
                'details' => 'Медиаконтент не найден или у вас нет прав для редактирования',
            ]);
        }
        return response()->json([
            "status" => "ok",
            "id" => $course->id,
            "details" => "",
            "course" => new CourseResource($course)
        ], 200);
    }

    public function delete(Request $request)
    {
        $course = Course::find($request['id']);
        if ($course) {
            $course->delete();
            return response()->json([
                "status" => "ok",
                "message" => "Медиаконтент успешно удалён",
            ]);
        } else return response()->json([
            "status" => "error",
            "message" => "что то пошло не так",
            "details" => "У вас недостаточно прав",         
        ]);
    }
}
