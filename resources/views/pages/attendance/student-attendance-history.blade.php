@extends('layouts.master')
@section('page_title', 'Student Attendance History')

@section('breadcrumb')
<x-breadcrumb :breadcrumbs="[
    ['name' => 'Dashboard', 'url' => route('dashboard')],
    ['name' => 'Attendance', 'url' => route('attendance.view')],
    ['name' => 'Student History', 'url' => '#', 'active' => true],
]"></x-breadcrumb>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            @if(isset($student_id))
                <livewire:attendance.student-attendance-history :student_id="$student_id" />
            @else
                <livewire:attendance.student-attendance-history />
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Additional scripts for the student attendance history page
        });
    </script>
@endsection 