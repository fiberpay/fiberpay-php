<?php

require_once realpath(dirname(__FILE__)) . '../lib/FiberPayClient.php';

function fiberCollectOrder(){
    $currency = 'PLN';
    $toName = 'Krzysztof Nowak';
    $toIban = 'PL55378528945895859558835555';

    /**         opcjonalnie         **/
    $metadata = 'Środki przychodzące z filii w woj. Mazowieckim';
}
