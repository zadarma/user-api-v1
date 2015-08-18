<?php

include_once 'include.php';

$params = array(
    'id' => 'YOURPBXNUMBER', // list of your PBX internal numbers you can get from "/v1/pbx/internal/"
    'status' => 'on',
//    'email' => 'YOUREMAIL'
);

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/pbx/internal/recording/', $params, 'put');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo "Number: " . $answerObject->internal_number . "<br/>";
    echo "Status: " . $answerObject->recording;
} else {
    echo $answerObject->message;
}
