<?php

use FiberPay\FiberPayClient;


$apiKey = 'twój_klucz_jawny';
$apiSecret = 'twój_klucz_tajny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);


$parentCode = fiberCollectOrder($client);
fiberCollectItem($client, $parentCode);


/***********************************************************
 *                                                         *
 *                      Funkcje                            *
 *                                                         *
 **********************************************************/

/** tworzy zlecenie oraz zwraca kod tego zlecenia (orderCode) potrzebny w kolejnych etapach */
/**
 * @param FiberPayClient $client
 * @return mixed
 */
function fiberCollectOrder($client){

    /** wymagane */
    $currency = 'PLN';      // na chwilę obecną jedyna dostępna opcja
    $toName = 'Krzysztof Nowak';
    $toIban = 'PL55378528945895859558835555';

    /** opcjonalnie */
    $metadata = 'Środki przychodzące z filii w woj. Mazowieckim';

    $response = $client->createCollect($toName, $toIban, $currency, $metadata);

    /** dla celów testowych zostanie wyświetlony response otrzymany po stworzeniu Orderu */
    echo "------    fiberCollectOrder     ------ \n" . $response;

    $json = json_decode($response, true);
    return $json['data']['code']; // zwraca code, który dalej jest traktowany jako parentCode
}


/**
 * @param FiberPayClient $client
 * @param string $parentCode
 */
function fiberCollectItem($client, $parentCode){

    /** parametry wymagane */
    $description = "Tytuł przelewu";
    $amount = 324.50;
    $currency = 'PLN';  // na chwilę obecną jedyna dostępna opcja

    /** opcjonalnie */
    $callbackUrl = 'https://your.api/callback';
    $callbackParams = 'mySuperOrderId=7';
    $metadata = 'clientHash=abc123';

    /** dla celów testowych zostanie wyświetlony response otrzymany po dodawaniu Itemu */
    echo "\n ------    fiberCollectItem     ------ \n";
    echo $client->addCollectItem($parentCode, $description, $amount, $currency);


    /** dla celów testowych tworzymy kilka dodatkowych Itemów do tego samego Orderu (czyli wykorzystując ten sam parentCode) */
    echo $client->addCollectItem($parentCode, $description, 476.20, $currency, $callbackUrl, $callbackParams, $metadata);
    echo $client->addCollectItem($parentCode, $description, 1578.94, $currency);
    echo $client->addCollectItem($parentCode, $description, 612.00, $currency, $callbackUrl, $callbackParams, $metadata);
}









