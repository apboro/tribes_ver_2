<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitpayKey extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'shop_id';
    public $fillable = ['shop_id', 
                        'project_id', 
                        'secretKey',
                        'metatag'];

    public static function isKeysUsed(int $projectId, int $secretKey, ?int $shopIdExclude = null): bool
    {
        return self::where([
                        ['shop_id', '!=', $shopIdExclude],
                        ['project_id', '=', $projectId],
                        ['secretKey', '=', $secretKey],
                        ])->exists();
    }
}