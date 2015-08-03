<?php

include_once 'include.php';

$params = array(
    'id' => 'YOURSIP',
    'status' => 'on'
);

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/sip/redirection/', $params, 'put');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo 'Redirection on your SIP "' . $answerObject->sip . " has been changed to " . $answerObject->current_status . ".";
} else {
    echo $answerObject->message;
}
