<?php

include_once 'include.php';

$params = array(
    'call_id' => '1458832388.1585217',
//    'pbx_call_id' => 'in_c2b77d043ae465a1072d3f745e071032b9485461',
//    'lifetime' => 180,
);

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/pbx/record/request/', $params);

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo "<pre>";
    print_r($answerObject);
    echo "</pre>";
} else {
    echo $answerObject->message;
}