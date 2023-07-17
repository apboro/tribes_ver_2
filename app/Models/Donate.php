<?php

namespace App\Models;


use App\Filters\QueryFilter;
use Database\Factories\DonateFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;


/**
 * @method DonateFactory factory()
 * @property int $id
 * @property mixed $command
 * @property mixed $title
 * @property mixed $user_id
 * @property mixed $image
 * @property mixed $description
 * @property mixed $donate_is_active
 *
 */
class Donate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static $currency = [
        'rub' => 0,
        'dollar' => 1,
        'euro' => 2,
    ];

    public static $currency_labels = [
        'rub' => '₽',
        'dollar' => '$',
        'euro' => '€',
    ];

    public static $baseData = [
        'main_image_id' => 0,
        'prompt_image_id' => 0,
        'success_image_id' => 0,
    ];

    protected $casts = [
        'isSendToCommunity' => 'boolean',
    ];

    protected $with = ['variants'];

    function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    function variants()
    {
        return $this->hasMany(DonateVariant::class, 'donate_id', 'id');
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, DonateVariant::class, 'donate_id', 'payable_id');
    }

    function getSumDonateByIndex()
    {
        $ids = $this->variants()->pluck('id')->all();
        $payments = Payment::select('add_balance')
            ->whereIn('payable_id', $ids)
            ->where('type', 'donate')
            ->where('status', 'CONFIRMED')
            ->get();
        $sum = [];
        foreach ($payments as $payment) {
            $sum[] = $payment->add_balance;
        }
        return $sum;
    }

    function getVariantByIndex($index)
    {
        $variants = $this->variants()->get();

        if(!isset($variants[$index])){
            $variants[$index] = DonateVariant::create([
                'donate_id' => $this->id,
                'isActive' => false
            ]);
        }

        return $variants ? $variants[$index] : false;
    }

    static function getCurrencyData($donate, $variant_index)
    {
        $res = [];
        foreach( self::$currency as $val => $index){
            $res[$index]['value'] = $val;
            $res[$index]['selected'] = $donate && $donate->getVariantByIndex($variant_index) && $donate->getVariantByIndex($variant_index)->currency == $index;
            $res[$index]['title'] = self::$currency_labels[$val];
        }
        return $res;
    }

    public function getPromptTime()
    {
        return $this->prompt_at_hours . ':' . $this->prompt_at_minutes;
    }

    public function getMainImage()
    {
        return $this->belongsTo(File::class,'main_image_id')->first();
    }

    public function getPromptImage()
    {
        return $this->belongsTo(File::class,'prompt_image_id')->first();
    }

    public function getSuccessImage()
    {
        return $this->belongsTo(File::class,'success_image_id')->first();
    }

    public function getDonateMainImage()
    {

        $image = $this->getMainImage();
        if ($image) {
            return $image->url;
        }
        return '/images/thanks.jpg';
    }

    public function getDonateMainDescription()
    {
        if($this->description){
            return $this->description;
        }
        return 'Без комментария';
    }
    
    public function checkForNonStaticVariant(): bool
    {
        $status = false;
        foreach ($this->variants()->get() as $variant) {
            if ($variant->isStatic == false and $variant->isActive == true)
                $status = true;
        }
        return $status;
    }

    public function scopeOwned($query)
    {
        return $query->where('user_id', '=', Auth::user()->id);
    }

    public function scopeActive($query)
    {
        return $query->where('donate_is_active', true);
    }

    public function getDonatePaymentLink($data = null)
    {
        $params = '';
        if ($data && is_array($data)) {
            $params = '?' . http_build_query($data);
        }
        return route('donate.process') . $params;
    }
    public function getDonatePaymentLinkRandom($data = null)
    {
        $params = '';
        if ($data && is_array($data)) {
            $params = '?' . http_build_query($data);
        }
        return config('app.frontend_url').'/app/public/monetization/arbitrary' . $params;
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function getRandomSumVariant()
    {
        return $this->variants()->where('variant_name', 'random_sum')->first();
    }


}
