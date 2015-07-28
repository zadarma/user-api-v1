# Zadarma API - User class
An official PHP class for work with Zadarma API.

Allows to work with all API methods (including VoIP, PBX, CallBack etc).

## Requirements:
- PHP >= 5.3.0
- cURL

## How to use?
An official documentation on Zadarma API is [here](https://zadarma.com/support/api/).

Keys for authorization are in [personal account](https://ss.zadarma.com/api/).

## Installation
### Via Сomposer
```sh
composer require "zadarma/user-api-v1"
```
or just add this line to your `composer.json` file:
```json
"zadarma/user-api-v1"
```

### Via Git
```sh
git clone git@github.com:zadarma/user-api-v1.git
```
Or just download "Client.php" from the folder "lib" and put it to your library folder.

###  Code example
```php
<?php

include_once '/PATH/TO/lib/Client.php';
// include_once '/PATH/TO/vendor/autoload.php'; // or the path to your "vendor" autoload file

$params = array(
    'id' => 'YOURSIP',
    'status' => 'on'
);

$zd = new \Zadarma_API\Client(YOUR_KEY, YOUR_SECRET);
/*
$zd->call('METHOD', 'PARAMS_ARRAY', 'REQUEST_TYPE', 'FORMAT', 'IS_AUTH');
where:
- METHOD - a method API, started from /v1/ and ended by '/';
- PARAMS_ARRAY - an array of parameters to a method;
- REQUEST_TYPE: GET (default), POST, PUT, DELETE;
- FORMAT: json (default), xml;
- IS_AUTH: true (default), false - is method under authentication or not.
*/
$answer = $zd->call('/v1/sip/redirection/', $params, 'put');

$answerObject = json_decode($answer);

if ($answerObject->status == 'success') {
    echo 'Redirection on your SIP "' . $answerObject->sip . " has been changed to " . $answerObject->current_status . ".";
} else {
    $answerObject->message;
}
```

All other examples you can see in the "[examples](https://github.com/zadarma/user-api-v1/tree/master/examples)" folder.