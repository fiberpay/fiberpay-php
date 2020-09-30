<?php

use FiberPay\FiberPayClient;


$apiKey = 'twój_klucz_jawny';
$apiSecret = 'twój_klucz_tajny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);


//zakłada zlecenie SplitOrder
$parentCode = createSplitOrder($client);

//dodaje elementy do zlecenia
addSplitItems($client, $parentCode);

//kończy definicję zlecenia (ważne, bez tego nie da się opłacić zlecenia)
$response = $client->endDefinitionOfSplit($parentCode);

echo "\n"."------    fiberSplitDefine     ------\n";
echo $response;
//w tym momencie należy opłacić zlecenie wg danych z encji invoice


/**
 * @param FiberPayClient $client
 * @param string $parentCode
 * @return string
 */
function addSplitItems($client, $parentCode){

    //tworzymy kilka pozycji zlecenia (przelewów do wysłania)

    echo "\n------    fiberSplitItem      ------\n";

    echo $client->addSplitItem($parentCode, 'Jan Kowalski', 'PL27114020040000300201355387',
        'Tytuł przelewu 1', 10000, 'PLN');

    /** parametry opcjonalnie */
    $metadata = 'dodatkowe informacje';
    $callbackUrl = 'https://your.api/callback';
    $callbackParams = 'mySuperId=7';

    echo $client->addSplitItem(
        $parentCode, 'Katarzyna Nowak', 'PL12344020040000300201355387',
        'Tytuł przelewu 1', 5217.10, 'PLN');
    echo "\n";
    echo $client->addSplitItem(
        $parentCode, 'Jerzy Zadrożny', 'PL56784020040000300201355387',
        'Tytuł przelewu 2', 2560.70, 'PLN', $callbackUrl, $callbackParams, $metadata);
    echo "\n";
    echo $client->addSplitItem(
        $parentCode, 'Wiesława Machaj', 'PL91014020040000300201355387',
        'Tytuł przelewu 3', 4270.00, 'PLN', $callbackUrl, $callbackParams, $metadata);
    echo "\n";
    echo $client->addSplitItem(
        $parentCode, 'Radosław Janikowski', 'PL34564020040000300201355387',
        'Tytuł przelewu 4', 1200.50, 'PLN');
    echo "\n";


    return $parentCode; // zwraca parentCode potrzebny do SplitDefine
}


/**
 * @param FiberPayClient $client
 * @return string
 */
function createSplitOrder($client) {
    echo "------    fiberSplitOrder     ------\n";

    $response =  $client->createSplit('PLN');
    echo "$response\n";

    $json = json_decode($response, true);
    return $json['data']['code'];  // zwraca code, który dalej jest traktowany jako parentCode
}



