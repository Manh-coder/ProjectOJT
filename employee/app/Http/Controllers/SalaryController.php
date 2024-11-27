<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use App\Models\SalaryLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function index()
    {
        // Get the list of salaries that have been calculated
        $salaries = Salary::with('user')->get();
        return view('admin.salary.index', compact('salaries'));
    }

    public function create()
    {
        // Get the list of employees and salary levels
        $users = User::all();
        return view('admin.salary.create', compact('users'));
    }

    public function calculateAll()
    {
        // Get all users, including admin
        $users = User::all();
    
        foreach ($users as $user) {
            // Check if the user has a salary level
            $salaryLevel = $user->salaryLevel;
            if (!$salaryLevel) {
                continue; // Skip user if they don't have a salary level
            }
    
            // Calculate valid and invalid attendance days for the current month
            $currentMonth = now()->startOfMonth();
            $nextMonth = now()->startOfMonth()->addMonth();
    
            $validDays = $user->attendances()
                ->whereBetween('date', [$currentMonth, $nextMonth])
                ->where('status', 'valid')
                ->count();
    
            $invalidDays = $user->attendances()
                ->whereBetween('date', [$currentMonth, $nextMonth])
                ->where('status', 'invalid')
                ->count();
    
            // Calculate salary
            $dailySalary = $salaryLevel->daily;
            $totalSalary = $validDays * $dailySalary; // Only count valid days
    
            // Check if salary for this month already exists
            $existingSalary = Salary::where('user_id', $user->id)
                ->where('month', $currentMonth->format('Y-m'))
                ->first();
    
            if (!$existingSalary) {
                // Save salary information if not already saved
                Salary::create([
                    'user_id' => $user->id,
                    'month' => $currentMonth->format('Y-m'),
                    'valid_days' => $validDays,
                    'invalid_days' => $invalidDays,
                    'salary' => $totalSalary,
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                ]);
            }
        }
    
        // Redirect to the salary list with success message
        return redirect()->route('salary.index')->with('success', 'Salaries have been calculated for all employees!');
    }
    

    // Return the valid and invalid attendance days for a specific employee
    public function getAttendanceDays($userId)
    {
        // Get the user
        $user = User::with('attendances')->findOrFail($userId);

        // Determine the current month
        $currentMonth = now()->startOfMonth();
        $nextMonth = now()->startOfMonth()->addMonth();

        // Calculate the valid and invalid attendance days
        $validDays = $user->attendances()
            ->whereBetween('date', [$currentMonth, $nextMonth])
            ->where('status', 'valid')
            ->count();

        $invalidDays = $user->attendances()
            ->whereBetween('date', [$currentMonth, $nextMonth])
            ->where('status', 'invalid')
            ->count();

        return response()->json([
            'valid_days' => $validDays,
            'invalid_days' => $invalidDays,
        ]);
    }

    // Store the salary calculation for a specific employee
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Get the user
        $user = User::with('salaryLevel')->findOrFail($request->user_id);
        $salaryLevel = $user->salaryLevel;

        if (!$salaryLevel) {
            return redirect()->back()->withErrors(['error' => 'The employee does not have a salary level. Please set the salary level before calculating salary.']);
        }

        // Determine the current month
        $currentMonth = now()->startOfMonth();
        $nextMonth = now()->startOfMonth()->addMonth();

        // Get the attendance data from the user_attendance table
        $validDays = $user->attendances()
            ->whereBetween('date', [$currentMonth, $nextMonth])
            ->where('status', 'valid')
            ->count();

        $invalidDays = $user->attendances()
            ->whereBetween('date', [$currentMonth, $nextMonth])
            ->where('status', 'invalid')
            ->count();

        // Calculate salary
        $dailySalary = $salaryLevel->daily;
        $totalSalary = $validDays * $dailySalary; // Only count valid days

        // Check if the salary for this month has already been calculated
        $existingSalary = Salary::where('user_id', $request->user_id)
            ->where('month', $currentMonth->format('Y-m'))
            ->first();

        if ($existingSalary) {
            return redirect()->back()->withErrors(['error' => 'The salary for this employee has already been calculated for the current month.']);
        }

        // Save the salary information into the database
        Salary::create([
            'user_id' => $request->user_id,
            'month' => $currentMonth->format('Y-m'),
            'valid_days' => $validDays,
            'invalid_days' => $invalidDays,
            'salary' => $totalSalary,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('salary.index')->with('success', 'The salary has been calculated and saved successfully!');
    }
}
