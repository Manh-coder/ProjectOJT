<!-- resources/views/admin/salary/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 style="font-weight: bold; font-size: 1.3rem;">List Employee Salary</h2><br>

        <!-- Nút tính lương cho tất cả nhân viên -->
        {{-- <a href="{{ route('salary.calculateAll') }}" class="btn btn-warning btn-xs" style="font-size: 12px; padding: 5px 9px;">
            <i class="fas fa-calculator"></i> Calculate All
        </a> --}}

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-xs" style="font-size: 12px; padding: 5px 9px;" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fas fa-calculator"></i> Calculate All
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('salary.calculateAll') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Calculate all employee</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="daily" class="form-label">Month</label>
                                <select class="form-select" aria-label="Default select example" name="month">
                                    <option value="">Open this select month</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <a href="{{ route('salary.create') }}" class="btn btn-info btn-xs" style="font-size: 12px; padding: 5px 9px;">
            <i class="fas fa-calculator"></i> Calculate
        </a>



        <table class="table">
            <thead>
                <tr>
                    <th>Employees</th>
                    <th>Action</th>
                    {{-- <th>Valid days</th>
                    <th>Invalid days</th>
                    <th>Total Salary</th>
                    <th>Month</th>
                    <th>Processed day</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    @php($salariesView = $salaries->where('user_id', $user->id))
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                style="font-size: 12px; padding: 5px 9px;"
                                data-target="#exampleModal111e{{ $user->id }}">
                                Chi tiết
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal111e{{ $user->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Lương của nhân viên
                                                {{ $user->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Valid days</th>
                                                            <th>Invalid days</th>
                                                            <th>Total Salary</th>
                                                            <th>Month</th>
                                                            <th>Processed day</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salariesView as $salary)
                                                            <tr>
                                                                <td>{{ $salary->valid_days }}</td>
                                                                <td>{{ $salary->invalid_days }}</td>
                                                                <td>{{ number_format($salary->salary, 0, '.', ',') }}</td>
                                                                <td>{{ $salary->month }}</td>
                                                                <td>{{ $salary->processed_at }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        {{-- <td>{{ $salary->valid_days }}</td>
                        <td>{{ $salary->invalid_days }}</td>
                        <td>{{ number_format($salary->salary, 0, '.', ',') }}</td>
                        <td>{{ $salary->month }}</td>
                        <td>{{ $salary->processed_at }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
