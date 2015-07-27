<?php

include_once 'include.php';

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/info/balance/');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo 'Your balance is ' . $answerObject->balance . ' ' . $answerObject->currency;
}

/*
echo 'Limits: ';
print_r($zd->getLimits());
*/