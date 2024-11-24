<?php

use App\Http\Controllers\Admin\EmailScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserAttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalaryLevelController;
use App\Http\Middleware\CheckAdmin;
use App\Mail\SendMail;
use App\Http\Controllers\NotificationScheduleController;


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
        
        Route::resource('departments', DepartmentController::class);
        
        Route::resource('user-attendance', UserAttendanceController::class);
        
        Route::post('/admin/attendance/{id}/confirm-explanation', [EmployeeController::class, 'confirmExplanation'])
        ->name('admin.attendance.confirmExplanation');

        Route::post('/admin/attendance/{id}/reject-explanation', [EmployeeController::class, 'rejectExplanation'])
        ->name('admin.attendance.reject');



        Route::post('employees/import', [EmployeeController::class, 'import'])->name('employees.import');
        //Route::get('employees/export-phpspreadsheet', [EmployeeController::class, 'exportWithPhpSpreadsheet'])->name('employees.export-phpspreadsheet');
        Route::get('employees-export', [EmployeeController::class, 'exportWithPhpSpreadsheet'])->name('employees.export');
        Route::post('employees-change-password/{id}', [EmployeeController::class, 'changePassword'])->name('employees.change.password');



        Route::get('/email-schedule', [EmailScheduleController::class, 'index'])->name('admin.email-schedule');
        Route::post('/email-schedule', [EmailScheduleController::class, 'update'])->name('admin.email-schedule.update');
    });

    Route::get('/notification-schedule', [NotificationScheduleController::class, 'index'])->name('notification-schedule');
    Route::post('/notification-schedule', [NotificationScheduleController::class, 'update'])->name('notification-schedule.update');
    Route::get('/guest/notification_schedule', [NotificationScheduleController::class, 'index'])->name('guest.notification_schedule');

        Route::post('employees-action', [EmployeeController::class, 'action'])->name('employees.action');
        Route::post('/employees/{attendanceId}/submit-explanation', [EmployeeController::class, 'submitExplanation'])->name('employees.submitExplanation');
    // Các route liên quan đến profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});





// Đăng ký các route liên quan đến xác thực (auth)
require __DIR__ . '/auth.php';

