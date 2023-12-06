<?php

namespace App\Repositories\Product;

use App\Http\ApiRequests\Product\ApiProductUpdateRequest;
use App\Http\ApiRequests\Product\ApiProductStoreRequest;
use App\Http\ApiResources\Product\ProductResource;
use App\Http\ApiResponses\ApiResponse;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductRepository
{

    public function showList($request): ApiResponse
    {
        $filter = $request->validated();
        $products = Product::findByFilter($filter);
        $count = Product::countByFilter($filter);

        return ApiResponse::listPagination(
            ['Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items((ProductResource::collection($products))->toArray($request));
    }

    public function store(ApiProductStoreRequest $request): Product
    {
        return Product::create(['author_id' => $request->authorId] + $this->fillProduct($request));
    }

    public function update(ApiProductUpdateRequest $request, int $id): Product
    {
        $product = Product::findByIdAndAuthorId($id, $request->authorId)->fill($this->fillProduct($request));
        $product->save();

        return $product;
    }

    private function fillProduct($request): array
    {
        $image = $request->file('image') ? Storage::disk('public')->putFile('product_images', $request->file('image')) : null;
        $productArray = [
            'description' => $request->input('description') ?? null,
            'title' => $request->input('title'),
            'price' => $request->input('price')
        ];
        if ($request->exists('image')) {
            $productArray = array_merge($productArray, ['image' => $image]);
        }

        return $productArray;
    }
}