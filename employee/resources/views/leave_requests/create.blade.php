@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-semibold text-gray-700">Request Leave</h2>

    <form action="{{ route('leave_requests.store') }}" method="POST" class="mt-6">
        @csrf

        <div class="mb-4">
            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" id="start_date" name="start_date" 
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" id="end_date" name="end_date" 
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
            <textarea id="reason" name="reason" rows="4" 
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </textarea>
        </div>

        <button type="submit" 
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md 
            hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Submit Request
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        startDate.addEventListener('change', function () {
            const selectedDate = startDate.value;
            endDate.min = selectedDate; // Thiết lập ngày tối thiểu cho End Date
        });
    });
</script>
@endsection
