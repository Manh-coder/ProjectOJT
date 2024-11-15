<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserAttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalaryLevelController;
use App\Http\Middleware\CheckAdmin;
use App\Mail\SendMail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Đây là nơi để đăng ký các route cho ứng dụng web. Các route này
| được tải bởi RouteServiceProvider và sẽ được gán vào nhóm middleware "web".
|
*/

// Route cho trang chủ
Route::get('/', function () {
   
    return view('welcome');
});

// Route cho dashboard, yêu cầu xác thực và xác minh email



// Nhóm các route yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexGuest'])->middleware(['auth', 'verified'])->name('dashboard.guest');

    // ADMIN
    Route::group(['middleware' => CheckAdmin::class, 'prefix' => '/admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
        // Employee routes
        Route::resource('salary-levels', SalaryLevelController::class);
        Route::resource('employees', EmployeeController::class);
        // Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        // // Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
        // Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
        // Department routes
        Route::resource('departments', DepartmentController::class);
        // Route::get('/departments', [DepartmentController::class, 'search'])->name('departments.index');
        // Route::get('/departments/search', [DepartmentController::class, 'search'])->name('departments.search');
        // Route::get('departments/{id}/details', [DepartmentController::class, 'show'])->name('departments.details');

        // User Attendance routes
        Route::resource('user-attendance', UserAttendanceController::class);
        // Route::get('/user_attendance', [UserAttendanceController::class, 'search'])->name('user_attendance.index');
        // Route::get('/user_attendance/search', [UserAttendanceController::class, 'search'])->name('user-attendance.search');
        // Route::get('user-attendance/{userId}/details', [UserAttendanceController::class, 'show'])->name('user-attendance.details');

        Route::post('employees/import', [EmployeeController::class, 'import'])->name('employees.import');
        //Route::get('employees/export-phpspreadsheet', [EmployeeController::class, 'exportWithPhpSpreadsheet'])->name('employees.export-phpspreadsheet');
        Route::get('employees-export', [EmployeeController::class, 'exportWithPhpSpreadsheet'])->name('employees.export');
        Route::post('employees-change-password/{id}', [EmployeeController::class, 'changePassword'])->name('employees.change.password');

    });
    Route::post('employees-action', [EmployeeController::class, 'action'])->name('employees.action');
    // Các route liên quan đến profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});





// Đăng ký các route liên quan đến xác thực (auth)
require __DIR__ . '/auth.php';

