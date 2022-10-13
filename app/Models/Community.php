<?php

namespace App\Models;

use App\Filters\QueryFilter;
use App\Helper\PseudoCrypt;
use App\Models\Knowledge\Question;
use Database\Factories\CommunityFactory;
use Hamcrest\Arrays\IsArray;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/** @method CommunityFactory factory()
 * @property mixed $owner
 * @property mixed $id
 */
class Community extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'communities';

    protected $with = ['donate'];
    /**
     * @var mixed
     */
    private $tariff;

    private $followerMap = ['', 'K', 'M', 'B'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($m) {
            //todo перенести логики привязки создаваемого сообщества в сервис контроллера
            if(($au = Auth::user()) ==! null){
                $m->owner = $au->id;
            }
        });
    }

    public function scopeOwned($query)
    {
        return $query->where('owner', '=', Auth::user()->id);
    }

    /**
     *   self::filter($filters)
     */
    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function generateHash()
    {
        $this->hash = PseudoCrypt::hash($this->id, 8);
    }

    /**
     * todo при использовании магических методов происходит путаница в названиях
     *      $this->connection()
     *      $this->connection
     *      protected \Illuminate\Database\Eloquent\Model::$connection
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function connection()
    {
        return $this->belongsTo( TelegramConnection::class, 'connection_id');
    }

    public function statistic()
    {
        return $this->hasOne(Statistic::class, 'community_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'community_id', 'id');
    }

    public function getRangeDonatePaymentLink($activeVariantIndex)
    {
        $donate = $this->donate()->first();
        if($donate) {
            $variant = $donate->getVariantByIndex($activeVariantIndex);
            if ($variant) {
                if ($variant->isActive && !$variant->isStatic) {
                    return route('community.donate.form', ['hash' => $this->hash]);
                }
            }
        }
        return false;
    }

    public function getDonatePaymentLink($data = null)
    {
        $params = '';
        if($data && is_array($data)){
            $params = '?' . http_build_query($data);
        }
        $this->generateHash();
        return route('community.donate.form', ['hash' => $this->hash]) . $params;
    }

    public function getTariffPayLink($data = null)
    {
        $params = '';
        if($data && is_array($data)){
            $params = '?' . http_build_query($data);
        }
        return route('community.tariff.form', ['community' => $this]) . $params;
    }

    public function getTariffPaymentLink($data = null)
    {
        $params = '';
        if($data && is_array($data)){
            $params = '?' . http_build_query($data);
        }
//        $this->generateHash();
        return route('community.tariff.payment', ['hash' => $this->hash]) . $params;
    }

    public function isTelegram()
    {
        return $this->connection()->first() instanceof TelegramConnection;
    }

    public function isTelegramGroup()
    {
        return $this->connection()->first()->chat_type === 'group';
    }


    public function isTelegramChannel()
    {
        return $this->connection()->first()->chat_type === 'channel';
    }

    public function isOwnedByUser(User $user) : bool
    {
        return $this->owner()->first() && $this->owner()->first()->id === $user->id;
    }

    function questions()
    {
        return $this->hasMany(Question::class, 'community_id', 'id');
    }

    public function getPopularQuestionsAttribute()
    {
        $questions = $this->questions();

        if ($questions->count() === 0)
        {
            return null;
        }

        return $questions->where([
            'is_public' => true,
            'is_draft' => false,
        ])->limit(20)
            ->get()
            ->shuffle()
            ->slice(0, 5);
    }

    function tariff()
    {
        return $this->hasOne(Tariff::class, 'community_id', 'id');
    }

    public function tariffVariants()
    {
        return $this->hasManyThrough(TariffVariant::class, Tariff::class);
    }

    public function hasNotActiveTariffVariants()
    {
        
        return $this->tariffVariants()->where('isActive',1)->where('isPersonal',0)->doesntExist();
    }

    function donate()
    {
        return $this->hasMany(Donate::class, 'community_id', 'id');
    }

    function owner()
    {
        return $this->belongsTo(User::class, 'owner', 'id');
    }

    public function donateVariants()
    {
        return $this->hasManyThrough(DonateVariant::class, Donate::class);
    }

    public function getDonateMainDescription()
    {
        $donate = $this->donate()->first();
        if($donate && $donate->description){
            return $donate->description;
        }
        return 'Без комментария';
    }

    public function getDonateMainImage()
    {
        $donate = $this->donate()->first();
        if($donate){
            $image = $donate->getMainImage();
            if($image){
                return $image->url;
            }
        }
        return '/images/thanks.jpg';
    }

    public function addition($summ)
    {
        $this->update(['balance' => round($this->balance + $summ, 2)]);
        return true;
    }

    public function subtraction($summ)
    {
        $this->update(['balance' => round($this->balance - $summ, 2)]);
        return true;
    }

    function followers()
    {
        return $this->belongsToMany(TelegramUser::class, 'telegram_users_community', 'community_id', 'telegram_user_id', 'id', 'telegram_id')
            ->withPivot(['excluded', 'role','accession_date','exit_date']);
    }

    public function getCountFollowersAttribute()
    {
        $countFollowers = $this->followers()->count();
        $string = $this->getFollowerString($countFollowers);

        for ($rank = 0; $countFollowers > 999; $rank++) {
            $countFollowers = round($countFollowers / 1000, 1);
        }

        return $countFollowers . $this->followerMap[$rank] . $string;
    }

    /**
     *   Взять ссылку на публичный список вопросов сообщества
     */
    public function getPublicKnowledgeLink(): string
    {
        $hash = PseudoCrypt::hash($this->id);
        return route('public.knowledge.list', compact('hash'));
    }
    /**
     *   Взять ссылку на справку "Как это работает"
     */
    public function howItWorksLink()
    {
        $hash = PseudoCrypt::hash($this->id);
        return route('public.knowledge.help', compact('hash'));
    }

    private function getFollowerString($number)
    {
        $string = ' подписчиков';

        if ($number === 1) {
            $string = ' подписчик';
        } elseif ($number > 1 && $number < 5) {
            $string = ' подписчика';
        }

        return $string;
    }
}
