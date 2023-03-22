<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionAction extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $table='conditions_actions';
}
