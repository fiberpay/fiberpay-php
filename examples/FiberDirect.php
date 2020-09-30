<?php

use FiberPay\FiberPayClient;


$apiKey = 'twój_klucz_jawny';
$apiSecret = 'twój_klucz_tajny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);

/** symuluje proces tworzenia przelewu */

$toName = "Zbigniew Sieczkowski";
$toIban = 'PL27114020040000300201355387';
$description = "Tutuł przelewu";
$amount = 21056.30;
$currency = 'PLN';  // na chwilę obecną jedyna dostępna opcja

/** opcjonalnie */
$callbackUrl = 'https://your.api/callback';
$callbackParams = 'callback params';
$metadata = 'dodatkowe informacje';

$response = $client->createDirect($toName, $toIban, $description, $amount, $currency);
$responseOptional = $client->createDirect($toName, $toIban, $description, $amount, $currency, $callbackUrl, $callbackParams, $metadata);

echo "------    fiberDirect     ------\n" . $response;

