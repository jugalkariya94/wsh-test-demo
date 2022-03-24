<?php

namespace App\Services;


use App\Repositories\BookRepository;
use Illuminate\Http\Request;

// Resources and Collections
use Illuminate\Http\Response;

class BookService
{
    protected BookRepository $repository;
    protected ShopifyAPIService $shopifyAPIService;

    public function __construct(BookRepository $bookRepository, ShopifyAPIService $shopifyAPIService)
    {
        $this->repository = $bookRepository;
        $this->shopifyAPIService = $shopifyAPIService;
    }

    public function save(array $data)
    {
        $book = $this->repository->firstOrNew($data['id'] ?? null);
        $book->fill($data);


        // Create book product over shopify
        $productData = ['product' =>
            [
                'title' => $data['name'],
                'body_html' => $data['description'],
            ]
        ];
        $productResponse = $this->shopifyAPIService->createProduct($productData);
        $shopifyProductID = $productResponse->get('id');


        // Update default variant for managing price
        $shopifyProductVariantID = $productResponse->get('variants')[0]['id'];
        $variantData = ['variant' =>
            [
                'id' => $shopifyProductVariantID,
                'price' => $data['price'],
                'compare_at_price' => $data['compare_at_price'],
            ]
        ];
        $variantResponse = $this->shopifyAPIService->updateProductVariant($shopifyProductVariantID, $variantData);

        // upload the selected image for the product
        $imageData = ['image' =>
            [
                'attachment' => base64_encode(file_get_contents($data['image'])),
            ]
        ];
        $imageResponse = $this->shopifyAPIService->createProductImage($shopifyProductID, $imageData);

        // Override the null shopify id with ID from api
        $book->shopify_id = $shopifyProductID;

        //Save book object
        $book->save();

        return $book;

    }
}
