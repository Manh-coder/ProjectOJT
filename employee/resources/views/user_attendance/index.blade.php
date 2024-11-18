{{-- @extends('layouts.app')

@section('title', 'User-Attendance List')

@section('content')
    <h2 style="font-size: 1.5rem; font-weight: bold;">User-Attendance List</h2><br>

<!-- Search Form -->
<form action="{{ route('user_attendance.index') }}" method="GET" class="mb-4">
    <input type="text" name="keyword" placeholder="Search user attendance..." class="form-input" value="{{ request()->get('keyword') }}">
    <button type="submit" class="btn">Search</button>
</form>

    <a href="{{ route('user-attendance.create') }}" class="btn">New User-Attendance</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Time</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->id }}</td>
                    <td>{{ $attendance->employee->name }}</td>
                    <td>{{ $attendance->time }}</td>
                    <td>{{ $attendance->type }}</td>
                    <td>
                        
                        <a href="{{ route('user-attendance.edit', $attendance->id) }}" class="btn">Edit</a>
                        <form action="{{ route('user-attendance.destroy', $attendance->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection --}}


@extends('layouts.app')

@section('title', 'User_attendance List')

<style>
    .table {
        width: 100%;
        margin: 20px 0;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    .form-input {
        padding: 0.3rem 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid #ddd;
        font-size: 0.875rem;
        margin-right: 0.5rem;
    }

    .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
        border-radius: 0.25rem;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary,
    .btn-secondary,
    .btn-danger {
        color: #fff;
        border: none;
    }

    .btn-primary {
        background-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #bd2130;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
</style>

@section('content')
    <h2 style="font-size: 1.5rem; font-weight: bold;">User attendance List</h2><br>

    {{-- <form action="{{ route('user-attendance.index') }}" method="GET" class="mb-4">
        <input type="text" name="keyword" placeholder="Tìm kiếm chấm công..." class="form-input" value="{{ request()->get('keyword') }}">
        <button type="submit" class="btn-sm btn-secondary">Search</button>
    </form>

    <a href="{{ route('user-attendance.create') }}" class="btn-sm btn-primary" style="margin-bottom: 1rem; display: inline-block;">Thêm Chấm Công Mới</a> --}}

    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Employee</th>
                <th>Time</th>
                {{-- <th>type</th> --}}
                <th>Time of work</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $key => $attendance)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $attendance->user?->name }}</td>
                    <td>
                        Checkin: {{ $attendance->datetime_ci }} <br>
                        Checkout: {{ $attendance->datetime_co }}

                    </td>
                    {{-- <td>{{ $attendance->type }}</td> --}}
                    <td>
                        @if ($attendance->datetime_co)
                            @php
                                $firstCheckInTime = \Carbon\Carbon::parse($attendance->datetime_ci);
                                $lastCheckOutTime = \Carbon\Carbon::parse($attendance->datetime_co);
                                $total = $lastCheckOutTime->diffInMinutes($firstCheckInTime);
                                $hour = round($total/60,2);
                            @endphp
                            {{ $hour }} hours
                        @else
                            -
                        @endif

                    </td>
                    <td>
                        <a href="{{ route('user-attendance.show', $attendance->user_id) }}" class="btn-sm btn-primary">Details</a>
                        {{-- <a href="{{ route('user-attendance.edit', $attendance->id) }}" class="btn-sm btn-primary" style="background-color: #ffc107; color: #000;">Edit</a> --}}
                        {{-- <form action="{{ route('user-attendance.destroy', $attendance->user_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-danger">Delete</button></button>
                        </form> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $attendances->links() }}
    </div>
@endsection
