<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>

        {{-- <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        {{ __("You're logged in!") }}
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- resources/views/dashboard.blade.php -->

        @section('content')
            <div class="container mx-auto py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Tổng số nhân viên -->
                    <div
                        class="bg-blue-600 text-white shadow-md rounded-lg p-6 transform transition-transform hover:scale-105">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold">{{ $employeeCount }}</h2>
                            <p class="text-white">Employees</p>
                        </div>
                    </div>

                    <!-- Tổng số phòng ban -->
                    <div
                        class="bg-green-600 text-white shadow-md rounded-lg p-6 transform transition-transform hover:scale-105">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold">{{ $departmentCount }}</h2>
                            <p class="text-white">Departments</p>
                        </div>
                    </div>
                </div>

                <!-- Danh sách nhân viên mới -->
                <div class="mt-12">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">New Employees</h3>
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th
                                        class="py-3 px-6 bg-blue-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th
                                        class="py-3 px-6 bg-blue-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th
                                        class="py-3 px-6 bg-blue-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Department
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($recentEmployees as $employee)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $employee->name }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-500">{{ $employee->email }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-500">{{ $employee->department?->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Danh sách phòng ban đang hoạt động -->
                <div class="mt-12">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Departments is Active</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($activeDepartments as $department)
                            <div
                                class="bg-gradient-to-br from-green-100 to-blue-100 text-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transform transition-all duration-300 hover:scale-105">
                                <div class="flex items-center">
                                    <!-- Biểu tượng -->
                                    <div class="flex-shrink-0 bg-white text-green-500 rounded-full p-3 mr-4 shadow-sm">
                                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7l6 6m0 0l6-6m-6 6V3" />
                                        </svg>
                                    </div>
                                    <!-- Thông tin phòng ban -->
                                    <div>
                                        <h4 class="text-lg font-bold">{{ $department->name }}</h4>
                                        <p class="text-sm text-gray-600">Active</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endsection


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
</x-app-layout>
