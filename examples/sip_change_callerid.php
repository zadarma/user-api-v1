<?php

include_once 'include.php';

$params = array(
    'id' => 'YOURSIP',
    'number' => '442037691880'
);

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/info/price/', $params, 'put');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo 'Your Caller ID was successfully changed.';
} else {
    echo $answerObject->message;
}