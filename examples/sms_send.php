<?php

include_once 'include.php';

$params = array(
    'number' => '442037691880',
    'message' => 'Hello from Zadarma API',
    /*'caller_id' => 'YOURPROVEDPHONE'*/
);

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/sms/send/', $params, 'post');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo 'Messages: ' . $answerObject->messages . '<br/>';
    echo 'Cost: ' . $answerObject->cost . ' ' . $answerObject->currency .  '<br/>';
} else {
    echo $answerObject->message;
}
