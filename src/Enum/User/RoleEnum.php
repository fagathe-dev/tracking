<?php
namespace App\Enum\User;

use App\Entity\User;
use App\Enum\EnumInterface;

final class RoleEnum implements EnumInterface
{

    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_API = 'ROLE_API';

    /**
     * @return array
     */
    public static function cases(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_USER,
            self::ROLE_API,
        ];
    }

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(int|string $value = self::ROLE_USER): string
    {
        return match ($value) {
            self::ROLE_ADMIN => 'Administrateur',
            self::ROLE_API => 'Utilisateur Api',
            default => 'Utilisateur',
        };
    }

    public static function getRoles(User $user): string
    {
        $roles = [];
        foreach ($user->getRoles() as $key => $role) {
            $roles = [...$roles, self::match($role)];
        }

        return join(', ', $roles);
    }

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'Administrateur' => self::ROLE_ADMIN,
            'Utilisateur' => self::ROLE_USER,
            'Utilisateur Api' => self::ROLE_API,
        ];
    }

}