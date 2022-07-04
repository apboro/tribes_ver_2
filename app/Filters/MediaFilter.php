<?php


namespace App\Filters;


use Illuminate\Support\Carbon;

class MediaFilter extends QueryFilter
{

    private $assoc = [
        'date' => 'created_at',
        'access' => 'access_days',
        'cost' => 'cost',
        ];

    public function sort($sort)
    {
        $sort = $this->assoc[$sort];
        $dir = request()->get('dir');

        if($dir !== 'asc'){
            $dir = 'desc';
        }

        return $this->builder->orderBy($sort, $dir);
    }

//    public function date($dir = 'asc')
//    {
//        $sort = 'created_at';
//        if($dir !== 'asc'){
//            $dir = 'desc';
//        }
//
//        return $this->builder->orderBy($sort, $dir);
//    }
//
//    public function access($dir = 'asc')
//    {
//        $sort = 'access_days';
//        if($dir !== 'asc'){
//            $dir = 'desc';
//        }
//
//        return $this->builder->orderBy($sort, $dir);
//    }
//
//    public function cost($dir = 'asc')
//    {
//        $sort = 'cost';
//        if($dir !== 'asc'){
//            $dir = 'desc';
//        }
//
//        return $this->builder->orderBy($sort, $dir);
//    }

//    public function date($date)
//    {
//        return $this->builder->whereHas('payment', function ($q) use($date) {
//            $q->whereDate('created_at', $date);
//        });
//    }
}