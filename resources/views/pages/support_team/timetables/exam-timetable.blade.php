@extends('layouts.master')
@section('page_title', 'Exam Timetable Manager')
@section('content')

<div class="card">
    <div class="card-header bg-white">
        <div class="row align-items-center">
            <div class="col-8">
                <h4 class="card-title mb-0">Exam Timetable Manager</h4>
                <div class="small text-muted">Create and manage exam schedules for different classes</div>
            </div>
            <div class="col-4 text-right">
                <a href="{{ route('tt.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Timetables
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @livewire('timetable.exam-timetable')
    </div>
</div>

@endsection 