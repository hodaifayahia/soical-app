<?php

namespace App\Enums;

// App\Enums\GroupRoleEnum.php
enum GroupRoleEnum: string
{
    case ADMIN = 'ADMIN';
    case USER = 'USER';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

