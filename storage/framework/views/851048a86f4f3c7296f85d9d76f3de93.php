<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Timetables</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($consolidationStats['timetables']); ?></dd>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Classes</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($consolidationStats['classes']); ?></dd>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Time Periods</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($consolidationStats['periods']); ?></dd>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Entries</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($consolidationStats['entries']); ?></dd>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg <?php echo e($consolidationStats['conflicts'] > 0 ? 'border-red-500 border-2' : ''); ?>">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium <?php echo e($consolidationStats['conflicts'] > 0 ? 'text-red-500' : 'text-gray-500'); ?> truncate">Conflicts</dt>
                <dd class="mt-1 text-3xl font-semibold <?php echo e($consolidationStats['conflicts'] > 0 ? 'text-red-600' : 'text-gray-900'); ?>"><?php echo e($consolidationStats['conflicts']); ?></dd>
            </div>
        </div>
    </div>
    
    <!-- Export Buttons -->
    <div class="mb-6 flex flex-wrap gap-2 justify-center">
        <button type="button" 
            wire:click="printTimetable"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print View
        </button>
        
        <button type="button" 
            wire:click="exportPDF"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <svg class="mr-2 -ml-1 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Export PDF
        </button>
        
        <button type="button" 
            wire:click="exportExcel"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <svg class="mr-2 -ml-1 h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export Excel
        </button>
        
        <button type="button" 
            wire:click="$set('selectedTab', 'selection')" 
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Back to Selection
        </button>
    </div>
    
    <!-- Consolidated Timetable Preview -->
    <div class="bg-white shadow overflow-x-auto rounded-lg border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Consolidated Timetable Preview</h3>
            <p class="mt-1 text-sm text-gray-500">
                Showing consolidated view grouped by <?php echo e(ucfirst($consolidationOptions['group_by'])); ?>.
                <span class="font-medium">Sessions:</span> <?php echo e($consolidationStats['academic_sessions']); ?> |
                <span class="font-medium">Terms:</span> <?php echo e($consolidationStats['academic_terms']); ?>

            </p>
        </div>
        
        <div class="overflow-x-auto">
            <!--[if BLOCK]><![endif]--><?php if($consolidationOptions['group_by'] === 'teacher'): ?>
                <?php echo $__env->make('livewire.timetable.partials.consolidated-teacher-view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($consolidationOptions['group_by'] === 'class'): ?>
                <?php echo $__env->make('livewire.timetable.partials.consolidated-class-view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($consolidationOptions['group_by'] === 'subject'): ?>
                <?php echo $__env->make('livewire.timetable.partials.consolidated-subject-view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <div class="p-6 text-center">
                    <div class="text-red-500 mb-2">Invalid grouping option selected.</div>
                    <button type="button" 
                        wire:click="$set('selectedTab', 'selection')" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Go Back and Choose a Valid Option
                    </button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    
    <!-- Legend Section -->
    <div class="mt-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Legend</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-white border border-gray-300 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Normal Entry</span>
                    </div>
                    
                    <!--[if BLOCK]><![endif]--><?php if($consolidationOptions['highlight_conflicts']): ?>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-red-100 border border-red-300 rounded mr-2"></div>
                            <span class="text-sm text-gray-700">Conflict Detected</span>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-gray-100 border border-gray-300 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Empty Time Slot</span>
                    </div>
                    
                    <!-- Additional legend items based on grouping -->
                    <!--[if BLOCK]><![endif]--><?php if($consolidationOptions['group_by'] === 'teacher'): ?>
                        <div class="flex items-center">
                            <div class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded mr-2">Class 9A</div>
                            <span class="text-sm text-gray-700">Class Label</span>
                        </div>
                    <?php elseif($consolidationOptions['group_by'] === 'class'): ?>
                        <div class="flex items-center">
                            <div class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded mr-2">Mr. Smith</div>
                            <span class="text-sm text-gray-700">Teacher Name</span>
                        </div>
                    <?php elseif($consolidationOptions['group_by'] === 'subject'): ?>
                        <div class="flex items-center">
                            <div class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded mr-2">Class 9A</div>
                            <span class="text-sm text-gray-700">Class Assignment</span>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/consolidator-preview.blade.php ENDPATH**/ ?>