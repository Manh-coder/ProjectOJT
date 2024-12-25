<!-- resources/views/admin/leave_requests/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-700">Manage Leave Requests</h2>

        <div class="mt-6">
            <table class="min-w-full table-auto bg-white shadow-md rounded-md">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">Employee</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">Start Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">End Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">Reason</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaveRequests as $leaveRequest)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $leaveRequest->user->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $leaveRequest->start_date }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $leaveRequest->end_date }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $leaveRequest->reason }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                @if ($leaveRequest->status == 'approved')
                                    <span class="badge text-bg-primary">Approved</span>
                                    {{-- <span class="text-green-500">Approved</span> --}}
                                @elseif($leaveRequest->status == 'rejected')
                                    {{-- <span class="text-red-500">Rejected</span> --}}
                                    <span class="badge text-bg-danger">Rejected</span>
                                @else
                                    {{-- <span class="text-yellow-500">Pending</span> --}}
                                    <span class="badge text-bg-warning">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                @if ($leaveRequest->status == 'pending')
                                    <form action="{{ route('admin.leave_requests.update', $leaveRequest->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" name="action" value="approved"
                                            class="text-green-500">Approve</button>

                                            |
                                            
                                        <button type="submit" name="action" value="rejected"
                                            class="text-red-500">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
