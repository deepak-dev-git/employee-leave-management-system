<?php

namespace App\Helpers;

use App\Enums\LeaveStatus;
use App\Models\LeaveSetting;
use Carbon\Carbon;
use App\Models\Leave;

class LeaveHelper
{
    public static function getAllocatedDays($year = null)
    {
        $year = $year ?? date('Y');
        return LeaveSetting::where('year', $year)->value('allocated_days') ?? 0;
    }

    public static function getUsedLeavesForYear($employeeId, $year)
    {
        return Leave::where('user_id', $employeeId)
            ->where('status', LeaveStatus::APPROVED)
            ->get()
            ->sum(function ($leave) use ($year) {
                $start = Carbon::parse($leave->start_date)->startOfDay();
                $end = Carbon::parse($leave->end_date)->endOfDay();
                $yearStart = Carbon::create($year, 1, 1)->startOfDay();
                $yearEnd = Carbon::create($year, 12, 31)->endOfDay();
                $effectiveStart = $start->lessThan($yearStart) ? $yearStart : $start;
                $effectiveEnd = $end->greaterThan($yearEnd) ? $yearEnd : $end;

                if ($effectiveEnd->lt($effectiveStart)) {
                    return 0;
                }

                return $effectiveStart->diffInDays($effectiveEnd) + 1;
            });
    }

    public static function getLeavesTakenForYear($year, $userId=null, $status = null)
    {
        return Leave::when($userId, function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where(function ($q) use ($year) {
                $q->whereYear('start_date', $year)
                    ->orWhereYear('end_date', $year);
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            });
    }
}
