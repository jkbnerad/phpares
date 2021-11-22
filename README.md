# phpares

PHP knihovna ke stahování informací z ARES (Administrativní registr ekonomických subjektů). 

## Použití

```PHP
<?php

declare(strict_types=1);

$client = new \GuzzleHttp\Client();
$validator = new \phpares\Validator();

$identificationData = new \phpares\IdentificationData($validator, $client);
$address = $identificationData->getAddress(48136450);

```

Výstup

```
[
    'id' => '209604861',
    'district' => 'Olomouc',
    'city' => 'Olomouc',
    'street' => 'Šemberova',
    'number' => '66',
    'numberType' => '1',
    'numberSecondary' => '9',
    'zipCode' => '77900'
]
```
