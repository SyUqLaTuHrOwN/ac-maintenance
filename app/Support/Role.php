<?php

namespace App\Support;

final class Role
{
    public const ADMIN   = 'admin';
    public const TEKNISI = 'teknisi';
    public const CLIENT  = 'client';

    public static function all(): array
    {
        return [self::ADMIN, self::TEKNISI, self::CLIENT];
    }
}
