<?php

require_once realpath(dirname(__FILE__)) . '../lib/FiberPayClient.php';

$data = array();

$data['amount'] = 16500.00;
$data['currency'] = 'PLN';
$data['description'] = 'Środki z placówki w Radomiu czerwiec';
$data['parentCode'] = 'w3taegy6fzuj';

/**         opcjonalnie         **/
$metadata = 'Sklep przy ulicy Przasnyskiej 16';
$callbackUrl = 'nie wiem co tu wpisać';
$callbackParams = 'również nie wiem';