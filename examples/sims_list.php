<?php

include_once 'include.php';

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/sim/');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    print_r($answerObject->sims);
} else {
    echo $answerObject->message;
}
