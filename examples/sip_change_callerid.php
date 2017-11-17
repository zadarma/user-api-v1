<?php

include_once 'include.php';

$params = array(
    'id' => '101230',
    'number' => '74991120101'
);

$zd = new \Zadarma_API\Client('05b259ec4036abdf4b15', '8c812526f60342c16c6c');
$answer = $zd->call('/v1/sip/callerid/', $params, 'put');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo 'Your Caller ID was successfully changed.';
} else {
    echo $answerObject->message;
}
