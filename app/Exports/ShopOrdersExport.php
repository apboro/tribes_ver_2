<?php

namespace App\Exports;

use App\Models\Market\ShopOrder;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShopOrdersExport extends AbstractExport implements FromArray, ShouldAutoSize, WithHeadings
{
    public int $shopId;

    protected string $fileName = "orders";

    private array $orderHeadings = [
        '#',
        'Created',
        'Quantity',
        'Status',
        'Address',
        'Telegram ID',
        'Phone',
        'Sum',
        'Delivery Sum',
    ];

    private array $productHeadings = [
        'Product ID',
        'Product Name',
        'Product Category',
        'Product Size',
        'Product Price',
        'Product Quantity',
    ];

    public function __construct(int $shopId)
    {
        $this->shopId = $shopId;
    }

    public function headings(): array
    {
        return array_merge($this->orderHeadings, $this->productHeadings);
    }

    public function array(): array
    {
        $orders = ShopOrder::where('shop_id', $this->shopId)->get();

        $res = [];

        /** @var ShopOrder $order */
        foreach ($orders as $order) {
            $res[] = [
                $order->id,
                $order->created_at,
                $order->orderProducts()->sum('quantity'),
                ShopOrder::STATUS_NAME_LIST[$order->status],
                $order->delivery->address,
                $order->telegram_user_id,
                $order->delivery->phone,
                $order->getPrice(),
                $order->delivery->calcDelivery(),
            ];

            $res = array_merge($res, $this->getProductRows($order));
        }

        return $res;
    }

    private function getProductRows(ShopOrder $order): array
    {
        $res = [];

        /** @var Product $orderProduct */
        foreach ($order->orderProducts as $orderProduct) {
            $row = [
                $orderProduct->product->id,
                $orderProduct->product->title,
                $orderProduct->product->category_name,
                $orderProduct->options['size'] ?? '',
                $orderProduct->price,
                $orderProduct->quantity,
            ];

            $res[] = $this->fillColumnsInRow($row, count($this->orderHeadings), '');
        }

        return $res;
    }

    private function fillColumnsInRow(array $row, int $count, string $value): array
    {
        return array_merge(array_fill(0, $count, $value), $row);
    }
}