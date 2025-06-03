<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-5">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h4 class="text-sm font-medium text-gray-700">Date & Session Information</h4>
    </div>
    
    <div class="px-6 py-4">
        <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-3 sm:gap-x-4">
            <div>
                <label for="attendanceDate" class="block text-sm font-medium text-gray-700 mb-1">Attendance Date</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="date" id="attendanceDate" wire:model.live="attendanceDate" 
                           class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-md">
                </div>
                <p class="mt-1 text-xs text-gray-500">Today: <?php echo e(now()->format('M d, Y')); ?></p>
            </div>
            
            <div>
                <label for="sessionType" class="block text-sm font-medium text-gray-700 mb-1">Session Type</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <select id="sessionType" wire:model.live="sessionType" 
                            class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 pr-10 py-2 text-sm border-gray-300 rounded-md appearance-none">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sessionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Morning, afternoon or evening session</p>
            </div>
            
            <div>
                <label for="expectedTime" class="block text-sm font-medium text-gray-700 mb-1">Expected Time</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="time" id="expectedTime" wire:model.live="expectedTime" 
                           class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-md">
                </div>
                <p class="mt-1 text-xs text-gray-500">For calculating late arrivals</p>
            </div>
        </div>
        
        <!-- Quick date selection -->
        <div class="mt-4 pt-3 border-t border-gray-100">
            <div class="flex flex-wrap gap-2">
                <p class="text-xs font-medium text-gray-500 mr-2 py-1">Quick select:</p>
                <button type="button" wire:click="$set('attendanceDate', '<?php echo e(now()->format('Y-m-d')); ?>')" 
                    class="inline-flex items-center px-2.5 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500">
                    Today
                </button>
                <button type="button" wire:click="$set('attendanceDate', '<?php echo e(now()->subDay()->format('Y-m-d')); ?>')" 
                    class="inline-flex items-center px-2.5 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500">
                    Yesterday
                </button>
                <button type="button" wire:click="$set('attendanceDate', '<?php echo e(now()->addDay()->format('Y-m-d')); ?>')" 
                    class="inline-flex items-center px-2.5 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500">
                    Tomorrow
                </button>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/partials/take-date-controls.blade.php ENDPATH**/ ?>