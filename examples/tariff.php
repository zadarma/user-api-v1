<?php

include_once 'include.php';

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/tariff/');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
	print_r($answer);
} else {
	echo $answerObject->message;
}
