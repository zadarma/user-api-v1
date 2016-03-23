<?php

include_once 'include.php';

$params = array(
    'call_id' => '11111111111111.1111',
//    'lifetime' => 12000,
);

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/pbx/record/request/', $params);

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    print_r($answerObject);
} else {
    echo $answerObject->message;
}