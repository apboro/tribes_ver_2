<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Filters\QueryFilter;
use App\Models\TelegramUser;
use App\Services\SMTP\Mailer;
use App\Services\TinkoffE2C;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;
use App\Models\SmsConfirmations;

/**
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property Subscription $subscription
 * @property HasMany $actions
 * @property mixed $phone
 *
 * @method UserFactory factory()
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public static $role = [
        'author' => 0,
        'follower' => 1,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'hash',
        'code',
        'phone',
        'password',
        'locale',
        'phone_confirmed',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    function getFirstLettersOfName()
    {
        $letters = '';

        $arr = explode(' ', $this->name);

        foreach ($arr as $elem_name) {
            $letters .= mb_substr($elem_name, 0, 1);
        }

        return $letters;
    }

    function setLocale($locale)
    {
        $this->update(['locale' => $locale]);
    }

    public function hashMake()
    {
        $this->hash = Hash::make($this->id); //хаха
        $this->save();
    }

    public function isConfirmed()
    {
        return $this->phone_confirmed;
    }

//    public function getFormattedPhone()
//    {
//        return $this->phone;
//    }

    public function getOwnCommunities()
    {
        return $this->communities()->get();
    }

    function getPhone()
    {
        return $this->phone ? $this->code . $this->phone : false;
    }

    function confirmation()
    {
        return $this->hasMany(SmsConfirmations::class, 'user_id');
    }

    function confirmationUserDate($format = 'time')
    {
        $time_stamp = $this->confirmation()->first() ? $this->confirmation()->first()->updated_at : $this->updated_at;

        $date = $time_stamp->translatedFormat('d F Y');
        $time = $time_stamp->format('H:i');

        return $format == 'time' ? $time : $date;
    }

    function telegramData()
    {
        return $this->telegramMeta()->get();
    }

    function hasTelegramAccount()
    {
        return $this->telegramMeta()->count();
    }

    function getTelegramAuthorizedDate()
    {
        return $this->telegramData() ? Carbon::createFromTimestamp($this->telegramData()->auth_date)->toDateTimeString() : null;
    }

    function telegramMeta()
    {
        return $this->hasMany(TelegramUser::class, 'user_id', 'id');
    }

    function communities()
    {
        return $this->hasMany(Community::class, 'owner', 'id');
    }

    public function hasCommunities()
    {
        return $this->communities()->exists();
    }

    function projects()
    {
        return $this->hasMany(Project::class, 'user_id', 'id');
    }

    function accumulation()
    {
        return $this->hasMany(Accumulation::class, 'user_id', 'id');
    }

    function tinkoffSync()
    {
        $e2c = new TinkoffE2C();
        $e2c->AddCustomer(env('TINKOFF_PREFIX') . '_user_' . $this->id);
    }

    public function getCustomerKey(): string
    {
        return env('TINKOFF_PREFIX') . '_user_' . $this->id;
    }

    function getActiveAccumulation()
    {
        $accumulation = $this->accumulation()
            ->where('ended_at', '>', Carbon::now()->toDateTimeString())
            ->where('started_at', '<', Carbon::now()->toDateTimeString())
            ->where('status', 'active')
            ->latest('created_at')
            ->first();

        return $accumulation;
    }


    function getBalance($rubbles = true)
    {
        $amount = $this->accumulation()
            ->where('status', 'active')
            ->sum('amount');

        return $rubbles ? $amount / 100 : $amount;
    }

    function getTribesCommission()
    {
        return (int)(UserSettings::findByUserId($this->id)->get('percent')->value ?? env('TRIBES_COMMISSION', 4));
    }

    public function sendPasswordResetNotification($token)
    {
        $v = view('mail.reset')->with(
            [
                'token' => $token,
                'email' => $this->email,
                'ip' => request()->ip(),
            ])->render();

        new Mailer('Сервис ' . env('APP_NAME'), $v, 'Восстановление пароля', $this->email);
    }

    function course()
    {
        return $this->hasMany(Course::class, 'owner', 'id');
    }

    function tariffVariant()
    {
        return $this->belongsToMany(TariffVariant::class, 'users_tarif_variants', 'user_id', 'tarif_variants_id')->withPivot(['days', 'prompt_time']);
    }

    function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id')->withPivot(['byed_at', 'expired_at']);
    }

    public function phoneNumber($phone)
    {
        return "(" . substr($phone, 0, 3) . ") " . substr($phone, 3, 3) . " " . substr($phone, 6);
    }

//    public function getPhoneAttribute($phone)
//    {
//        return $phone ? '+7 ' . $this->phoneNumber($phone) : null;
//    }

    public function isAdmin()
    {
        return Administrator::where('user_id', $this->id)->exists();
    }

    public function administrator()
    {
        return $this->hasOne(Administrator::class, 'user_id', 'id');
    }

    public function createTempToken()
    {
        $token = $this->createToken('api-token');
        return $this->withAccessToken($token->plainTextToken)
            ->setTempToken($token->plainTextToken);
    }

    private function setTempToken($token) //todo при переходе на FullRest - Удалить
    {
        $this->api_token = $token;
        Session::put('current_token', $token);
        return $this->save();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function subscription()
    {
        return $this->hasOne(UserSubscription::class, 'user_id', 'id' );
    }

    public function actions(): HasMany
    {
        return $this->hasMany(Action::class, 'user_id', 'id');
    }

    public function connections(): HasMany
    {
        return $this->hasMany(TelegramConnection::class, 'user_id', 'id');
    }
}

