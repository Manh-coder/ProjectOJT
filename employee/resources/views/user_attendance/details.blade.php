{{-- @extends('layouts.app')

@section('title', 'User Attendance Details')

<style>
    .attendance-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
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
</style>

@section('content')
<div class="attendance-card">
    <h2>User Attendance Details</h2>

    <div class="attendance-info"><strong>User ID:</strong> {{ $checkIn->user_id ?? $checkOut->user_id ?? 'Not available' }}</div>
    
    <div class="attendance-info"><strong>Check In at:</strong> {{ $checkIn->created_at ?? $checkOut->created_at ?? 'Not available' }}</div>
    <div class="attendance-info"><strong>Check Out at:</strong> {{ $checkIn->updated_at ?? $checkOut->updated_at ?? 'Not available' }}</div>

    <a href="{{ route('user_attendance.index') }}" class="btn">Back to Attendance List</a>
</div>
@endsection --}}


@extends('layouts.app')

@section('title', 'Details User_attendence')

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
</style>

@section('content')


    <div>
        <h2>Attendance_user Detail</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Check in time</th>
                    <th>Check out time</th>
                    {{-- <th>type</th> --}}
                    <th>Time of work</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entries as $key => $attendance)
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
                    </tr>
                @endforeach
            </tbody>
            <a href="{{ route('user-attendance.index') }}" class="btn">Back User_attendance Lists</a>
        </table>
    </div>





@endsection
