<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Kiểm tra trạng thái check-in/check-out --}}
        @if ($entry && !empty($entry->datetime_ci) && empty($entry->datetime_co))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>{{ auth()->user()->name }}</strong>, Please checkout.
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            {{-- Hiển thị tổng thời gian làm việc --}}
            <div class="bg-blue-600 text-white shadow-md rounded-lg p-6 transform transition-transform hover:scale-105">
                <div>
                    <h3 class="text-1xl font-bold uppercase tracking-wide">Total Working Time Today</h3>

                    <br>
                    @if ($entry)
                        @if ($entry->datetime_co)
                            @php
                                $firstCheckInTime = \Carbon\Carbon::parse($entry->datetime_ci);
                                $lastCheckOutTime = \Carbon\Carbon::parse($entry->datetime_co);
                                $diff = $firstCheckInTime->diff($lastCheckOutTime);
                                $total = $diff->format('%H:%I:%S');
                            @endphp
                            <p class="text-3xl font-bold mb-2">{{ $total }}</p>
                        @endif
                    @endif
                    <br>

                    {{ date('d-m-Y') }}
                </div>
            </div>

            {{-- Form check-in/check-out --}}
            <div class="bg-green-600 text-white shadow-md rounded-lg p-6 transform transition-transform hover:scale-105">
                <div class="row">
                    <div class="col-md-2">Action:</div>
                    <div class="col-md-2">
                        <form action="{{ route('employees.action') }}" method="post" class="form-inline float-right">
                            @csrf
                            <input type="text" value="ci" name="action" style="display: none">
                            <button 
                                type="{{ !$entry?->datetime_ci ? 'submit' : 'button' }}" 
                                class="btn btn-primary btn-sm p-1 {{ $entry?->datetime_ci ? 'opacity-75' : '' }}" 
                                {{ $entry?->datetime_ci ? 'disabled' : '' }}>
                                Checkin
                            </button>
                        </form>
                    </div>
                    
                    {{-- <div class="col-md-2">
                        <form action="{{ route('employees.action') }}" method="post" class="form-inline float-right">
                            @csrf
                            <input type="text" value="co" name="action" style="display: none">
                            <button type="{{ !$entry?->datetime_co && !empty($entry->datetime_ci) ? 'submit' : 'button' }}" class="btn btn-primary btn-sm p-1 {{ $entry?->datetime_co ? 'opacity-75' : '' }}">Checkout</button>
                        </form>
                    </div> --}}

                    <div class="col-md-2">
                        <form action="{{ route('employees.action') }}" method="post" class="form-inline float-right">
                            @csrf
                            <input type="text" value="co" name="action" style="display: none">
                            <button 
                                type="{{ !empty($entry) && !$entry->datetime_co && !empty($entry->datetime_ci) && $entry->status != 'invalid' ? 'submit' : 'button' }}" 
                                class="btn btn-primary btn-sm p-1 {{ !empty($entry) && ($entry->datetime_co || $entry->status == 'invalid') ? 'opacity-75' : '' }}" 
                                {{ empty($entry) || $entry->status == 'invalid' || $entry->status == 'pending' ? 'disabled' : '' }}>
                                Checkout
                            </button>
                        </form>
                    </div>
                    
                    
                </div>
                <div class="row">
                    <div class="col-md-12 mt-6 bg-white text-gray-800 p-4 rounded-lg shadow">
                        @if ($entry)
                            <p>Check-in time: {{ $entry->datetime_ci }}</p>
                            <p>Check-out time: {{ $entry->datetime_co }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Giải trình lý do nếu trạng thái là "invalid" --}}
        @if ($entry && $entry->status == 'invalid')
            <div class="alert alert-warning">
                <strong>{{ auth()->user()->name }}</strong>, your check-in or check-out is invalid. Please provide an explanation.
                <form action="{{ route('employees.submitExplanation', $entry->id) }}" method="POST">
                    @csrf
                    <textarea name="explanation" placeholder="Provide your explanation here..." class="form-control" required></textarea>
                    <button type="submit" class="btn btn-warning mt-2">Submit Explanation</button>
                </form>
            </div>
        @endif

        {{-- Danh sách ngày công của nhân viên --}}
        <div class="mt-12">
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">Attendance Records</h3>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 bg-blue-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="py-3 px-6 bg-blue-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Check in time
                            </th>
                            <th class="py-3 px-6 bg-blue-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Check out time
                            </th>
                            <th class="py-3 px-6 bg-blue-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Time of work
                            </th>
                            <th class="py-3 px-6 bg-blue-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="py-3 px-6 bg-blue-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Explanation
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($entries as $key => $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ date('Y-m-d', strtotime($attendance->datetime_ci)) }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ date('H:i:s', strtotime($attendance->datetime_ci)) }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ date('H:i:s', strtotime($attendance->datetime_co)) }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">
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
                                <td class="py-4 px-6 text-sm text-gray-500">
                                    {{-- Hiển thị trạng thái --}}
                                    @if ($attendance->status == 'pending')
                                    <span class="text-yellow-600 font-bold" style="color: rgb(255, 204, 0) !important;">Pending</span>
                                    @elseif ($attendance->status == 'invalid')
                                    <span class="text-red-500 font-bold" style="color: red !important;">Invalid</span>
                                    @elseif ($attendance->status == 'valid')
                                    <span class="text-green-500 font-bold" style="color: green !important;">Valid</span>
                                    @else
                                        <span class="text-gray-500">N/A</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($attendance->explanation)
                                        <span>{{ $attendance->explanation }}</span>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
     {{-- Phân trang đẹp hơn với Bootstrap --}}
<div class="mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            {{-- Nút Previous --}}
            <li class="page-item {{ $entries->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $entries->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            {{-- Các trang giữa --}}
            @foreach ($entries->getUrlRange(1, $entries->lastPage()) as $page => $url)
                <li class="page-item {{ $page == $entries->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Nút Next --}}
            <li class="page-item {{ $entries->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $entries->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
        </div>        
    </div>

  
    <style>
.btn:disabled, .btn[disabled] {
    background-color: #d6d6d6;   
    color: #a0a0a0;              
    cursor: not-allowed;          
}

.btn:disabled:hover {
    background-color: #d6d6d6;  /* Màu mờ khi hover */
}

        body {
            background-image: url('/path/to/your/background-image.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .bg-blue-600 {
            background-color: #1E40AF;
            /* Màu xanh đậm */
        }

        .bg-green-600 {
            background-color: #059669;
            /* Màu xanh lá cây */
        }

        .bg-blue-100 {
            background-color: #DBEAFE;
            /* Màu xanh nhạt cho bảng */
        }

        .shadow-md {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background-color: #28a745;
            color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-warning {
            background-color: #ffc107;
            color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .form-control {
            padding: 10px;
            font-size: 1rem;
            width: 100%;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
    </style>

</x-guest-layout>
