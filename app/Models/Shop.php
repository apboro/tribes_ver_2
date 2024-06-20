<?php

namespace App\Models;

use App\Exceptions\Invalid;
use App\Models\Shop\ShopSafeRoute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\hasOneThrough;
use Illuminate\Database\Query\Builder;
use App\Models\Traits\HasFilter;
use App\Models\User\UserLegalInfo;

class Shop extends Model
{
    use HasFactory, HasFilter;

    public $guarded = ['buyable'];

    public const LIMIT_SHOW_DEFAULT = 10;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getOwnerTg()
    {
        return $this->user->telegramMeta->first();
    }

    private static function getFilterRules(): array
    {
        return [
            'name' => ['field' => 'name', 'sql' => 'ilike'],
            'userId' => ['field' => 'user_id', 'sql' => '='],
            'shop_ids' => ['field' => 'id', 'sql' => 'in'],
        ];
    }

    public static function findByFilter(array $filter): Collection
    {
        return self::addFilter($filter, self::getFilterRules())
            ->orderByDesc('id')
            ->offset($filter['offset'] ?? 0)
            ->limit($filter['limit'] ?? self::LIMIT_SHOW_DEFAULT)
            ->get();
    }

    public static function countByFilter(array $filter): int
    {
        return self::addFilter($filter, self::getFilterRules())->count();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public static function findWithUsersAndProducts(): Collection
    {
        return self::with(['user', 'products'])->orderBy('user_id')->get();
    }
    
    public function unitpayKey(): HasOne
    {
        return $this->hasOne(UnitpayKey::class);
    }

    public function insertUnitpayKey(?string $projectId, ?string $secretKey): bool
    {
        return (bool) $this->unitpayKey()->insert(['shop_id' => $this->id, 'project_id' => $projectId, 'secretKey' => $secretKey]);
    }

    public function legalInfo(): hasOneThrough
    {
        return $this->hasOneThrough(UserLegalInfo::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    public function safeRoute(): HasOne
    {
        return $this->hasOne(ShopSafeRoute::class);
    }

    /**
     * @param int $shopId
     *
     * @return Shop|Model|HasMany|object
     *
     * @throw UnexpectedValueException
     */
    public function getSafeRoute(): ShopSafeRoute
    {
        return $this->safeRoute()->first() ?? Invalid::NotFound();
    }
        
    public function visitedProductsByTgUser(int $telegramId): ?VisitedProduct
    {
        return $this->hasOne(VisitedProduct::class)->where('telegram_id', $telegramId)->withDefault()->firstOrNew(['telegram_id' => $telegramId]);
    }

    public function setBuyable(bool $value = false): bool
    {
        $this->buyable = $value;
        
        return $this->save();
    }

    public static function buildShortShopLink(int $shopId): string
    {
        $botName = config('telegram_bot.bot.botName');
        $part = '';
        if($botName !== 'SpodialBot' ) {
            $part = 'd/';
        }

        return  'https://spdl.shop/' . $part  . $shopId;
    }

    public static function buildTgShopLink(int $shopId): string
    {
        $botName = config('telegram_bot.bot.botName');
        $marketName = config('telegram_bot.bot.marketName');

        return  'https://t.me/' . $botName . '/' . $marketName . '?startapp=' . $shopId;
    }

    public static function findUnitpayMetatag(int $shopId): string
    {
        return Shop::firstOrNew(['id' => $shopId])->unitpayKey()->first()->metatag ?? '';
    }

    public function yookassaKey(): HasOne
    {
        return $this->hasOne(YookassaKey::class);
    }

    public function robokassaKey(): HasOne
    {
        return $this->hasOne(RobokassaKey::class);
    }

    public function getPaymentSystems(): array
    {
        $paymentSystems = config('payments.banksForShopOrder');
        $paymentSystemNames = config('payments.banksNames');
        $result = [];
        
        foreach ($paymentSystems as $name => $class) {
            $isConnect = (method_exists($class, 'isWorkWithShop')) ? $class::isWorkWithShop($this) : false;
            $result[] = ['name' => $name,
                        'brand' => $paymentSystemNames[$name] ?? $name,
                        'connect' => $isConnect,
                        'active' => $this->payment_system  === $name];
        }

        return $result;
    } 

    public function isWorkWithPaymentSysytem(string $name): bool
    {
        $paymentSystems = $this->getPaymentSystems();
        foreach ($paymentSystems as $paymentSystem) {
            if ($paymentSystem['name'] === $name) {
                return $paymentSystem['connect'];
            }
        }

        return false;
    } 

    public function changeProductsBuyable(bool $buyable): void
    {
        $this->products()->update(['buyable' => $buyable]);
    }
}
