<?php

namespace App\Models;

use App\Traits\Modulable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    use HasFactory, Modulable;

    protected $guarded = [];

    function module()
    {
        return $this->belongsToMany(Module::class, 'module_text', 'text_id', 'module_id');
    }
    function getSource(){
        return $this->text;
    }
}
