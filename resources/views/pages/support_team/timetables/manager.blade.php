@extends('layouts.master')
@section('page_title', 'Timetable Management System')
@section('content')

<div class="card">
    <div class="card-body">
        @livewire('timetable.timetable-manager')
    </div>
</div>

@endsection 