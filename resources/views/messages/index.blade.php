@extends('layouts.master')

@section('page_title', 'Messages')

@section('content')
    <!-- Include the Livewire MessageManager Component -->
    @livewire('message-manager', [
        'userId' => request()->query('userId'),
        'filter' => request()->query('filter'),
        'view' => request()->query('view')
    ])
@endsection