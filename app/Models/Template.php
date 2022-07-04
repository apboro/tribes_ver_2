<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $guarded = [];

    function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function getTagsList()
    {
        $arr = str_split($this->html);
        $record = false;
        $tag = '';
        $tags = [];
        foreach($arr as $key => $symb){
            if($arr[$key] == '[' && $arr[$key + 1] == '[' ){
                $record = true;
            }
            if ($arr[$key] === ']' && $arr[$key + 1] === ']' ) {
                $record = false;
                $tags[] = trim($tag, '[,]');
                $tag = '';

            }
            if ($record) {
                $tag .= $arr[$key + 1];
            }
        }

        return $tags;
    }
}
