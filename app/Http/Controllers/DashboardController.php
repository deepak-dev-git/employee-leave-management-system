<?php

namespace App\Http\Controllers;

use App\Enums\LeaveStatus;
use App\Enums\UserType;
use App\Helpers\LeaveHelper;
use App\Models\Leave;
use App\Models\LeaveSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentYear = now()->year;

        $allocatedLeave = LeaveSetting::where('year', $currentYear)
            ->pluck('allocated_days')
            ->first() ?? 0;

        if ($user->hasRole(UserType::ADMIN)) {
            $employeeCount = User::where('type', UserType::EMPLOYEE)->count();
            $todayLeaveCount = Leave::whereDate('created_at', today())->count();
            $totalLeaveCount = Leave::whereYear('start_date', $currentYear)
                ->orWhereYear('end_date', $currentYear)
                ->count();

            $acceptedCount = LeaveHelper::getLeavesTakenForYear($currentYear, null, LeaveStatus::APPROVED)->count();
            $rejectedCount = LeaveHelper::getLeavesTakenForYear($currentYear, null, LeaveStatus::REJECTED)->count();
            $pendingCount = LeaveHelper::getLeavesTakenForYear($currentYear, null, LeaveStatus::PENDING)->count();

            $allocatedLeave = LeaveHelper::getAllocatedDays();

            return view('dashboard', compact(
                'employeeCount',
                'todayLeaveCount',
                'totalLeaveCount',
                'acceptedCount',
                'rejectedCount',
                'pendingCount',
                'allocatedLeave'
            ));
        } else {
            $employeeId = $user->id;
            $totalLeaveCount = LeaveHelper::getLeavesTakenForYear($currentYear, $employeeId)->count();
            $acceptedCount = LeaveHelper::getLeavesTakenForYear($currentYear, $employeeId, LeaveStatus::APPROVED)->count();
            $rejectedCount = LeaveHelper::getLeavesTakenForYear($currentYear, $employeeId, LeaveStatus::REJECTED)->count();
            $pendingCount = LeaveHelper::getLeavesTakenForYear($currentYear, $employeeId, LeaveStatus::PENDING)->count();

            $allocatedLeave = LeaveHelper::getAllocatedDays();
            $usedLeaves = LeaveHelper::getUsedLeavesForYear($employeeId, $currentYear);

            $remainingLeaves = max(0, $allocatedLeave - $usedLeaves);

            return view('dashboard', compact(
                'totalLeaveCount',
                'acceptedCount',
                'rejectedCount',
                'pendingCount',
                'remainingLeaves',
                'allocatedLeave'
            ));
        }
    }
}
