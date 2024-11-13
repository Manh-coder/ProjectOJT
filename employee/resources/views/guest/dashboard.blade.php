<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- {{ __('Dashboard') }} --}}
        </h2>

        <!-- Thêm vào tệp CSS hoặc trong <style> -->
        <style>
            body {
                background-image: url('/path/to/your/background-image.jpg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                background-repeat: no-repeat;
            }

            /* Nền cho các phần tử */
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


        </style>



    </x-slot>

    <div>
        {{-- <h1>Hello {{ auth()->user()->name }}</h1> --}}
        {{-- <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn"> {{ __('Log Out') }}</button>
        </form> --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($entry && !empty($entry->datetime_ci) && empty($entry->datetime_co))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>{{ auth()->user()->name }}</strong> Please checkout.
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2  gap-6 mt-4">
            
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

            
            <div class="bg-green-600 text-white shadow-md rounded-lg p-6 transform transition-transform hover:scale-105">
                <div>
                    <div class="row">
                        <div class="col-md-2">
                            Action:
                        </div>
                        <div class="col-md-2">

                            <form action="{{ route('employees.action') }}" method="post" class="form-inline float-right">
                                @csrf
                                <input type="text" value="ci" name="action" style="display: none">
                                <button type="{{ !$entry?->datetime_ci ? 'submit' : 'button' }}" class="btn btn-primary btn-sm p-1 {{ $entry?->datetime_ci ? 'opacity-75' : '' }}">Checkin</button>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form action="{{ route('employees.action') }}" method="post" class="form-inline float-right">
                                @csrf
                                <input type="text" value="co" name="action" style="display: none">
                                <button type="{{ !$entry?->datetime_co && !empty($entry->datetime_ci) ? 'submit' : 'button' }}" class="btn btn-primary btn-sm p-1 {{ $entry?->datetime_co ? 'opacity-75' : '' }}">Checkout</button>
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
        </div>
        <!-- Danh sách nhân viên mới -->
        <div class="mt-12">
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">Access control</h3>
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
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-guest-layout>
