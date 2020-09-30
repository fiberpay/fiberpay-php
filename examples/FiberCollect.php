<?php

use FiberPay\FiberPayClient;


$apiKey = 'twój_klucz_jawny';
$apiSecret = 'twój_klucz_tajny';

$client = new \FiberPay\FiberPayClient($apiKey, $apiSecret, true);

//Tworzymy zlecenie przyjmowania płatności (można mieć wiele, np. z wypłatami na różne konta)
$response = $client->createCollect(
    'Przykładowa sp. z o.o.','PL55378528945895859558835555', 'PLN',
    'filia=mazowieckie');
echo "$response\n";

$ret = json_decode($response, true);
$parentCode =  $ret['data']['code'];

echo "\n ------    items     ------ \n";

//dodajemy kolejne pozycje, które chcemy aby zostały opłacone (nie wszystkie muszą być opłacone)

echo $client->addCollectItem($parentCode, "Opis płatności 1", 324.50);
echo "\n";

echo $client->addCollectItem($parentCode, "Opis płatności 2", 476.20, 'PLN',
    'https://your.api/callback', 'mySuperOrderId=7');
echo "\n";

echo $client->addCollectItem($parentCode, "Opis płatności 3", 1578.94);
echo "\n";

echo $client->addCollectItem($parentCode, "Opis płatności 4", 612.00, 'PLN',
    'https://your.api/callback', 'mySuperOrderId=8');
echo "\n";










