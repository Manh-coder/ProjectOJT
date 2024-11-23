@extends('layouts.app')

@section('title', 'Details User Attendance')

<style>
    .btn-info {
    font-size: 0.875rem; /* Giảm kích thước font của nút "View Details" */
    padding: 0.25rem 0.75rem; /* Điều chỉnh padding nếu cần */
}
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

    .btn-reject {
        background-color: #dc3545; /* Màu đỏ */
        color: #fff;
        padding: 0.3rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: background-color 0.3s ease;
    }

    .btn-reject:hover {
        background-color: #c82333; /* Màu đỏ đậm */
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 10px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
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
                <th>Detail</th> <!-- Cột Detail thay thế Explanation và Actions -->
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
                        @if ($attendance->status == 'pending')
                            <!-- Nút View Details để mở modal -->
                            <button class="btn btn-info btn-sm" onclick="showModal('{{ $attendance->explanation }}', '{{ route('admin.attendance.confirmExplanation', $attendance->id) }}', '{{ route('admin.attendance.reject', $attendance->id) }}')">
                                View Details
                            </button>
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

<!-- Modal -->
<div id="attendanceModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Attendance Explanation</h3>
        <p><strong>Explanation:</strong> <span id="attendanceExplanation"></span></p>

        <form id="confirmForm" action="" method="POST">
            @csrf
            <button type="submit" class="btn-confirm">Confirm</button>
        </form>

        <form id="rejectForm" action="" method="POST">
            @csrf
            <button type="submit" class="btn-reject">Reject</button>
        </form>
    </div>
</div>

<script>
    function showModal(explanation, confirmUrl, rejectUrl) {
        // Hiển thị modal
        document.getElementById('attendanceModal').style.display = 'block';
        
        // Hiển thị giải thích
        document.getElementById('attendanceExplanation').innerText = explanation;
        
        // Cập nhật đường dẫn cho các form Confirm và Reject
        document.getElementById('confirmForm').action = confirmUrl;
        document.getElementById('rejectForm').action = rejectUrl;
    }

    function closeModal() {
        // Đóng modal
        document.getElementById('attendanceModal').style.display = 'none';
    }

    // Khi click ra ngoài modal, đóng modal
    window.onclick = function(event) {
        if (event.target == document.getElementById('attendanceModal')) {
            closeModal();
        }
    }
</script>

@endsection
