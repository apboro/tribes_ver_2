<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mediacontent\LessonStoreRequest;
use App\Models\Template;
use App\Http\Resources\TemplateResourceCollection;
use App\Models\Lesson;
use App\Http\Resources\LessonResource;
use App\Repositories\Lesson\LessonRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{

    public $lessonRepo;

    public function __construct(LessonRepositoryContract $lessonRepo)
    {
        $this->lessonRepo = $lessonRepo;
    }

    public function templateList()
    {
        $template = new TemplateResourceCollection(Template::all());
        if ($template) {
            return $template;
        } else {
            return response()->json([
                "status" => "ok",
                "message" => "Медиаконтент успешно обновлён",
                "details" => "",             
            ]);
        }
    }

    public function store(LessonStoreRequest $request)
    {
        $lesson = $this->lessonRepo->store($request);

        if($lesson instanceof JsonResponse){
            return $lesson;
        }

        if(!$lesson){
            return response()->json([
                'status' => 'error',
                'message' => 'Не найдено',
                'details' => 'Медиаконтент не найден',
            ]);
        }

        return response()->json([
            "status" => "ok",
            "id" => $lesson->id,
            "details" => "",
            "lesson" => new LessonResource($lesson)
        ], 200);
    }

    public function edit(Request $request)
    {
        $lesson = Lesson::where('id', $request['id'])->first();

//        $lesson = new LessonResource($lessonModel);

        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'Что-то пошло не так',
                'details' => 'У вас нет прав для редактирования этого урока',
            ]);
        } else {
            return response()->json([
                "status" => "ok",
                "id" => $lesson->id,
                "details" => "",
                "lesson" => new LessonResource($lesson)
            ], 200);
        }
    }

    public function delete(Request $request)
    {
        $lesson = Lesson::find($request['id']);

        if ($lesson) {
            $lesson->delete();
            return response()->json([
                "status" => "ok",
                "message" => "Медиаконтент успешно удалён",
            ]);
        } else return response()->json([
            "status" => "error",
            "message" => "Урок не найден",
            "details" => "Возможно у вас не достаточно прав",
        ]);
    }
}
