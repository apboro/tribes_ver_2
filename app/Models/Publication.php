<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;
use App\Events\BuyPublicaionEvent;

class Publication extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const BUY_EXPIRATION = 365;
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->setAttributeUuid();
        });
    }

    public function setAttributeUuid()
    {
        $this->attributes['uuid'] = Str::uuid();
    }

    public function parts(): HasMany
    {
        return $this->hasMany(PublicationPart::class);
    }

    public function favourites()
    {
        return $this->hasMany(FavouritePublication::class);
    }

    public function visited()
    {
        return $this->hasMany(VisitedPublication::class);
    }

    public function lastVisit(int $user_id)
    {
        return $this->hasMany(VisitedPublication::class)->where('user_id', $user_id)->first();
    }

    public function isFavourite(int $user_id)
    {
        return $this->hasMany(FavouritePublication::class)->where('user_id', $user_id)->first();
    }

    function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function feedbacks()
    {
        return $this->hasMany(LMSFeedback::class);

    }

    public static function getVisitedUserIdList(int $authorId): array
    {
        $publicationIdList = self::where('author_id', '=', $authorId)->pluck('id')->toArray();

        return VisitedPublication::WhereIn('publication_id', $publicationIdList)->pluck('user_id')->toArray();
    }

    public static function actionAfterPayment($payment)
    {
        $user = $payment->payer;
        $publication = Publication::find($payment->payable_id);
        $user->publications()->attach($publication->id, [
            'cost' => $publication->price === null ? 0 : $publication->price,
            'byed_at' => Carbon::now(),
            'expired_at' => Carbon::now()->addDays(self::BUY_EXPIRATION),
        ]);
        Event::dispatch(new BuyPublicaionEvent($publication, $user));
    }
}
