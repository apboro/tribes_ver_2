<?php

namespace App\Services\Shop;

use App\Models\Product;
use App\Models\Shop;
use App\Models\TelegramUser;

class ShopReport
{
    private function prepareTableHeader(): array
    {
        return [
            0 => [
                'shop.name',
                'product.quantity',
                'shop.created_at',
                'user.name',
                'user.email',
                'user.created_at',
                'telegram.userName',
                'telegram.firstName',
                'telegram.lastName',
                //'product.title',
                //'product.price',
                //'product.created_at'
            ]
        ];
    }

    private function prepareShopRow(Shop $shop, ?TelegramUser $ownerTg): array
    {
        return [
            $shop->name ?? '',
            $shop->products->count(),
            $shop->created_at ?? '',
            $shop->user->name,
            $shop->user->email,
            $shop->user->created_at->toDayDateTimeString(),
            $ownerTg->user_name ?? '',
            $ownerTg->first_name ?? '',
            $ownerTg->last_name ?? '',
        ];
    }

    private function prepareProductRow(Product $product): array
    {
        return [
            '', '', '', '', '', '', '', '', '',
            $product->title,
            $product->price,
            $product->created_at->toDayDateTimeString()
        ];
    }

    public function prepareTable(): array
    {
        $tableCels = $this->prepareTableHeader();
        $currentUserId = 0;
        $shops = Shop::findWithUsersAndProducts();

        foreach ($shops as $shop) {
            if ($currentUserId != $shop->user_id) {
                $currentUserId = $shop->user_id;
                $ownerTg = $shop->getOwnerTg();
            }

            $tableCels[] = $this->prepareShopRow($shop, $ownerTg);

            //foreach ($shop->products as $product) {
            //    $tableCels[] = $this->prepareProductRow($product);
            //}
        }

        return $tableCels;
    }
}
