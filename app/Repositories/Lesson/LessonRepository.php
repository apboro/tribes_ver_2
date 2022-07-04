<?php

namespace App\Repositories\Lesson;

use App\Models\Course;
use App\Models\File;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Text;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LessonRepository implements LessonRepositoryContract
{
    public function store($requestData)
    {
        $course = Course::whereId($requestData['course_id'])->first();

        if(!$course){
            return response()->json([
                'status' => 'error',
                'message' => 'Не найдено',
                'details' => 'Медиаконтент не найден',
            ]);
        }

        $lesson = $requestData['id'] ?  Lesson::where('id', $requestData['id'])->first() : new Lesson();

        $lesson->course_id = $course->id;

        $lesson->title = $requestData['lesson_meta']['title'] ?? 'Новый урок';

        $lesson->active = $requestData['active'] ?? 0;
        $lesson->isPublish = $requestData['isPublish'] ?? 0;

        $lesson->save();

        $updates = $this->moduleUpdate($lesson, $requestData);

        if($updates instanceof JsonResponse){
            return $updates;
        }

        return $lesson;

    }

    protected function moduleUpdate($lesson, $requestData)
    {
        $collection = $lesson->modules()->pluck('id')->toArray();
        $newCollection = [];
        if ($requestData['modules']) {
            foreach($requestData['modules'] as $index => $m){

                if((int)$m['id'] !== 0){
                    $module = Module::whereId($m['id'])->first();
                    if(!$module){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Не найдено',
                            'details' => 'Модуль не найден',
                        ]);
                    }
                } else {
                    $module = new Module();
                    $module->template_id = $m['template_id'];
                    $module->lesson_id = $lesson->id;

                }
                $module->text()->delete();
                $module->index = $index;
                $module->save();
                $newCollection[] = $module->id;
                $requires = $module->getRequiresByTemplate();
                
                $pass = true;

                foreach ($requires as $require){
                    if(!isset($m[$require])){
                        $pass = false;
                        break;
                    }
                }

                foreach($requires as $require){
                    $tag = explode('_', $require);

                    switch ($tag[0]){
                        case 'text':
                            $mod = new Text();
                            $mod->text = $m[$require];
//                                $mod->template_id = $module->id;
                            $mod->save();
                            $mod->module()->attach($module);
                            break;

                        case 'image':
                            $mod = File::find(isset($m[$require]) ? (int)$m[$require] : 0);
                            if($mod && $pass){
                                $module->image()->sync([$mod->id]);
                            } else {
                                $module->image()->delete();
                            }
                            break;

                        case 'audio':
                            $mod = File::find((int)$m[$require]);
                            if($mod && $pass){
                                $module->audio()->sync([$mod->id]);
                            } else {
                                $module->audio()->delete();
                            }
                            break;

                        case 'video':

                            $mod = File::find((int)$m[$require]);
                            if($mod && $pass){
                                $module->video()->sync([$mod->id]);
                            } else {
                                $module->video()->delete();
                            }
                            break;
                    }
                }


//                if(!$pass){
//                    return response()->json([
//                        'status' => 'error',
//                        'message' => 'Неверные параметры',
//                        'details' => 'Недостаточно данных для указанного шаблона',
//                    ]);
//                } else {
//
//
//                }
            }
        }
        Module::whereIn('id', array_diff($collection, $newCollection))->delete();
        return true;
    }

    protected function saveMod($requestData, $module, $type)
    {
        $data = $this->getRequestType($requestData, $type);
        if ($data) {
            foreach ($data as $value) {
                $image = File::find($value);
                $image->module()->attach($module);
            }
            
        }
    }

    protected function saveVideo($requestData, $module)
    {
        $data = $this->getRequestType($requestData, 'video');
        if ($data) {
            foreach ($data as $value) {
                $video = Video::find($value);
                $video->module()->attach($module);
            }
            
        }
    }

    protected function saveText($str, $module)
    {
        $text = new Text();
        $text->text = $str;
        $text->save();

        $text->module()->attach($module);
    }

    protected function getRequestType($requestData, $ident)
    {
        $data = [];
        foreach ($requestData['templates'] as $key => $value){
            if (strpos($key, $ident) !== false){
                $data[] = [$key => $value];
            }
        } 
        return $data;
    }

}