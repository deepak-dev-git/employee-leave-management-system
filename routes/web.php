<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard.index');
    }
    return view('auth.login');
});


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/leave-settings/update', [LeaveController::class, 'updateLeaveSetting'])->name('leave-settings.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus']);
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    });
    Route::prefix('leaves')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('leaves.index');
        Route::get('/create', [LeaveController::class, 'create'])->name('leaves.create');
        Route::post('/', [LeaveController::class, 'store'])->name('leaves.store');
        Route::get('/{leave}', [LeaveController::class, 'show'])->name('leaves.show');
        Route::get('/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
        Route::patch('/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
        Route::delete('/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
        Route::patch('/{leave}/toggle-status', [LeaveController::class, 'toggleStatus'])->name('leaves.toggleStatus');
        Route::get('/export/{format}', [LeaveController::class, 'export'])->name('leaves.export');
    });
});
