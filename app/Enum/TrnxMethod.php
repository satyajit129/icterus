<?php

namespace App\Enum;

class TrnxMethod
{
    const BKASH  = 1;
    const ROCKET = 2;
    const NAGAD  = 3;
    const BANK   = 4;
    const CASH   = 5;

    /**
     * Get all method values
     */
    public static function all(): array
    {
        return [
            self::BKASH,
            self::ROCKET,
            self::NAGAD,
            self::BANK,
            self::CASH,
        ];
    }

    /**
     * Get labels with keys
     */
    public static function labels(): array
    {
        return [
            self::BKASH  => 'bKash',
            self::ROCKET => 'Rocket',
            self::NAGAD  => 'Nagad',
            self::BANK   => 'Bank Transfer',
            self::CASH   => 'Cash',
        ];
    }

    /**
     * Get label by int value
     */
    public static function label(int $key): string
    {
        return self::labels()[$key] ?? 'Unknown';
    }
}
