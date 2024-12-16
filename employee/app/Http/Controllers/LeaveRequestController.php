<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequestSubmitted;
use App\Mail\LeaveRequestStatusChanged;


class LeaveRequestController extends Controller
{
    // Hiển thị danh sách đơn xin nghỉ phép
    public function index()
    {
        $leaveRequests = LeaveRequest::where('user_id', Auth::id())->get();
        return view('leave_requests.index', compact('leaveRequests'));
    }

    // Form đăng ký nghỉ phép
    public function create()
    {
        return view('leave_requests.create');
    }

    // Xử lý logic đăng ký nghỉ phép
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $user = Auth::user();
        $leaveBalance = LeaveBalance::where('user_id', $user->id)->first();

        $requestedDays = \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1;

        if ($requestedDays > $leaveBalance->total_leave_days - $leaveBalance->used_leave_days) {
            return back()->withErrors(['error' => 'You have exceeded your leave days limit. Please apply for unpaid leave.']);
        }

        // Lưu đơn xin nghỉ phép
        LeaveRequest::create([
            'user_id' => $user->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Gửi email thông báo
        Mail::to($user->email)->send(new \App\Mail\LeaveRequestSubmitted());

        return redirect()->route('leave_requests.index')->with('success', 'Leave request submitted successfully!');
    }

    public function destroy($id)
{
    $leaveRequest = LeaveRequest::findOrFail($id);

    // Xóa bản ghi
    $leaveRequest->delete();

    // Chuyển hướng với thông báo thành công
    return redirect()->route('leave_requests.index')->with('success', 'Leave request deleted successfully.');
}


public function update(Request $request, $id)
{
    $leaveRequest = LeaveRequest::findOrFail($id);

    $status = $request->status;
    if (!in_array($status, ['approved', 'rejected'])) {
        return redirect()->back()->with('error', 'Invalid status.');
    }

    $leaveRequest->update(['status' => $status]);

    // Gửi email thông báo cho nhân viên
    $message = $status == 'approved' ? 'Your leave request has been approved.' : 'Your leave request has been rejected.';
    Mail::to($leaveRequest->user->email)->send(new LeaveRequestStatusChanged($leaveRequest, $message));

    return redirect()->route('leave_requests.index')->with('success', 'Leave request updated successfully.');
}

}
