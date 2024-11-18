@extends('layouts.app')

@section('title', 'Details User Attendance')

<style>
    .attendance-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        max-width: 700px;
        margin: 2rem auto;
        text-align: left;
    }

    .attendance-card h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
        color: #333;
        border-bottom: 2px solid #3182CE;
        display: inline-block;
        padding-bottom: 0.5rem;
    }

    .attendance-info {
        margin-bottom: 1rem;
        font-size: 1.1rem;
        color: #555;
    }

    .attendance-info strong {
        color: #222;
    }

    .btn {
        background-color: #3182CE;
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        text-decoration: none;
        display: inline-block;
        margin-top: 1.5rem;
        transition: background-color 0.3s ease;
        font-size: 1rem;
    }

    .btn:hover {
        background-color: #2B6CB0;
    }

    .btn-confirm {
        background-color: #28a745; /* Màu xanh lá */
        color: #fff;
        padding: 0.3rem 1rem;  /* Thay đổi padding để nút nhỏ hơn */
        border-radius: 0.5rem;
        font-size: 0.875rem; /* Giảm kích thước font */
        transition: background-color 0.3s ease;
    }

    .btn-confirm:hover {
        background-color: #218838; /* Màu xanh đậm */
    }

</style>

@section('content')

<div>
    <h2>Attendance User Detail</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Ngày</th>
                <th>Check in time</th>
                <th>Check out time</th>
                <th>Time of work</th>
                <th>Status</th>
                <th>Explanation</th>
                <th>Action</th> <!-- Thêm cột Action cho nút xác nhận -->
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $attendance)
                <tr>
                    <td>{{ date('Y-m-d', strtotime($attendance->datetime_ci)) }}</td>
                    <td>{{ date('H:i:s', strtotime($attendance->datetime_ci)) }}</td>
                    <td>{{ date('H:i:s', strtotime($attendance->datetime_co)) }}</td>

                    <td>
                        @if ($attendance->datetime_co)
                            @php
                                $firstCheckInTime = \Carbon\Carbon::parse($attendance->datetime_ci);
                                $lastCheckOutTime = \Carbon\Carbon::parse($attendance->datetime_co);
                                $total = $lastCheckOutTime->diff($firstCheckInTime);
                                $format = $total->format('%H:%I:%s');
                            @endphp
                            {{ $format }}
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        @if ($attendance->status == 'pending')
                            <span class="text-warning">Pending</span>
                        @elseif ($attendance->status == 'invalid')
                            <span class="text-danger">Invalid</span>
                        @elseif ($attendance->status == 'valid')
                            <span class="text-success">Valid</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>

                    <td>
                        @if ($attendance->explanation)
                            <span>{{ $attendance->explanation }}</span>
                        @else
                            <span>-</span>
                        @endif
                    </td>

                    <td>
                        @if ($attendance->status == 'pending')
                            <!-- Nút xác nhận giải trình cho admin -->
                            <form action="{{ route('admin.attendance.confirmExplanation', $attendance->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-confirm">Confirm</button>
                            </form>
                        @else
                            <span>-</span>
                        @endif


                        @if ($attendance->status == 'pending')
                            <!-- Nút xác nhận giải trình cho admin -->
                            <form action="{{ route('admin.attendance.reject', $attendance->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-confirm">Reject</button>
                            </form>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('user-attendance.index') }}" class="btn">Back to User Attendance List</a>
</div>

@endsection
