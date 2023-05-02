<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $owner_id
 */
class QuestionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id'
    ];
}
