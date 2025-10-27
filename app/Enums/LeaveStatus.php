<?php

namespace App\Enums;

enum LeaveStatus: string
{
    case APPROVED = 'Approved';
    case REJECTED = 'Rejected';
    case PENDING = 'Pending';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
