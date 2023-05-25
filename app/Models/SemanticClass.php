<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $class_id
 * @property string $class_name
 * @property string $class_probability
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SemanticClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'class_name',
        'class_probability',
    ];
}
