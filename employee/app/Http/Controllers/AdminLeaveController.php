<?php

namespace App\Http\Controllers;

use App\Mail\LeaveRequestSubmitted;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;

use App\Mail\LeaveRequestStatusUpdated;
use Illuminate\Support\Facades\Mail;

class AdminLeaveController extends Controller
{
    // Hiển thị danh sách đơn xin nghỉ phép
    public function index()
    {
        $leaveRequests = LeaveRequest::with('user')->latest('created_at')->get();
        return view('admin.leave_requests.index', compact('leaveRequests'));
    }

    // Phê duyệt hoặc từ chối đơn xin nghỉ phép
    public function update(Request $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($request->action == 'approved') {
            $leaveRequest->update(['status' => 'approved']);

            // Cập nhật số ngày đã sử dụng
            $leaveBalance = $leaveRequest->user->leaveBalance;
            $usedDays     = \Carbon\Carbon::parse($leaveRequest->start_date)->diffInDays(\Carbon\Carbon::parse($leaveRequest->end_date)) + 1;

            $leaveBalance->increment('used_leave_days', $usedDays);
        } else {
            $leaveRequest->update(['status' => 'rejected']);
        }


        // Gửi email thông báo
    Mail::to($leaveRequest->user->email)->send(new LeaveRequestStatusUpdated($leaveRequest));


        return redirect()->back()->with('success', 'Leave request updated successfully!');
    }
}
