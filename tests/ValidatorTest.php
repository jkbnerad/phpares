<?php

declare(strict_types=1);

use phpares\Validator;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

$validator = new Validator();

// valid
Assert::true($validator->isValid(25596641));
Assert::true($validator->isValid('00023574'));
Assert::true($validator->isValid('23574')); // short as string
Assert::true($validator->isValid(23574)); // short as int

// invalid
Assert::false($validator->isValid('025596641'));
Assert::false($validator->isValid(12345678));

