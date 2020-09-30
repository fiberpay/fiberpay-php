<?php

use FiberPay\FiberPayClient;


$apiKey = 'twój_klucz_jawny';
$apiSecret = 'twój_klucz_tajny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);

/** wymagane */
$targetName = "Zbigniew Sieczkowski";
$targetIban = 'PL27114020040000300201355387';
$brokerName = "Janina Mocarz";
$brokerIban = 'PL93234120040015780201352241';
$description = "Tutuł przelewu";
$sourceAmount = 13572.80;
$targetAmount = 10780.30;
$currency = 'PLN';  // na chwilę obecną jedyna dostępna opcja

/** opcjonalnie */
$callbackUrl = 'https://your.api/callback';
$callbackParams = 'shopId=7';
$metadata = 'userHash=22ab345cac';

$response = $client->createForward(
    $targetName, $targetIban,$brokerName,$brokerIban,
    $description, $sourceAmount, $targetAmount, $currency);

$responseOptional = $client->createForward(
    $targetName, $targetIban,$brokerName, $brokerIban,
    $description, $sourceAmount, $targetAmount,
    $currency, $callbackUrl, $callbackParams, $metadata);

echo "------    fiberForward     ------\n";
echo $response;


