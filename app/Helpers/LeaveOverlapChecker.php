<?php

namespace App\Helpers;

use App\Models\Leave;

class LeaveOverlapChecker
{
    public static function checkOverLapping($userId, $startDate, $endDate)
    {
        return Leave::where('user_id', $userId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $startDate);
                });
            })
            ->exists();
    }
}
