<?php

include_once 'include.php';

$greetingFile = 'greeting.wav';

if(!file_exists($greetingFile)){
    echo "File $greetingFile does not exist";
}

$params = array(
    'status' => 'on',
    'pbx_number' => 'YOUR_PBX_ID-YOUR_PBX_NUMBER',
    'destination' => 'YOUR_EMAIL',
    'condition' => 'always',
    'type' => 'voicemail',
    'voicemail_greeting' => 'own',
    'greeting_file' => curl_file_create($greetingFile),
);

$zd = new \Zadarma_API\Client(KEY, SECRET);
$answer = $zd->call('/v1/pbx/redirection/', $params, 'post');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo 'Redirection on your PBX "' . $answerObject->pbx_id.'-'.$answerObject->pbx_name . " has been changed to " . $answerObject->destination . ".";
} else {
    echo $answerObject->message;
}
