<?php

namespace App\Models;

use Database\Factories\TelegramConnectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method TelegramConnectionFactory factory()
 * @property mixed $id
 */
class TelegramConnection extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*protected static function newFactory()
    {
        return new TelegramConnectionFactory();
    }*/

    public function community()
    {
        return $this->hasOne(Community::class, 'connection_id', 'id');
    }

}
