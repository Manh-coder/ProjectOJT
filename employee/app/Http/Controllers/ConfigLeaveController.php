<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use App\Models\User;
use Illuminate\Http\Request;

class ConfigLeaveController extends Controller
{
    public function index(Request $request)
    {
        $entries   = LeaveBalance::with('user')->get();
        $users     = User::doesntHave('leaveBalance')->where('type', 2)->get();
        $userEdits = User::where('type', 2)->get();
        return view('admin.configLeaves.index', compact('entries', 'users', 'userEdits'));
    }
    public function store(Request $request)
    {
        LeaveBalance::updateOrCreate(['id' => $request->input('id')], [
            'user_id'          => $request->input('user_id'),
            'total_leave_days' => $request->input('total_leave_days')
        ]);
        return to_route('config-leave.index');
    }
}
