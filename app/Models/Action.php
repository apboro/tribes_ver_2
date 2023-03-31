<?php

namespace App\Models;

use App\Services\TelegramMainBotService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Action extends Model
{
    use HasFactory;

    protected $guarded=[];
    public $timestamps=false;

    protected $hidden=['created_at', 'updated_at'];


    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class, 'group_uuid', 'group_uuid');
    }


}
