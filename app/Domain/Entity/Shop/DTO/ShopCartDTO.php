<?php

declare(strict_types=1);

namespace App\Domain\Entity\Shop\DTO;

use App\Domain\DTO\BaseDTO;

class ShopCartDTO extends BaseDTO
{
     public const INPUT_TELEGRAM_USER_ID = 'telegram_user_id';
     public const INPUT_SHOP_ID = 'shop_id';
     public const INPUT_PRODUCT_ID = 'product_id';
     public const INPUT_QUANTITY = 'quantity';
     public const INPUT_OPTIONS = 'options';

    public int $telegram_user_id;
    public int $shop_id;
    public int $product_id;
    public int $quantity;
    public array $options;

    public function __construct(int $telegram_user_id, int $shop_id, int $product_id, int $quantity, array $options)
    {
        $this->telegram_user_id = $telegram_user_id;
        $this->shop_id = $shop_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
        $this->setOptions($options);
    }

    public function getTelegramUserId(): int
    {
        return $this->telegram_user_id;
    }

    public function getShopId(): int
    {
        return $this->shop_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    private function setOptions(array $options): void
    {
        if ($options !== []) {
            $this->options = $options;
        }
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}