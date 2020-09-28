<?php

require_once realpath(dirname(__FILE__)) . '../../lib/FiberPayClient.php';


$apiKey = 'twój_klucz_jawny';
$apiSecret = 'twój_klucz_tajny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);




/** symuluje proces usługi FiberForward */
fiberForward();


/***********************************************************
 *                                                         *
 *                      Funkcje                            *
 *                                                         *
 **********************************************************/


function fiberForward(){
    global $client;

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
    $callbackParams = 'callback params';
    $metadata = 'dodatkowe informacje';

    // TODO : ForwardController w backendzie / do dodania sprawdzenie
    $response = $client->createForward($targetName, $targetIban,$brokerName,$brokerIban, $description, $sourceAmount, $targetAmount, $currency);
    $responseOptional = $client->createForward($targetName, $targetIban,$brokerName, $brokerIban, $description, $sourceAmount, $targetAmount,
        $currency, $callbackUrl, $callbackParams, $metadata);

    echo "------    fiberForward     ------"."\n".$response;
//    echo "------    fiberForward     ------"."\n".$responseOptional;

}


