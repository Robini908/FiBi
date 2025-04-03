@extends('layouts.master')
@section('page_title', 'Timetable Consolidator')
@section('content')

<div class="card">
    <div class="card-header bg-white">
        <div class="row">
            <div class="col-md-6">
                <h4 class="card-title"><i class="icon-calendar3"></i> Timetable Consolidator</h4>
            </div>
            <div class="col-md-6">
                <div class="float-md-right">
                    <a href="{{ route('tt.index') }}" class="btn btn-danger">
                        <i class="icon-arrow-left12"></i> Back to Timetables
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <p>This tool allows you to consolidate multiple timetables into a single view. You can select timetables from the same class across different academic sessions or from all classes to generate a consolidated timetable.</p>
            </div>
        </div>

        <livewire:timetable.timetable-consolidator />
    </div>
</div>

@endsection 