<?php

include_once 'include.php';

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/sip/00001/status/');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    print_r($answerObject->is_online);
}
