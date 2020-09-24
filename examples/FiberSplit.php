<?php

require_once realpath(dirname(__FILE__)) . '../../lib/FiberPayClient.php';

$apiKey = 'twój klucz publiczny';
$apiSecret = 'twój klucz prywatny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);



    /**     tworzy FiberSplitOrder oraz pobiera orderCode potrzebny w kolejnych etapach     */
function fiberSplitOrder() {
    global $client;

    /**         wymagane            */
    $currency = 'PLN';  // na chwilę obecną jedyna dostępna opcja

    /**         opcjonalnie         */
    $metadata = 'eg. Wypłata środków za okres styczeń - luty 2020';



    $response = $client->createSplit($currency);
    /**     z wykorzystaniem opcjonalnych parametrów
    $response = $client->createSplit($currency, $metadata); **/

    $json = json_decode($response, true);
    echo nl2br("------    fiberSplitOrder     ------");
    echo $response;
    return $json['data']['code'];
}

    /** dodaje Item do zlecenia */
function fiberSplitItem(){
    global $client;

    /**         wymagane            */
    $parentCode = fiberSplitOrder(); /** w wersji przykładowej tworzy nowy FiberSplitOrder i pobiera jego kod
    np.  $parentCode = 'zc6ta75gfpme';  kod uzyskujemy po stworzeniu FiberSplitOrder                      **/
    $amount = 3650.50;
    $currency = 'PLN';  // na chwilę obecną jedyna dostępna opcja
    $toName = 'Jan Kowalski';
    $toIban = 'PL27114020040000300201355387';
    $description = 'Wynagrodzenie za miesiąc styczeń 2020';

    /**         opcjonalnie         **/
    $metadata = 'Faktura nr 23461';
    $callbackUrl = '';
    $callbackParams = '';



    $response = $client->addSplitItem($parentCode, $toName, $toIban, $description, $amount, $currency);
    /**     z wykorzystaniem opcjonalnych parametrów
    $response = $client->addSplitItem($parentCode, $toName, $toIban, $description, $amount,
            $currency, $callbackUrl, $callbackParams, $metadata);                       */

    // dla celów poglądowych dodajemy do tego samego Orderu kilka Itemów
    $client->addSplitItem($parentCode, 'Katarzyna Nowak', 'PL12344020040000300201355387', $description, 5217.10, 'PLN');
    $client->addSplitItem($parentCode, 'Jerzy Zadrożny', 'PL56784020040000300201355387', $description, 2560.70, 'PLN');
    $client->addSplitItem($parentCode, 'Wiesława Machaj', 'PL91014020040000300201355387', $description, 4270.00, 'PLN');
    $client->addSplitItem($parentCode, 'Radosław Janikowski', 'PL34564020040000300201355387', $description, 1200.50, 'PLN');

    $json = json_decode($response, true);
    echo nl2br("------    fiberSplitItem     ------");
    echo $response;
    return $json['data']['parentCode'];

}

    /** zamyka zlecenie */
function fiberSplitDefine(){
    global $client;

    /**         wymagane            */
    $parentCode = fiberSplitItem();/** w wersji przykładowej tworzy nowy FiberSplitOrder, w nim 5 SplitItemów i pobiera parentCode
    np.  $parentCode = 'zc6ta75gfpme';  kod uzyskujemy po stworzeniu FiberSplitOrder                      **/



    $response = $client->endDefinitionOfSplit($parentCode);
    $json = json_decode($response, true);
    echo nl2br("------    fiberSplitDefine     ------");
    echo $response;
}

fiberSplitDefine();

