<?php

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

// Resources and Collections
use Illuminate\Http\Response;

class ShopifyAPIService extends ShopifyAPIClient
{
    /**
     * get a product from Shopify
     * @param $data | array
     * @return mixed
     */
    public function createProduct(array $data)
    {
        $response = $this->request(Request::METHOD_POST, 'products.json', $data);
        if(isset($response['errors']) && $response['errors']) {
            return collect($response['errors']);
        } else {
            return collect($response['resource']);
        }
    }

    /**
     * create a product variant on Shopify
     * @param string $productID
     * @param array $data | array
     * @return mixed
     */
    public function createProductVariant(string $productID, array $data)
    {
        $response = $this->request(Request::METHOD_POST, 'products/'.$productID.'/variants.json', $data);
        if(isset($response['errors']) && $response['errors']) {
            return collect($response['errors']);
        } else {
            return collect($response['resource']);
        }
    }

    /**
     * update a product variant on Shopify
     * @param string $variantID
     * @param array $data | array
     * @return mixed
     */
    public function updateProductVariant(string $variantID, array $data)
    {
        $response = $this->request(Request::METHOD_PUT, 'variants/'.$variantID.'.json', $data);
        if(isset($response['errors']) && $response['errors']) {
            return collect($response['errors']);
        } else {
            return collect($response['resource']);
        }
    }

    /**
     * create a product image on Shopify
     * @param string $productID
     * @param array $data | array
     * @return mixed
     */
    public function createProductImage(string $productID, array $data)
    {
        $response = $this->request(Request::METHOD_POST, 'products/'.$productID.'/images.json', $data);
        if(isset($response['errors']) && $response['errors']) {
            return collect($response['errors']);
        } else {
            return collect($response['resource']);
        }
    }

    /**
     * get all products from Shopify
     * @return mixed
     */
    public function getAllProducts()
    {
        $products = [];
        $nextPageToken = null;
        do {
            $response = $this->request(Request::METHOD_GET, 'products.json?page_info=' . $nextPageToken . '&rel=next');
            foreach ($response['resource'] as $product) {
                array_push($products, $product);
            }
            $nextPageToken = $response['next']['page_token'] ?? null;
        } while ($nextPageToken != null);

        return $products;
    }

    /**
     * get products for a page from Shopify
     * @return mixed
     */
    public function getProductsForPage($pageToken = null)
    {
        $response = $this->request(Request::METHOD_GET, 'products.json?page_info=' . $pageToken . '&rel=next');
        $products = collect($response['resource']);
        $nextPageToken = $response['next']['page_token'] ?? null;
        return ['products' => $products, 'next_page_token' => $nextPageToken];
    }

    /**
     * get a product from Shopify
      @param $id
     * @return mixed
     */
    public function getProductDetails($id)
    {
        $response = $this->request(Request::METHOD_GET, 'products/' . $id . '.json');
        return $response['resource'];
    }

    /**
     * get a variant from Shopify
     * @param $id
     * @return mixed
     */
    public function getVariantDetails($id)
    {
        $response = $this->request(Request::METHOD_GET, 'variants/' . $id . '.json');
        return $response['resource'];
    }


    /**
     * get metafields for an object
     * @param string $id Shopify ID
     * @return mixed
     */
    public function getMetafields($id, $type = 'products')
    {
        $response = $this->request(Request::METHOD_GET, "{$type}/{$id}/metafields.json");
        return $response['resource'];
    }


    /**
     * Get Shop details from the api
     * @return mixed
     */
    public function getShopDetails()
    {
        $response = $this->request(Request::METHOD_GET, 'shop.json');
        return $response['resource'] ?? $response;
    }


}
