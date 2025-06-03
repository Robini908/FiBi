<div class="bg-white rounded-lg shadow-md border border-secondary-100 overflow-hidden">
    <!-- Header section with title and controls (Material Design app bar) -->
    <div class="px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
        <div class="flex items-center">
            <div class="mr-4">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-medium text-white">Teacher-Subject Assignments</h2>
                <div class="mt-1 inline-flex items-center text-xs font-medium">
                    <span class="bg-primary-200 text-primary-900 px-2 py-0.5 rounded-full">
                        <?php echo e($academicYear); ?> - <?php echo e($academicTerm); ?>

                    </span>
                </div>
            </div>
        </div>

        <div class="flex space-x-2">
            <!-- Add Assignment Button (Material Design FAB style) -->
            <button wire:click="openModal" class="inline-flex items-center px-4 py-2 rounded-full shadow text-sm font-medium text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Assignment
            </button>

            <!-- Bulk Assign Button -->
            <button
                onclick="Livewire.dispatch('openModal', { component: 'subject-teacher-bulk-assignment' })"
                id="bulkAssignButton"
                class="inline-flex items-center px-4 py-2 rounded-full shadow text-sm font-medium text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200"
            >
                <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                Bulk Assign
            </button>

            <!-- Actions dropdown -->
            <div x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false" class="relative inline-block text-left">
                <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 rounded-full shadow text-sm font-medium text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                    More
                </button>

                <div x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white focus:outline-none z-10"
                    style="display: none;"
                >
                    <div class="py-1">
                        <button
                            wire:click="exportToExcel"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export to Excel
                            </div>
                        </button>

                        <button
                            wire:click="exportToPdf"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Export to PDF
                            </div>
                        </button>

                        <button
                            wire:click="openBulkDeleteModal"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo e(count($selectedAssignments) === 0 ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                            <?php echo e(count($selectedAssignments) === 0 ? 'disabled' : ''); ?>

                        >
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Selected (<?php echo e(count($selectedAssignments)); ?>)
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls Section -->
    <?php echo $__env->make('livewire.partials.subject-teacher-assignment.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Statistics Cards - Material Design Dashboard Style -->
    <div class="px-6 py-4 bg-white border-b border-secondary-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Assignments -->
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-primary-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Assignments</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900"><?php echo e($assignments->total()); ?></p>
                    </div>
                </div>
            </div>

            <!-- Active Assignments -->
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">Active</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900"><?php echo e($activeCount ?? '—'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Primary Assignments -->
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">Primary</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900"><?php echo e($primaryCount ?? '—'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Teachers with Assignments -->
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">Active Teachers</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900"><?php echo e($teacherCount ?? '—'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Selected Items Bar - Displays when items are selected, Material Design selection feedback style -->
    <!--[if BLOCK]><![endif]--><?php if(count($selectedAssignments) > 0): ?>
    <div class="bg-primary-50 px-6 py-3 flex items-center justify-between border-b border-primary-100">
        <div class="text-sm text-primary-700 flex items-center">
            <svg class="h-5 w-5 mr-2 text-primary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium"><?php echo e(count($selectedAssignments)); ?></span> items selected
        </div>
        <div>
            <button
                wire:click="$set('selectedAssignments', [])"
                class="text-primary-600 hover:text-primary-800 text-sm font-medium focus:outline-none transition-colors duration-150"
            >
                Clear selection
            </button>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Data Table Section - Material Design Table -->
    <div class="px-6 py-4">
        <!--[if BLOCK]><![endif]--><?php if($assignments->count() > 0): ?>
            <?php echo $__env->make('livewire.partials.subject-teacher-assignment.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('livewire.partials.subject-teacher-assignment.empty-state', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Pagination - Google-style pagination -->
        <!--[if BLOCK]><![endif]--><?php if($assignments->hasPages()): ?>
            <div class="mt-4 flex justify-center">
                <div class="inline-flex rounded-md shadow-sm">
                    <?php echo e($assignments->links()); ?>

                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Form Modal -->
    <!--[if BLOCK]><![endif]--><?php if($isModalOpen): ?>
        <?php echo $__env->make('livewire.partials.subject-teacher-assignment.form-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Bulk Delete Modal -->
    <!--[if BLOCK]><![endif]--><?php if($isBulkDeleteModalOpen): ?>
        <?php echo $__env->make('livewire.partials.subject-teacher-assignment.bulk-delete-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Alpine JS Handlers -->
    <script>
        document.addEventListener('livewire:initialized', function () {
            // Handle toast notifications
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').on('toast', event => {
                console.log('Toast notification:', event);
                Toast[event.type](event.message);
            });

            // Handle component refresh events
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').on('refreshAssignments', () => {
                console.log('Refreshing assignments');
                // Reset form state
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').dispatch('reset-form-state');

                // Additional cleanup if needed
                if (document.getElementById('assignment-form')) {
                    document.getElementById('assignment-form').reset();
                }
            });

            // Debug modal state changes
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').on('isBulkModalOpenChanged', state => {
                console.log('Bulk modal state changed:', state);
            });

            // Add click listener to bulk modal button
            document.querySelector('button[wire\\:click="openBulkModal"]')?.addEventListener('click', () => {
                console.log('Bulk modal button clicked');
            });
        });

        document.addEventListener('livewire:initialized', () => {
            // Listen for confirm-bulk-assignment event
            Livewire.on('confirm-bulk-assignment', (data) => {
                const confirmed = confirm(data[0].message + " Do you want to continue anyway?");
                if (confirmed) {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('confirmBulkAssignment');
                }
            });
        });
    </script>
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/subject-teacher-assignment.blade.php ENDPATH**/ ?>