<?php

include_once 'include.php';

$params = array(
    /*'id' => 'YOURSIP',*/
);


$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/sip/redirection/', $params);

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    print_r($answerObject->info);
}
