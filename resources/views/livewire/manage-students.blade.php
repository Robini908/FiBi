<div x-data="{ activeTab: 'students', showFilters: false }" class="bg-gray-50 min-h-screen p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Back button - shown when in any action view -->
        @if ($isEditingStudent || $isViewingDetails || $isDeleting || $isApproving || $isSuspendingStudent || $isExpellingStudent || $isAddingStudent)
            @include('livewire.partials.students.back-button')
        @endif

        <!-- Main content area -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if (!($isEditingStudent || $isViewingDetails || $isDeleting || $isApproving || $isSuspendingStudent || $isExpellingStudent || $isAddingStudent))
            <!-- Header -->
                @include('livewire.partials.students.header')

                <!-- Advanced Filters -->
                @include('livewire.partials.students.advanced-filters')

                <!-- Quick Search -->
                @include('livewire.partials.students.quick-search')

                <!-- Student List -->
                @include('livewire.partials.students.student-list')

                <!-- Pagination -->
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
            @elseif ($isAddingStudent)
                <!-- Admit Student Form -->
                @include('livewire.admit-student')
            @elseif ($isEditingStudent)
                <!-- Edit Student View -->
                @include('livewire.partials.students.edit-student')
            @elseif ($isViewingDetails && $selectedStudent)
                <!-- Send Email View -->
                @include('livewire.partials.students.send-email')
            @elseif ($isDeleting)
                <!-- Delete Confirmation View -->
                @include('livewire.partials.students.delete-confirmation')
            @elseif ($isExpellingStudent)
                <!-- Student Expulsion View -->
                @include('livewire.partials.students.student-expulsion')
            @elseif ($isSuspendingStudent)
                <!-- Student Suspension View -->
                @include('livewire.partials.students.student-suspension')
            @elseif ($isApproving)
                <!-- Student Approval View -->
                @include('livewire.partials.students.student-approval')
            @endif
        </div>
    </div>
</div>
