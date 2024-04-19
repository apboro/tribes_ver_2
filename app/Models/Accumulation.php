<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\AccumulationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 *  @method AccumulationFactory factory()
 *
 * @property amount
 */
class Accumulation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const OVERDUE_HOURS = 24;

    public function scopeOwned($query)
    {
        return $query->where('user_id', '=', Auth::user()->id);
    }

    public function addition($summ)
    {
        $this->update(['amount' => $this->amount + $summ]);
        return true;
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

    /**
     * Возвращает сумму денег в копилках пользователя $userId
     */
    public static function getSumByUser(int $userId): int
    {
        return self::select(DB::raw('sum(amount) as amount'))
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first()
            ->amount ?? 0;
    }

    /**
     * Возвращает список активных копилок пользователя $userId
     */
    public static function findActiveAccumulations(int $userId)
    {
        return self::where('user_id', $userId)
                            ->where('status', 'active')
                            ->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Закрывает копилку
     */
    public function close(): self
    {
        $this->status = 'closed';
        $this->save();

        return $this;
    }

    /**
     * Возвращает активную копилку по SpAccumulationId
     */
    public static function findAccumulation(int $SpAccumulationId): ?self
    {
        return self::where('SpAccumulationId', $SpAccumulationId)
                    ->where('status', 'active')
                    ->first();
    }

    /**
     * Возвращает активную копилку пользователя
     */
    public static function findUsersAccumulation(int $userId, ?string $type = null): ?self
    {
        return self::where('user_id', $userId)
                            ->where('status', 'active')
                            ->where('type', $type)
                            ->where('ended_at', '>', Carbon::now()->toDateTimeString())
                            ->latest('created_at')
                            ->first();
    }

    /**
     * Проверка существования копилки SpAccumulationId без учета активности
     */
    public static function isAccumulationExists(int $SpAccumulationId): bool
    {
        return self::where('SpAccumulationId', $SpAccumulationId)->count() ? true : false;
    }

    /**
     * Проверка существования копилки SpAccumulationId без учета активности
     */
    public static function newAccumulation(int $userId, int $SpAccumulationId, int $endedDays = 0, ?string $type = null): self
    {
        if ($endedDays) {
            $ended = Carbon::now()->addMonth();
        } else {
            $ended = Carbon::now()->addDays($endedDays); 
        }

        return self::create([
            'user_id' => $userId,
            'SpAccumulationId' => $SpAccumulationId,
            'started_at' => Carbon::now(),
            'ended_at' => $ended,
            'status' => 'active',
            'type' => $type,
        ]);
    }

    public static function findNeedClose(): Collection
    {
        return self::where('status', 'active')
                    ->where('ended_at', '>=', Carbon::now()->subHours(self::OVERDUE_HOURS))
                    ->where('ended_at', '<=', Carbon::now())
                    ->get();
    }
}
