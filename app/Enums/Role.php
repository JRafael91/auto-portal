<?php

namespace App\Enums;

enum Role: string
{
    case USER = 'USER';
    case ADMIN = 'ADMIN';

    public function getValue(): string
    {
        return match ($this) {
            self::USER => 'USUARIO',
            self::ADMIN => 'ADMINISTRADOR'
        };
    }
}
