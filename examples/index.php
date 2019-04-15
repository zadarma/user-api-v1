<?php

use Zadarma_API\Api;

require_once __DIR__.DIRECTORY_SEPARATOR.'include.php';

$api = new Api(KEY, SECRET, true);

// TODO: enter your values
$sourceNumber = ''; // in international format
$destinationNumber = '';
$sip = ''; // sip number
$pbx = ''; // internal number
$callId = '';
$destinationEmail = '';

// info methods
$result = $api->getBalance();
$result = $api->getPrice($destinationEmail, $sourceNumber);
$result = $api->getTimezone();
$result = $api->getTariff();


// pbx methods
$result = $api->getPbxInternal();
$result = $api->getPbxStatus($pbx);
$result = $api->getPbxRecord($callId, null);
$result = $api->getPbxRedirection($pbx);

$result = $api->setPbxPhoneRedirection($pbx, $destinationNumber, false, true);
$result = $api->setPbxVoicemailRedirection($pbx, $destinationEmail, true, Api::PBX_REDIRECTION_OWN_GREETING);
$result = $api->setPbxRedirectionOff($pbx);


// sip methods
$result = $api->getSip();
$result = $api->getSipStatus($sip);
$result = $api->getSipRedirection($sip);

$result = $api->setSipCallerId($sip, $sourceNumber);
$result = $api->setSipRedirectionNumber($sip, $destinationNumber);
$result = $api->setSipRedirectionStatus($sip, false);


// statistic methods
$result = $api->getStatistics();
$result = $api->getPbxStatistics();
$result = $api->getDirectNumbers();


// other methods
$result = $api->requestCallback($pbx, $destinationNumber);

$result = $api->numberLookup($destinationNumber);
$result = $api->numberLookupMultiple([$sourceNumber, $destinationNumber]);

$result = $api->sendSms($destinationNumber, 'text', $sourceNumber);
