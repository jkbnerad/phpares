<?php
declare(strict_types=1);

namespace phpares;

interface IValidator
{
    public function isValid(string|int $ic, bool $exception = false): bool;
}
