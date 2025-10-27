<?php

namespace App\Http\Controllers;

use App\Helpers\LeaveHelper;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query();
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }
        if (request()->filled('type')) {
            $query->where('type', request('type'));
        }
        if (request()->filled('username')) {
            $query->where('name', 'like', '%' . request('username') . '%');
        }
        $users = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name'); // ['admin' => 'admin', 'user' => 'user']
        return view('users.create_update', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|exists:roles,name',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'pass' => $request->password,
            'status' => $request->status ?? 0,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'user created successfully.');
    }

    public function show(User $user)
    {
        $currentYear = now()->year;
        $allocatedLeave = LeaveHelper::getAllocatedDays();
        $usedLeaves = LeaveHelper::getUsedLeavesForYear($user->id, $currentYear);
        $remainingLeaves = max(0, $allocatedLeave - $usedLeaves);
        return view('users.view', compact('user', 'allocatedLeave', 'usedLeaves', 'remainingLeaves'));
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name');
        return view('users.create_update', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|exists:roles,name',
            'password' => 'nullable|string|min:6',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'pass' => $request->password ? bcrypt($request->password) : $user->password,
            'status' => $request->status ?? 0,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'user updated successfully.');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'user deleted successfully.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'status'  => $user->status
        ]);
    }
}
