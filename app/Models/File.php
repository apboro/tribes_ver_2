<?php

namespace App\Models;

use App\Traits\Modulable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *@property $url
 */
class File extends Model
{
    use HasFactory, Modulable;

    protected $guarded = [];

    public function normalizeSize()
    {
        return round($this->size / 1024, 2) . " KB";
    }

    public function normalizeMime()
    {
        return trim(stristr($this->mime, '/'), '/');
    }

    public function getName()
    {
        return $this->description;
    }

    function module()
    {
        return $this->belongsToMany(Module::class, 'module_image', 'file_id', 'module_id');
    }

    function courses()
    {
        return $this->belongsToMany(Course::class, 'course_attachments');
    }

    function getUrl()
    {
        return $this->url;
    }

    function getPath()
    {
        return str_replace('/storage', '', $this->getUrl());
    }
}
