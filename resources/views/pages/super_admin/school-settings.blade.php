@extends('layouts.master')

@section('title', $title ?? 'School Settings')

@section('page_title', 'School Settings')

@section('breadcrumbs')
    @if(isset($breadcrumbs))
        <x-ui.breadcrumbs :items="$breadcrumbs" />
    @endif
@endsection

@section('content')
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:super-admin.school-settings />
        </div>
    </div>
@endsection 