<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @method ProjectFactory factory()
 * @method Builder filter()
 * @property string $title
 */
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title'
    ];

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function communities()
    {
        return $this->belongsTo(Community::class,  'id','project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}