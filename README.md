# Zadarma API - User class
An official PHP class for work with Zadarma API.

Allows to work with all API methods (including VoIP, PBX, CallBack etc).

## Requirements:
- PHP >= 5.3.0
- cURL

## How to use?
An official documentation on Zadarma API is [here](https://zadarma.com/ru/support/api/).

Keys for authorization are in [personal account](https://ss.zadarma.com/api/).

## Installation
### Via Сomposer
```sh
composer require "zadarma/user-api-v1"
```
### Via Git
```sh
git clone git@github.com:zadarma/user-api-v1.git
```
Or just download "Client.php" from the folder "lib" and put it to your library folder.

###  Code example
```php
<?php

include_once '/PATH_TO/lib/Client.php';
// include_once '/PATH_TO/vendor/autoload.php'; // or the path to your "vendor" autoload file

$params = array(
    'id' => 'YOURSIP',
    'status' => 'on'
);

$zd = new \Zadarma_API\Client(YOUR_KEY, YOUR_SECRET);
$answer = $zd->call('/v1/sip/redirection/', $params, 'put');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo 'Redirection on your SIP "' . $answerObject->sip . " has been changed to " . $answerObject->current_status . ".";
} else {
    $answerObject->message;
}
```

All other examples you can see in the folder "examples".