<?php

include_once 'include.php';

$zd = new \Zadarma_API\Client('05b259ec4036abdf4b15', '8c812526f60342c16c6c');
$answer = $zd->call('/v1/info/balance/');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo 'Your balance is ' . $answerObject->balance . ' ' . $answerObject->currency;
}
echo "\n";
/*
echo 'Limits: ';
print_r($zd->getLimits());
*/
