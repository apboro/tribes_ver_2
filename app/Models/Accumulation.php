<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\AccumulationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 *  @method AccumulationFactory factory()
 */
class Accumulation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeOwned($query)
    {
        return $query->where('user_id', '=', Auth::user()->id);
    }

    public function addition($summ)
    {
        $this->update(['amount' => $this->amount + $summ]);
        return true;
    }

    function getTribesCommission()
    {
        return UserSettings::findByUserId($this->user_id)->get('percent')->value ?? env('TRIBES_COMMISSION',4);
    }

    public function subtraction($summ)
    {
        $this->update(['amount' => $this->amount - $summ]);
        return true;
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'SpAccumulationId', 'SpAccumulationId');
    }

    public function getEndedAtAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getStartedAtAttribute($value)
    {
        return Carbon::parse($value);
    }
}
