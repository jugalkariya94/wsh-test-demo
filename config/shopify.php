<?php

return [
    'api_key' => env('SHOPIFY_API_KEY'),
    'api_secret' => env('SHOPIFY_API_SECRET'),
    'api_token' => env('SHOPIFY_API_TOKEN'),
    'app_domain' => env('SHOPIFY_APP_DOMAIN'),
    'api_scope' => env('SHOPIFY_API_SCOPE', [
        "write_products",
        "read_product_listings",
        "read_product_listings",
        "read_products",
    ]),
];
