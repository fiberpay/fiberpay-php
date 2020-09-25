<?php

require_once realpath(dirname(__FILE__)) . '../../lib/FiberPayClient.php';

$apiKey = 'twój klucz publiczny';
$apiSecret = 'twój klucz prywatny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);

function fiberDirect(){
    global $client;

    /** wymagane */
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

    echo "------    fiberDirect     ------"."\n".$response;

}

fiberDirect();