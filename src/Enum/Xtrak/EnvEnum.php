<?php

namespace App\Enum\Xtrak;

use App\Enum\EnumInterface;

final class EnvEnum implements EnumInterface
{

    
    public const ENV_DEV = 'DEV';
    public const ENV_PREPROD = 'PREPROD';
    public const ENV_PROD = 'PROD';

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(string|int $value = ''): string
    {
        return match ($value) {
            self::ENV_PREPROD => self::ENV_PREPROD,
            self::ENV_PROD => self::ENV_PROD,
            default => self::ENV_DEV,
        };
    }


    /**
     * @return array
     */
    public static function cases(): array
    {
        return [
            self::ENV_DEV,
            self::ENV_PREPROD,
            self::ENV_PROD
        ];
    }

    /**
     * choices
     *
     * @return array
     */
    public static function choices(): array
    {
        return [
            self::ENV_DEV => self::ENV_DEV,
            self::ENV_PREPROD => self::ENV_PREPROD,
            self::ENV_PROD => self::ENV_PROD,
        ];
    }
}
