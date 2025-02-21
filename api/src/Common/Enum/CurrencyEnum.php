<?php

declare(strict_types=1);

namespace App\Common\Enum;

enum CurrencyEnum: string
{
    case RUB = 'RUB';
    case USD = 'USD';
    case THB = 'THB';

    /**
     * @return array<string>
     */
    public static function getValues(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            $result[] = $case->value;
        }

        return $result;
    }
}
