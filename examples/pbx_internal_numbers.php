<?php

include_once 'include.php';

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/pbx/internal/');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo "<pre>";
    print_r($answerObject->numbers);
    echo "</pre>";
}