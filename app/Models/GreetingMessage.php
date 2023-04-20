<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property mixed $text
 * @property mixed $user_id
 * @property array|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|mixed|null $image
 * @property mixed $id
 */
class GreetingMessage extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'id'];

}
