<?php

namespace App\Enums;

enum LeaveType: string
{
    case SICK = 'Sick';
    case CASUAL = 'Casual';
    case OTHERS = 'Others';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
