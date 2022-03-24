<?php

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

// Resources and Collections
use Illuminate\Http\Response;

class ShopifyAPIClient
{
    /**
     * api client
     * @var $client string
     */
    protected $client;

    /**
     * Shopify API version
     * @var $apiVersion string
     */
    protected $apiVersion;

    /**
     * users's  shopify domain
     * @var $domain string
     */
    protected $domain;

    /**
     * shopify api token
     * @var $apiToken string
     */
    protected $apiToken;

    /**
     * Shopify API Version
     * */
    const API_VERSION = '2022-01';

    /**
     * ShopifyAPIService constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->domain = config('services.shopify.app_domain');
        $this->apiToken = config('services.shopify.api_token');
        $this->apiVersion = self::API_VERSION;
    }

    /**
     * make  shopify api request
     * @param string $method  method to call
     * @param string $url  URL to call
     * @param array $param
     * @return mixed
     */
    protected function request($method, $url, $param = [])
    {
        $requestURL = 'https://' . $this->domain . '.myshopify.com/admin/api/' . $this->apiVersion . '/' . $url;
        $parameters = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Shopify-Access-Token' => $this->apiToken
            ]
        ];

        if (!empty($param)) {
            $parameters['json'] = $param;
        }
        // TODO: Refactor exception handling
        $responseHeaders = [];
        try {
            $response = $this->client->request($method, $requestURL, $parameters);
            $responseHeaders = $response->getHeaders();

            // get next page token
            $tokenType = 'next';
            if (array_key_exists('Link', $responseHeaders)) {
                $link = $responseHeaders['Link'][0];
                $tokenType = strpos($link, 'rel="next') !== false ? "next" : "previous";
                $tobeReplace = ["<", ">", 'rel="next"', ";", 'rel="previous"'];
                $tobeReplaceWith = ["", "", "", ""];
                parse_str(parse_url(str_replace($tobeReplace, $tobeReplaceWith, $link), PHP_URL_QUERY), $op);
                $pageToken = trim($op['page_info']);
            }

            // check the call limit
            if (!empty($responseHeaders["X-Shopify-Shop-Api-Call-Limit"])) {
                $rateLimit = explode('/', $responseHeaders["X-Shopify-Shop-Api-Call-Limit"][0]);
                $usedLimitPercentage = (100 * $rateLimit[0]) / $rateLimit[1];
                if ($usedLimitPercentage > 95) {
                    sleep(5);
                }
            }

            $responseBody = $response ? json_decode($response->getBody(), true) : [];
            $r['resource'] = (is_array($responseBody) && count($responseBody) > 0) ? array_shift($responseBody) : $responseBody;
            $r[$tokenType]['page_token'] = isset($pageToken) ? $pageToken : null;
            return $r;
        }
        catch (RequestException $e) {
            dd($e->getMessage());
            $error = __('message.error');
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode(); // HTTP status code;
                if ($statusCode === Response::HTTP_TOO_MANY_REQUESTS) {
                    sleep(1);
                    return $this->request($method, $url, $param);
                }
                $response = (json_decode($e->getResponse()->getBody()));
                \Log::error('Something went wrong with Shopify api request', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                $error = __('message.error');
                if (is_object($response) && !empty($response->errors))
                    $error = $response->errors;
            }
            return ['resource' => [], 'errors' => $error];
        }
        catch (\Exception  $e) {
            \Log::error('Something went wrong with Shopify api request', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ['resource' => [], 'errors' => __('message.error')];
        }

    }

    // URL structure is a bit different so not using the request method here
    public function getAppScope()
    {

        $parameters = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Shopify-Access-Token' => $this->apiToken
            ]
        ];

        if (!empty($param)) {
            $parameters['json'] = $param;
        }
        $url = 'https://' . $this->apiKey . ':' . $this->apiPassword . '@' . $this->domain . '.myshopify.com/admin/oauth/access_scopes.json';
        $response = $this->client->request(Request::METHOD_GET, $url, $parameters);

        $responseBody = json_decode($response->getBody(), true);
        $accessScopes = array_column($responseBody['access_scopes'], 'handle');
        return $accessScopes;
    }

}
