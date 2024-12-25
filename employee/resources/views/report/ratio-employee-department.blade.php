@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-700">Report</h2>
        </div>
        <br>
        <form action="" method="GET">

            <div class="row">
                {{-- Biểu đồ thống kê tỉ lệ nhân sự giữa các phòng ban --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="date" class="form-control" name="ratio" placeholder="name@example.com"
                                    value="{{ request()->query('ratio') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                            {{-- <h5 class="card-title">Card title</h5> --}}
                            <x-chartjs-component :chart="$ratio" />
                        </div>
                    </div>
                </div>
                {{-- Biểu đồ thống kê độ tuổi, giới tính theo từng phòng ban --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="date" class="form-control" id="exampleFormControlInput1"
                                    placeholder="name@example.com" name="gender_and_age"
                                    value="{{ request()->query('gender_and_age') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                            {{-- <h5 class="card-title">Card title</h5> --}}

                            <x-chartjs-component :chart="$ageAndGender" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="date" class="form-control" id="exampleFormControlInput1"
                                    placeholder="name@example.com" name="gender" value="{{ request()->query('gender') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                            {{-- <h5 class="card-title">Card title</h5> --}}

                            <x-chartjs-component :chart="$gender" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="date" class="form-control" id="exampleFormControlInput1"
                                    placeholder="name@example.com" name="workday"
                                    value="{{ request()->query('workday"') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                            {{-- <h5 class="card-title">Card title</h5> --}}

                            <x-chartjs-component :chart="$workdayReport" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="date" class="form-control" id="exampleFormControlInput1"
                                    placeholder="name@example.com" name="salary" value="{{ request()->query('salary"') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                            {{-- <h5 class="card-title">Card title</h5> --}}

                            <div>
                                <x-chartjs-component :chart="$salaryReport" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>


    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
