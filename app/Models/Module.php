<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
//        static::addGlobalScope('owned', function (Builder $builder) {
//            $builder->whereHas('lesson', function($q){
//                $q->whereHas('course', function($q){
//                    $q->where('owner', Auth::user()->id);
//                });
//            });
//        });
    }

    public function getRequiresByTemplate()
    {
        $template = $this->template()->first();
        return $template->getTagsList();
    }

    function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    function text()
    {
        return $this->belongsToMany(Text::class, 'module_text', 'module_id', 'text_id');
    }

    function video()
    {
        return $this->belongsToMany(File::class, 'module_video', 'module_id', 'file_id');
    }

    function image()
    {
        return $this->belongsToMany(File::class, 'module_image', 'module_id', 'file_id');
    }

    function audio()
    {
        return $this->belongsToMany(File::class, 'module_audio', 'module_id', 'file_id');
    }

}
