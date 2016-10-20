<?php

include_once 'include.php';

$params = array(
    'start' => '2016-09-01 00:00:00',
    'end'   => '2016-09-30 23:59:59'
    /*'widget_id' => '1',*/
);

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/statistics/callback_widget/', $params);

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    print_r($answerObject->stats);
} else {
    echo $answerObject->message;
}
