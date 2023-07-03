<?php

namespace App\Models;

use App\Http\Requests\Community\DonateRequest;
use App\Traits\Authorable;
use Database\Factories\DonateVariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @method DonateVariantFactory factory() */
class DonateVariant extends Model
{
    use HasFactory, Authorable;

    protected $guarded = [];

    function author()
    {
        return $this->donate()->first()->owner()->first();
    }

    function donate()
    {
        return $this->belongsTo(Donate::class, 'donate_id');
    }

    public function community()
    {
        return $this->donate()->first()->community()->first();
    }

    public function isCurrency($val)
    {
        return $this->currency === Donate::$currency[$val];
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

}
