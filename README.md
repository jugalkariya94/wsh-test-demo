Laravel ReactJS Shopify Bookstore Sample

## Setup Instructions

Install composer dependencies 

    composer install

copy `.env.example` to `.env` file

Replace DB connection details with your own connection details

    DB_CONNECTION=mysql  
    DB_HOST=127.0.0.1  
    DB_PORT=3306  
    DB_DATABASE=laravel
    DB_USERNAME=root  
    DB_PASSWORD=

Get the Access token from Shopify and add the details into the `.env`

    SHOPIFY_API_TOKEN=shpat_shopify_access_token  
    SHOPIFY_APP_DOMAIN=shopify-store-domain

Run Migration

    php artisan migrate

Install NPM dependencies and run the frontend

    npm install && npm run dev
