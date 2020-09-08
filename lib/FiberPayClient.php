<?php

namespace FiberPay;

class FiberPayClient {

    private $version = '1.0';
    private $apiUrl;

    protected $apiKey;
    protected $apiSecret;

    public function __construct($apiKey, $apiSecret, $testServer = false) {
        $this->apiUrl = $testServer ? 'https://apitest.fiberpay.pl' : 'https://api.fiberpay.pl';

        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    private function call($httpMethod, $uri, $data = null){
        $headers = $this->createHeaders($httpMethod, $uri, $data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl . $uri);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if($httpMethod === 'post'){
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $response = curl_exec($curl);
        return $response;
    }

    private function createHeaders($httpMethod, $uri, $data = null){
        $nonce = explode(' ', microtime());
        $nonce = $nonce[1] . substr($nonce[0], 2);

        $route = implode(' ', [strtoupper($httpMethod), $uri]);

        $data = empty($data) ? '' : json_encode($data);
        $signature = $this->signature($route, $nonce, $this->apiKey, $data, $this->apiSecret);

        $headers = [
            "Content-Type: application/json",
            "X-API-Key: $this->apiKey",
            "X-API-Nonce: $nonce",
            "X-API-Route: $route",
            "X-API-Signature: $signature",
        ];

        return $headers;
    }

    private function signature($route, $nonce, $apiKey, $data, $apiSecret) {
        $toBeSigned = implode('', [$route, $nonce, $apiKey, $data]);
        return hash_hmac('sha512', $toBeSigned, $apiSecret);
    }


    public function createSplit() {
        $data['currency'] = 'PLN';

        $httpMethod = 'post';
        $uri = "/api/$this->version/orders/split";

        return $this->call($httpMethod, $uri, $data);
    }

    public function addSplitItem($orderCode, $amount, $currency, $toName, $toIban, $description, $callbackUrl = null) {
        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'parentCode' => $orderCode,
            'toName' => $toName,
            'toIban' => $toIban,
            'description' => $description,
        ];

        if(!empty($callbackUrl)) {
            $data['callbackUrl'] = $callbackUrl;
        }

        $httpMethod = 'post';
        $uri = "/api/$this->version/orders/split/item";

        return $this->call($httpMethod, $uri, $data);
    }

    public function endDefinitionOfSplit($orderCode) {
        $httpMethod = 'put';
        $uri = "/api/$this->version/orders/split/$orderCode/define";

        return $this->call($httpMethod, $uri);
    }

    public function getSplitOrderInfo($orderCode) {
        $httpMethod = 'get';
        $uri = "/api/$this->version/orders/split/$orderCode";

        return $this->call($httpMethod, $uri);
    }

    public function getSplitOrderItemInfo($orderItemCode) {
        $httpMethod = 'get';
        $uri = "/api/$this->version/orders/split/item/$orderItemCode";

        return $this->call($httpMethod, $uri);
    }


}