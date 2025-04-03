@extends('layouts.master')

@section('page_title', 'Fee Payment')

@section('content')
<div class="container-fluid px-4 py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('finance.student-fee-payments') }}">Finance</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fee Payment</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    @livewire('finance.fee-payment')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
