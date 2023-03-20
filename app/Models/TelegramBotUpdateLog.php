<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotUpdateLog extends Model
{
    use HasFactory;
    protected $fillable = ['data'];
}
