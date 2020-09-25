<?php

require_once realpath(dirname(__FILE__)) . '../../lib/FiberPayClient.php';


$apiKey = 'twój klucz publiczny';
$apiSecret = 'twój klucz prywatny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);

    /** tworzy FiberCollectOrder oraz pobiera orderCode potrzebny w kolejnych etapach */
function fiberCollectOrder(){
    global $client;

    /** wymagane */
    $currency = 'PLN';      // na chwilę obecną jedyna dostępna opcja
    $toName = 'Krzysztof Nowak';
    $toIban = 'PL55378528945895859558835555';

    /** opcjonalnie */
    $metadata = 'Środki przychodzące z filii w woj. Mazowieckim';


    $response = $client->createCollect($toName, $toIban, $currency, $metadata);

    /** dla celów testowych zostanie wyświetlony response otrzymany po stworzeniu Orderu */
    $testPrintData = "------    fiberCollectOrder     ------ "."\n".$response;
    echo $testPrintData;

    $json = json_decode($response, true);
    return $json['data']['code']; // zwraca code, który dalej jest traktowany jako parentCode
}

function fiberCollectItem(){
    global $client;

    /** wymagane */
    // w wersji przykładowej tworzy nowy FiberCollectOrder i pobiera jego kod
    // np.  $parentCode = 'zc6ta75gfpme';  kod uzyskujemy po stworzeniu FiberCollectOrder
    $parentCode = fiberCollectOrder();
    $description = "Tytuł przelewu";
    $amount = 324.50;
    $currency = 'PLN';  // na chwilę obecną jedyna dostępna opcja

    /** opcjonalnie */
    $callbackUrl = 'https://your.api/callback';
    $callbackParams = 'callback params';
    $metadata = 'dodatkowe informacje';

    /** dla celów testowych zostanie wyświetlony response otrzymany po dodawaniu Itemu */
    $testPrintData = "\n"."------    fiberCollectItem     ------"."\n";
    $testPrintData .= $client->addCollectItem($parentCode, $description, $amount, $currency);


    /** dla celów testowych tworzymy kilka dodatkowych Itemów do tego samego Orderu (czyli wykorzystując ten sam parentCode) */
    $testPrintData .= $client->addCollectItem($parentCode, $description, 476.20, $currency, $callbackUrl, $callbackParams, $metadata);
    $testPrintData .= $client->addCollectItem($parentCode, $description, 1578.94, $currency);
    $testPrintData .= $client->addCollectItem($parentCode, $description, 612.00, $currency, $callbackUrl, $callbackParams, $metadata);

    echo $testPrintData;
}

fiberCollectItem();




