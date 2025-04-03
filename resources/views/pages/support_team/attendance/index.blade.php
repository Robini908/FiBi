@extends('layouts.master')

@section('page_title', 'Student Attendance Management')

@section('content')

<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900">Student Attendance Dashboard</h3>
        <p class="mt-1 text-sm text-gray-600">
            Manage attendance records for all classes and students. Track, analyze, and report on student attendance patterns.
        </p>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
    <!-- Take Attendance Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-300">
        <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-gray-50 px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-medium text-gray-800">Take Attendance</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <p class="text-sm text-gray-500 mb-4">
                Record daily attendance for classes and sections. Mark students as present, absent, late, or excused.
            </p>
            
            <form action="{{ route('attendance.take') }}" method="GET" class="space-y-4">
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700">Select Class</label>
                    <select id="class_id" name="class_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="section_id" class="block text-sm font-medium text-gray-700">Select Section</label>
                    <select id="section_id" name="section_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="">Select Class First</option>
                    </select>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                        Take Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Attendance Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-300">
        <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-gray-50 px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-medium text-gray-800">View Attendance</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <p class="text-sm text-gray-500 mb-4">
                View and manage attendance records. Filter by date, class, or section to see detailed attendance information.
            </p>
            
            <form action="{{ route('attendance.view') }}" method="GET" class="space-y-4">
                <div>
                    <label for="view_class_id" class="block text-sm font-medium text-gray-700">Select Class</label>
                    <select id="view_class_id" name="class_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="view_section_id" class="block text-sm font-medium text-gray-700">Select Section</label>
                    <select id="view_section_id" name="section_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="">Select Class First</option>
                    </select>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Records
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reports Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-300">
        <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-gray-50 px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-medium text-gray-800">Attendance Reports</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <p class="text-sm text-gray-500 mb-4">
                Generate comprehensive attendance reports. Analyze attendance patterns and identify at-risk students.
            </p>
            
            <form action="{{ route('attendance.report') }}" method="GET" class="space-y-4">
                <div>
                    <label for="report_class_id" class="block text-sm font-medium text-gray-700">Select Class</label>
                    <select id="report_class_id" name="class_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="report_section_id" class="block text-sm font-medium text-gray-700">Select Section</label>
                    <select id="report_section_id" name="section_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="">Select Class First</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date" required class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="end_date" required class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3m0 0l3 3m-3-3v7m6-2v-7m0 7l-3-3m3 3l3-3" />
                        </svg>
                        Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Analytics Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-300">
        <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-gray-50 px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-medium text-gray-800">Attendance Analytics</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <p class="text-sm text-gray-500 mb-4">
                Visualize attendance data with interactive charts and graphs. Track trends and monitor attendance patterns.
            </p>
            
            <div class="mt-4 flex justify-center">
                <a href="{{ route('attendance.analytics') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3m0 0l3 3m-3-3v7m6-2v-7m0 7l-3-3m3 3l3-3" />
                    </svg>
                    View Analytics
                </a>
            </div>
        </div>
    </div>

    <!-- Student Lookup Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-300">
        <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-gray-50 px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-medium text-gray-800">Student Lookup</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <p class="text-sm text-gray-500 mb-4">
                Search for a specific student to view their attendance history, statistics, and trends.
            </p>
            
            <form action="{{ route('attendance.student.search') }}" method="GET" class="space-y-4">
                <div>
                    <label for="search_term" class="block text-sm font-medium text-gray-700">Student Name/ID</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" name="search_term" id="search_term" class="focus:ring-green-500 focus:border-green-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" placeholder="Enter name or ID">
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900">Quick Tips</h3>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="relative rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Take attendance daily</p>
                        <p class="text-sm text-gray-500">Regular attendance recording ensures accurate tracking and reporting</p>
                    </div>
                </div>
            </div>
            
            <div class="relative rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">View reports monthly</p>
                        <p class="text-sm text-gray-500">Monthly reports help identify attendance patterns and issues</p>
                    </div>
                </div>
            </div>
            
            <div class="relative rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Monitor attendance rates</p>
                        <p class="text-sm text-gray-500">Low attendance rates could indicate academic or personal issues</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to load sections based on selected class
    function loadSections(classId, targetSelectId) {
        if (!classId) {
            document.getElementById(targetSelectId).innerHTML = '<option value="">Select Class First</option>';
            return;
        }
        
        fetch(`/api/classes/${classId}/sections`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Select Section</option>';
                data.forEach(section => {
                    options += `<option value="${section.id}">${section.name}</option>`;
                });
                document.getElementById(targetSelectId).innerHTML = options;
            })
            .catch(error => console.error('Error loading sections:', error));
    }
    
    // Set up event listeners for all class select elements
    document.getElementById('class_id').addEventListener('change', function() {
        loadSections(this.value, 'section_id');
    });
    
    document.getElementById('view_class_id').addEventListener('change', function() {
        loadSections(this.value, 'view_section_id');
    });
    
    document.getElementById('report_class_id').addEventListener('change', function() {
        loadSections(this.value, 'report_section_id');
    });
    
    // Set default dates for report
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('start_date').valueAsDate = firstDayOfMonth;
    document.getElementById('end_date').valueAsDate = today;
});
</script>
@endsection 