<div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-gray-50 px-4 py-4 sm:px-6">
    <div class="flex flex-wrap items-center justify-between">
        <h3 class="text-lg font-medium text-gray-900">
            @if($classId && $sectionId && isset($class) && isset($section))
                Attendance Records - {{ $class->name }} {{ $section->name }}
            @else
                Attendance Records
            @endif
        </h3>
        @if($classId && $sectionId)
            <div class="flex space-x-2">
                <a href="{{ route('attendance.take', ['class_id' => $classId, 'section_id' => $sectionId]) }}" class="inline-flex items-center px-3 py-1.5 border border-green-700 text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Take Attendance
                </a>
                <button wire:click="downloadAttendanceReport" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </div>
        @endif
    </div>
</div> 