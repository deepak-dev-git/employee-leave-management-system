<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\LeaveStatus;
use App\Enums\LeaveType;
use App\Http\Requests\SaveLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use Illuminate\Support\Facades\DB;
use App\Events\LeaveCreated;
use App\Events\LeaveStatusUpdated;
use App\Models\LeaveSetting;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeavesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole(UserType::ADMIN);
        $query = Leave::with('user');
        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }
        if ($isAdmin && $request->filled('employee')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->employee . '%');
            });
        }
        $leaves = $query->orderBy('start_date', 'desc')->paginate(10);
        $leaves->appends($request->all());

        return view('leaves.index', [
            'leaves' => $leaves,
            'isAdmin' => $isAdmin,
            'statuses' => \App\Enums\LeaveStatus::cases(),
            'types' => \App\Enums\LeaveType::cases(),
        ]);
    }


    public function create()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole(UserType::ADMIN);
        if ($isAdmin) {
            abort(403);
        }

        return view('leaves.create_update', compact('isAdmin'));
    }

    public function store(SaveLeaveRequest $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole(UserType::ADMIN);
        if ($isAdmin) {
            abort(403);
        }
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $startDate = $data['start_date'];
            $endDate = ($data['is_one_day'] ?? false) ? $startDate : $data['end_date'];
            $leaveData = [
                'user_id' => $user->id,
                'type' => $data['type'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'request_reason' => $data['request_reason'] ?? null,
                'updated_at' => null
            ];
            $leave = new Leave();
            $leave->fill($leaveData);
            $leave->timestamps = false;
            $leave->created_at = now();
            $leave->updated_at = null;
            $leave->save();

            event(new LeaveCreated($leave));
            DB::commit();
            return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }
    public function show(Leave $leave)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole(UserType::ADMIN);
        $isOneDayLeave = $leave->start_date == $leave->end_date;
        return view('leaves.view', compact('leave', 'isAdmin', 'isOneDayLeave'));
    }

    public function edit(Leave $leave)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole(UserType::ADMIN);
        if (!$isAdmin) {
            abort(403);
        }
        $isOneDayLeave = $leave->start_date == $leave->end_date;

        return view('leaves.create_update', compact('leave', 'isOneDayLeave', 'isAdmin'));
    }

    public function update(UpdateLeaveRequest $request, Leave $leave)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole(UserType::ADMIN);
        if (!$isAdmin) {
            abort(403);
        }
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $leave->fill([
                'status' => $data['status'],
                'admin_remarks' => $data['admin_remarks'] ?? null,
            ])->save();
            event(new LeaveStatusUpdated($leave));
            DB::commit();
            return redirect()->route('leaves.index')->with('success', 'Leave  status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function updateLeaveSetting(Request $request)
    {
        $request->validate([
            'allocated_days' => 'required|integer|min:0',
        ]);
        LeaveSetting::updateOrCreate(
            ['year' => date('Y')],
            ['allocated_days' => $request->allocated_days]
        );
        return redirect()->back()->with('success', 'Allocated leave count updated successfully.');
    }

    public function export(Request $request, $format)
    {
        if ($format === 'excel') {
            $leaves = Leave::with('user')->get();
            return Excel::download(new LeavesExport($leaves), 'leaves.xlsx');
        }
        if ($format === 'pdf') {
            $leaves = Leave::with('user')->get();
            $pdf = Pdf::loadView('exports.leaves', compact('leaves'));
            return $pdf->download('leaves.pdf');
        }
        abort(404);
    }
}
