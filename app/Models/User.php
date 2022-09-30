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
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;
use App\Models\SmsConfirmations;

/**
 * @method UserFactory factory()
 * @property mixed $id
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public static $role = [
        'author' => 0,
        'follower' => 1
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

        foreach ($arr as $elem_name){
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

    public function getFormattedPhone()
    {
        return $this->phone;
    }

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
        $time_stamp = $this->confirmation()->first()->updated_at;

        $date = $time_stamp->translatedFormat('d F Y');
        $time = $time_stamp->format('H:i');

        return $format == 'time' ? $time : $date;
    }

    function telegramData()
    {
        return $this->telegramMeta()->first();
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
        return $this->hasOne(TelegramUser::class, 'user_id', 'id');
    }

    function communities()
    {
        return $this->hasMany(Community::class, 'owner', 'id');
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

    function getCustomerKey()
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
        return (int) (UserSettings::findByUserId($this->id)->get('percent')->value ?? env('TRIBES_COMMISSION',4));
    }

    public function sendPasswordResetNotification($token)
    {
        $v = view('mail.reset')->with(
            [
                'token' => $token,
                'email' => $this->email,
                'ip' => request()->ip()
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
        return "(".substr($phone, 0, 3).") ".substr($phone, 3, 3)." ".substr($phone,6);
    }

    public function getPhoneAttribute($phone)
    {
        return $phone ? '+7 ' . $this->phoneNumber($phone) : '-';
    }

    public function isAdmin()
    {
        return Administrator::where('user_id',$this->id)->exists();
    }

    public function administrator()
    {
        return $this->hasOne(Administrator::class, 'user_id','id');
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
        Session::put('current_token',$token);
        return $this->save();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class,'user_id');
    }
}

63	534	0	21:17	2022-09-04 21:17:37	NULL	f
70	545	27	22:12	2022-09-27 22:12:30	NULL	t
70	558	9	13:14	2022-09-09 13:14:11	NULL	t
105	556	87	21:54	2022-09-27 21:54:48	NULL	t
70	566	13	16:30	2022-09-09 16:30:15	NULL	t
70	543	16	10:10	2022-09-16 10:10:37	NULL	t
70	589	10	22:02	2022-09-10 22:02:40	NULL	t
70	587	10	15:30	2022-09-10 15:30:19	NULL	t
70	590	11	09:43	2022-09-11 09:43:16	NULL	t
71	591	72	10:09	2022-09-12 10:09:36	NULL	t
70	605	15	17:59	2022-09-15 17:59:31	NULL	t
71	619	80	18:19	2022-09-20 18:19:12	NULL	t
70	597	12	14:46	2022-09-12 14:46:52	NULL	t
70	607	15	19:02	2022-09-15 19:02:17	NULL	t
70	599	13	08:43	2022-09-13 08:43:57	NULL	t
70	616	20	12:51	2022-09-20 12:51:24	NULL	t
70	588	10	18:34	2022-09-10 18:34:49	NULL	t
70	681	29	07:39	2022-09-29 07:39:41	NULL	t
70	688	29	15:37	2022-09-29 15:37:20	NULL	t
71	694	90	16:11	2022-09-30 16:11:57	NULL	t

