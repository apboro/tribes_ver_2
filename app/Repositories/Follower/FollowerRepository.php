<?php

namespace App\Repositories\Follower;

use App\Helper\PseudoCrypt;
use App\Models\Course;
use App\Models\File;
use App\Models\Lesson;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerRepository implements FollowerRepositoryContract
{

    public function getAllCourses()
    {
        return Auth::user()->courses()->get();
    }

    public function getFollowerCourse($hash)
    {
        $id = PseudoCrypt::unhash($hash);

        $isAuthor = (bool)Course::where('id', (int)$id)
            ->where('owner', Auth::user()->id)->count();

        if ($isAuthor) {
            $course = Course::find($id);
        } else {
            $course = Course::with('buyers')->where('id', (int)PseudoCrypt::unhash($hash))
                ->whereHas('buyers', function ($q) {
                    $q->where('user_id', Auth::user()->id);
                })
                ->first();

            if(!$course){
                return 'wait';
            }

            $expired_at = $course->buyers()->find(Auth::user()->id)->pivot->expired_at;

            if ($expired_at < Carbon::now()) {
                return 'expired';
            }
        }
        return $course;
    }

    public function getLesson($request, $course)
    {
        if ($request->lesson != NULL and $request->lesson !== 0) {
            $lesson = Lesson::find($request->lesson);
        } else {
            $lesson = ($course->lessons()->orderBy('id', 'ASC')->first()) ?? NULL;
        }

        return $lesson;
    }

    public function getTemplate($lesson)
    {
        if ($lesson) {
            $template = '';
            $modules = $lesson->modules()->orderBy('index')->get();

            foreach ($modules as $module) {
                $template .= $module->template->html;
                $template = $this->getTags($module, $template);
            }

            if ($template != '') {
                return $template;
            } else $template = 'Урок на стадии дополнения.';
        } else $template = 'Урок на стадии дополнения.';
    }

    protected function getTags($module, $template)
    {
        $i = 0;
        while ($i <= substr_count($template, '[[')) :
            $urlAudio = (isset($module->audio[$i]->url)) ? '<audio controls id="controls"><source src="'
            . $module->audio[$i]->url
            . '" type="audio/mpeg"><source src="'
            . $module->audio[$i]->url
            . '" type="audio/ogg"></audio>' : '&#160';
            $numeric = $i + 1;
            
            $template = preg_replace('#\[\[audio_' . $numeric . '\]\]#', $urlAudio, $template);
            
            $i++;
        endwhile;
       
        $i = 0;
        while ($i <= substr_count($template, '[[')) :
            $urlImage = (isset($module->image[$i]->url)) ? '<img src="' . $module->image[$i]->url . '">' : '&#160';
                $numeric = $i + 1;
                
                $template = preg_replace('#\[\[image_' . $numeric . '\]\]#', $urlImage, $template);
                
            $i++;
        endwhile;
       
        $i = 0;
        while ($i <= substr_count($template, '[[')) :
            $urlVideo = (isset($module->video[$i]->iframe)) ? $module->video[$i]->iframe : '&#160';
            $numeric = $i + 1;
            
            $template = preg_replace('#\[\[video_' . $numeric . '\]\]#', $urlVideo, $template);

            $i++;
        endwhile;

        $i = 0;
        while ($i <= substr_count($template, '[[')) :
            $text = (isset($module->text[$i]->text)) ? $module->text[$i]->text : '&#160';
            $numeric = $i + 1;
            
            $template = preg_replace('#\[\[text_' . $numeric . '\]\]#', $text, $template);
            
            $i++;
        endwhile;
        return $template;
    }
}
