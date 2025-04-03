@extends('layouts.master')
@section('page_title', 'Attendance Analytics')

@section('breadcrumb')
<x-breadcrumb :breadcrumbs="[
    ['name' => 'Dashboard', 'url' => route('dashboard')],
    ['name' => 'Attendance', 'url' => route('attendance.view')],
    ['name' => 'Analytics', 'url' => '#', 'active' => true],
]"></x-breadcrumb>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <livewire:attendance.attendance-analytics />
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Additional styles for loading states */
    .placeholder-loading {
        position: relative;
        overflow: hidden;
    }
    .placeholder-loading::after {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: loading 1.5s infinite;
    }
    @keyframes loading {
        100% {
            transform: translateX(100%);
        }
    }
    
    /* Transitions for smoother UI */
    .tab-transition {
        transition: all 0.3s ease-in-out;
    }
</style>
@endpush

@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set up event listeners using Livewire 3 syntax
            document.addEventListener('livewire:init', () => {
                // Listen for real-time updates
                Livewire.hook('message.processed', (message, component) => {
                    // Resize charts if they exist
                    if (window.echarts) {
                        window.echarts.instances.forEach(function(chart) {
                            chart.resize();
                        });
                    }
                });
            });
            
            // Handle chart animations on tab change
            document.querySelectorAll('[wire\\:click^="setTab"]').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    setTimeout(function() {
                        if (window.echarts) {
                            window.echarts.instances.forEach(function(chart) {
                                chart.resize();
                            });
                        }
                    }, 300);
                });
            });
        });
    </script>
@endsection 