@extends('layouts.app')

@section('title', 'Notification Schedule')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Set Your Notification Schedule</h4>
        </div>
        <div class="card-body">
            

            <form action="{{ route('notification-schedule.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="check_in_time">Check-in Notification Time</label>
                            <input 
                                type="time" 
                                id="check_in_time" 
                                name="check_in_time" 
                                class="form-control" 
                                value="{{ $schedule->check_in_time }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="check_out_time">Check-out Notification Time</label>
                            <input 
                                type="time" 
                                id="check_out_time" 
                                name="check_out_time" 
                                class="form-control" 
                                value="{{ $schedule->check_out_time }}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save Schedule</button>
            </form>
        </div>
    </div>
</div>
@endsection
