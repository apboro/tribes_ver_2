<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed $condition_id
 * @property mixed $id
 * @property mixed $group_uuid
 */
class ConditionAction extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $table='conditions_actions';

    public function action(): HasOne
    {
        return $this->hasOne(Action::class,'group_uuid','group_uuid');
    }

}
