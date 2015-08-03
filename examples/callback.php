<?php

include_once 'include.php';

$params = array(
    'from' => '442037691880',
    'to' => '442037691881',
//    'sip' => 'YOURSIP'
);

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/request/callback/', $params);

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    print_r($answerObject);
} else {
    echo $answerObject->message;
}
