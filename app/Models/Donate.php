<?php

namespace App\Models;


use Database\Factories\DonateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @method DonateFactory factory()
 * @property int $id
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

    function getSumDonateByIndex()
    {
        $payments = Payment::select('type', 'add_balance')->where('community_id', $this->community->id)->where('type', '!=', 'tariff')->get();
        $sum = [];
        foreach ($payments as $payment) {
            $index = $payment->type;
            $new_index = preg_replace("/[a-zA-Zа-яА-Я-]/", "", $index);
            if ($new_index == $this->index) {
                $sum[] = $payment->add_balance;
            }
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
        return '/images/no-image.svg';
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

}
