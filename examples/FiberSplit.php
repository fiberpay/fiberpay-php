<?php

require_once realpath(dirname(__FILE__)) . '../../lib/FiberPayClient.php';


$apiKey = 'twój_klucz_jawny';
$apiSecret = 'twój_klucz_tajny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);




/** symuluje cały proces, od stworzenia Orderu poprzez dodanie Itemów aż do zamknięcia zlecenia */
fiberSplitDefine();


/***********************************************************
 *                                                         *
 *                      Funkcje                            *
 *                                                         *
 **********************************************************/


/** zamyka zlecenie */
function fiberSplitDefine(){
    global $client;

    /** wymagane */
    //    w wersji przykładowej tworzy nowy FiberSplitOrder, w nim 5 SplitItemów i pobiera parentCode
    //    np.  $parentCode = 'zc6ta75gfpme';  kod uzyskujemy po stworzeniu FiberSplitOrder
    $parentCode = fiberSplitItem();


    $response = $client->endDefinitionOfSplit($parentCode);
    echo "\n"."------    fiberSplitDefine     ------"."\n".$response;
}

/** dodaje Item do zlecenia */
function fiberSplitItem(){
    global $client;

    /** wymagane */
    //    w wersji przykładowej tworzy nowy FiberSplitOrder i pobiera jego kod
    //    np.  $parentCode = 'zc6ta75gfpme';  kod uzyskujemy po stworzeniu FiberSplitOrder
    $parentCode = fiberSplitOrder();
    $amount = 3650.50;
    $currency = 'PLN';  // na chwilę obecną jedyna dostępna opcja
    $toName = 'Jan Kowalski';
    $toIban = 'PL27114020040000300201355387';
    $description = 'Wynagrodzenie za miesiąc styczeń 2020';

    /** opcjonalnie */
    $metadata = 'dodatkowe informacje';
    $callbackUrl = 'https://your.api/callback';
    $callbackParams = 'callback params';


    $response = $client->addSplitItem($parentCode, $toName, $toIban, $description, $amount, $currency);
    /** z wykorzystaniem opcjonalnych parametrów */
    $responseOptional = $client->addSplitItem($parentCode, $toName, $toIban, $description, $amount,
        $currency, $callbackUrl, $callbackParams, $metadata);

    /** dla celów testowych zostanie wyświetlony response otrzymany po dodawaniu Itemu */
    $testPrintData = "\n"."------    fiberSplitItem      ------"."\n";
    $testPrintData .= $response;


    /** dla celów testowych tworzymy kilka dodatkowych Itemów do tego samego Orderu (czyli wykorzystując ten sam parentCode) */
    $testPrintData .= $client->addSplitItem($parentCode, 'Katarzyna Nowak', 'PL12344020040000300201355387', $description, 5217.10, 'PLN');
    $testPrintData .= $client->addSplitItem($parentCode, 'Jerzy Zadrożny', 'PL56784020040000300201355387', $description, 2560.70, 'PLN', $callbackUrl, $callbackParams, $metadata);
    $testPrintData .= $client->addSplitItem($parentCode, 'Wiesława Machaj', 'PL91014020040000300201355387', $description, 4270.00, 'PLN', $callbackUrl, $callbackParams, $metadata);
    $testPrintData .= $client->addSplitItem($parentCode, 'Radosław Janikowski', 'PL34564020040000300201355387', $description, 1200.50, 'PLN');

    echo $testPrintData;

    return $parentCode; // zwraca parentCode potrzebny do SplitDefine
}



/** tworzy FiberSplitOrder oraz pobiera orderCode potrzebny w kolejnych etapach */
function fiberSplitOrder() {
    global $client;

    /** wymagane */
    $currency = 'PLN';  // na chwilę obecną jedyna dostępna opcja

    /** opcjonalnie */
    $metadata = 'eg. Wypłata środków za okres styczeń - luty 2020';


    $response = $client->createSplit($currency);
    /** z wykorzystaniem opcjonalnych parametrów */
    $responseOptional = $client->createSplit($currency, $metadata);

    /** dla celów testowych zostanie wyświetlony response otrzymany po stworzeniu Orderu */
    $testPrintData = "------    fiberSplitOrder     ------"."\n".$response;
    echo $testPrintData;

    $json = json_decode($response, true);
    return $json['data']['code'];  // zwraca code, który dalej jest traktowany jako parentCode
}



