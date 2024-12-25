<!-- resources/views/leave_requests/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-700">Your Leave Requests</h2>

            <a href="{{ route('leave_requests.create') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Create Leave Request
            </a>
        </div>
        <div>
            <h6>Tổng số phép <span class="badge badge-secondary">{{ $leaveBalnace->total_leave_days }}</span></h6>
            <h6>Tổng số phép đã sử dụng <span class="badge badge-secondary">{{ $leaveBalnace->used_leave_days }}</span></h6>
        </div>

        <div class="mt-6">
            <table class="min-w-full table-auto bg-white shadow-md rounded-md">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">Start Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">End Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">Reason</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaveRequests as $leaveRequest)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $leaveRequest->start_date }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $leaveRequest->end_date }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $leaveRequest->reason }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                @if ($leaveRequest->status == 'approved')
                                    <span class="text-green-500">Approved</span>
                                @elseif($leaveRequest->status == 'rejected')
                                    <span class="text-red-500">Rejected</span>
                                @else
                                    <span class="text-yellow-500">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                <form action="{{ route('leave_requests.destroy', $leaveRequest->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition"
                                        onclick="return confirm('Are you sure you want to delete this leave request?')">
                                        Delete
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
