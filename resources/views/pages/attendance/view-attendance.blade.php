@extends('layouts.master')
@section('page_title', 'View Attendance Records')

@section('breadcrumb')
<x-breadcrumb :breadcrumbs="[
    ['name' => 'Dashboard', 'url' => route('dashboard')],
    ['name' => 'Attendance', 'url' => '#'],
    ['name' => 'View Records', 'url' => '#', 'active' => true],
]"></x-breadcrumb>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            @if(isset($class_id) && isset($section_id))
                <livewire:attendance.view-attendance :classId="$class_id" :sectionId="$section_id" />
            @else
                <livewire:attendance.view-attendance />
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Additional scripts for the view attendance page
        });
    </script>
@endsection 