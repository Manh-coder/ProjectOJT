@extends('layouts.app')

@section('title', 'Email Schedule')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Email Schedule Settings</h4>
        </div>
        <div class="card-body">            

            {{-- Hiển thị thông báo lỗi --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.email-schedule.update') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Check-in Time -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="check_in_time" class="form-label">Check-in Time</label>
                            <input 
                                type="time" 
                                id="check_in_time" 
                                name="check_in_time" 
                                class="form-control" 
                                value="{{ $schedule->check_in_time ?? '' }}"
                            >
                            <small class="text-muted">Leave blank if no changes needed</small>
                        </div>
                    </div>

                    <!-- Check-out Time -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="check_out_time" class="form-label">Check-out Time</label>
                            <input 
                                type="time" 
                                id="check_out_time" 
                                name="check_out_time" 
                                class="form-control" 
                                value="{{ $schedule->check_out_time ?? '' }}"
                            >
                            <small class="text-muted">Leave blank if no changes needed</small>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
