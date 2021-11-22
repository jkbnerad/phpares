<?php

declare(strict_types = 1);

namespace phpares;

use phpares\Exceptions\InvalidICException;
use phpares\Exceptions\TooLongICException;

class Validator implements IValidator
{
    private const IC_LENGTH = 8;

    /**
     * @throws TooLongICException
     * @throws InvalidICException
     */
    public function isValid(string|int $ic, bool $exception = false): bool
    {
        $icStr = (string)$ic;
        $length = strlen($icStr);

        if ($length > self::IC_LENGTH) {
            if ($exception) {
                throw new TooLongICException(sprintf('%s is too long. Max. %s characters expected.', $icStr, self::IC_LENGTH));
            } else {
                return false;
            }
        }

        if ($length < self::IC_LENGTH) {
            $icStr = str_pad($icStr, self::IC_LENGTH, '0', STR_PAD_LEFT);
        }

        // checksum
        $sum = 0;
        for ($i = 0; $i < 7; $i++) {
            $sum += (int)$icStr[$i] * (self::IC_LENGTH - $i);
        }

        $checkNumber = (11 - ($sum % 11)) % 10;

        if ((int)$icStr[7] === $checkNumber) {
            return true;
        }

        if ($exception) {
            throw new InvalidICException(sprintf('%s is invalid IC.', $icStr));
        } else {
            return false;
        }
    }
}
