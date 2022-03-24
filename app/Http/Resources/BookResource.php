<?php

namespace App\Http\Resources;

use App\Services\ShopifyAPIService;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $shopifyProductData = (new ShopifyAPIService)->getProductDetails($data['shopify_id']);
        $data['price'] = $shopifyProductData['variants'][0]['price'];
        $data['title'] = $shopifyProductData['title'];
        $data['compare_at_price'] = $shopifyProductData['variants'][0]['compare_at_price'];
        $data['image'] = $shopifyProductData['image']['src'] ?? '';
        return $data;
    }
}
