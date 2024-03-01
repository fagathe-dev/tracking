<?php

namespace App\Service\Token;

final class TokenGenerator
{

    private const NUMERIC = '1234567890';

    private const ALPHANUMERIC = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890';

    private const UPPERCASE = 'AZERTYUIOPQSDFGHJKLMWXCVBN';

    private const LOWERCASE = 'azertyuiopqsdfghjklmwxcvbn';

    /**
     * generate
     *
     * @param  int $length 
     * @param  string $chars 'all' | 'numeric' | 'uppercase' | 'lowercase'
     * @param  bool $unique
     * @return string
     */
    public function generate(?int $length = 10, ?string $type = 'all', ?bool $unique = false): string
    {
        $chars = match ($type) {
            'uppercase' => self::UPPERCASE,
            'lowercase' => self::LOWERCASE,
            'numeric' => self::NUMERIC,
            default => self::ALPHANUMERIC,
        };
        if ($unique) {
            return uniqid($this->generateRandomString($length, $chars), true);
        }

        return $this->generateRandomString($length, $chars);
    }

    /**
     * generateCode
     *
     * @return string
     */
    public function generateCode(): string
    {
        return $this->generateRandomString(6, self::NUMERIC);
    }

    /**
     * generateRandomString
     *
     * @param  mixed $length
     * @param  mixed $chars
     * @return string
     */
    public function generateRandomString(?int $length = 10, ?string $chars = self::ALPHANUMERIC): string
    {
        return substr(str_shuffle($chars), 0, $length);
    }
}
