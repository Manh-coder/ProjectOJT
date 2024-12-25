<!-- resources/views/admin/salary/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 style="font-weight: bold; font-size: 1.3rem;">List Leave </h2><br>

        <button type="button" class="btn btn-primary btn-xs" style="font-size: 12px; padding: 5px 9px;" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fas fa-plus"></i> Create
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('config-leave.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Create</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="daily" class="form-label">Employee</label>
                                <select class="form-select" aria-label="Default select example" name="user_id" required>
                                    <option value="">Open this select month</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="daily" class="form-label">Total leave day</label>
                                <input type="text" class="form-control" name="total_leave_days">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <table class="table">
            <thead>
                <tr>
                    <th>Employees</th>
                    <th>Total leave day</th>
                    <th>Used leave day</th>
                    <th>Remain leave day</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entries as $salary)
                    <tr>
                        <td>{{ $salary->user->name }}</td>
                        <td>{{ $salary->total_leave_days }}</td>
                        <td>{{ $salary->used_leave_days }}</td>
                        <td>{{ $salary->unpaid_leave_days }}</td>
                        <td>

                            <button type="button" class="btn btn-primary btn-xs" style="font-size: 12px; padding: 5px 9px;"
                                data-bs-toggle="modal" data-bs-target="#exampleModal{{ $salary->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{ $salary->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('config-leave.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" class="form-control" name="id"
                                                value="{{ $salary->id }}">
                                            <input type="hidden" class="form-control" name="user_id"
                                                value="{{ $salary->user_id }}">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="daily" class="form-label">Employee</label>
                                                    <select class="form-select" aria-label="Default select example"
                                                        disabled>
                                                        <option value="">Open this select month</option>
                                                        @foreach ($userEdits as $user)
                                                            <option value="{{ $user->id }}"
                                                                {{ $user->id == $salary->user_id ? 'selected' : '' }}>
                                                                {{ $user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="daily" class="form-label">Total leave day</label>
                                                    <input type="text" class="form-control" name="total_leave_days"
                                                        value="{{ $salary->total_leave_days }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
