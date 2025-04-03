@extends('layouts.master')
@section('page_title', 'Take Attendance')

@section('breadcrumb')
<x-breadcrumb :breadcrumbs="[
    ['name' => 'Dashboard', 'url' => route('dashboard')],
    ['name' => 'Attendance', 'url' => route('attendance.view')],
    ['name' => 'Take Attendance', 'url' => '#', 'active' => true],
]"></x-breadcrumb>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            @if(isset($class_id) && isset($section_id))
                <livewire:attendance.take-attendance :classId="$class_id" :sectionId="$section_id" />
            @else
                <livewire:attendance.take-attendance />
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Additional scripts for the take attendance page
        });
    </script>
@endsection 