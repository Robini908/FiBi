<div class="min-h-full bg-gray-100">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with title and action button -->
            <?php echo $__env->make('livewire.partials.class-management.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <!-- Filters for classes -->
            <?php echo $__env->make('livewire.partials.class-management.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <!-- Main Content Area -->
            <div class="space-y-6">
    <!--[if BLOCK]><![endif]--><?php if($showForm): ?>
                    <!-- Class Form -->
                    <?php echo $__env->make('livewire.partials.class-management.class-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($isViewingClassTeacher): ?>
                    <!-- Class Master View -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Class Teachers for <?php echo e($class->name); ?>

                                </h3>
                                <button 
                                    wire:click="$set('isViewingClassTeacher', false)" 
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Close
                                </button>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4">
                            <!--[if BLOCK]><![endif]--><?php if($hasTeachers): ?>
                                <div class="overflow-x-auto rounded-md border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classTeachers->groupBy('pivot.session'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session => $teachers): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr class="hover:bg-gray-50">
                                                        <!--[if BLOCK]><![endif]--><?php if($editingTeacherId === $teacher->id && $editingSession === $session): ?>
                                                            <td colspan="7" class="px-6 py-4">
                                                                <form wire:submit.prevent="saveStreamTeacher" class="space-y-4">
                                                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                                                        <div>
                                                                            <label for="streamTeacher" class="block text-sm font-medium text-gray-700">Select Teacher</label>
                                                                            <select 
                                                                                wire:model="streamTeacher" 
                                                                                id="streamTeacher" 
                                                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                                                            >
                                                                <option value="">-- Select Teacher --</option>
                                                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacherOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <option value="<?php echo e($teacherOption->id); ?>"><?php echo e($teacherOption->name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            </select>
                                                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['streamTeacher'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                                        </div>

                                                                        <div>
                                                                            <label for="session" class="block text-sm font-medium text-gray-700">Session</label>
                                                                            <select 
                                                                                wire:model="session" 
                                                                                id="session" 
                                                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                                                            >
                                                                <option value="">-- Select Session --</option>
                                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = range(date('Y'), 1900); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <option value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            </select>
                                                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['session'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                                        </div>
                                                    </div>
                                                                    
                                                                    <div class="flex justify-end space-x-2">
                                                                                <button
                                                                            type="submit" 
                                                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                                        >
                                                                            Save
                                                                                </button>
                                                                                <button
                                                                            type="button" 
                                                                            wire:click="cancelEdit" 
                                                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                                        >
                                                                            Cancel
                                                                                </button>
                                                                            </div>
                                                                </form>
                                                                    </td>
                                                        <?php else: ?>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($session); ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex items-center">
                                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                                        <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                        </svg>
                                                                    </div>
                                                                    <div class="ml-4">
                                                                        <div class="text-sm font-medium text-gray-900"><?php echo e($teacher->name); ?></div>
                                                                    </div>
                                                            </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($teacher->phone ?? '--'); ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($teacher->gender ?? '--'); ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($teacher->code ?? '--'); ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <!--[if BLOCK]><![endif]--><?php if($teacher->photo): ?>
                                                                    <img src="<?php echo e(asset($teacher->photo)); ?>" alt="Teacher Photo" class="h-10 w-10 rounded-full object-cover">
                                                                <?php else: ?>
                                                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100">
                                                                        <span class="text-xs font-medium text-gray-500">N/A</span>
                                                                    </span>
                                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <div class="flex justify-end space-x-2">
                                                                    <button 
                                                                        wire:click="deleteTeacher(<?php echo e($teacher->id); ?>, '<?php echo e($session); ?>')" 
                                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-red-600 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                                        title="Delete teacher assignment"
                                                                    >
                                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                            </button>
                                            </div>
                                        </td>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>

                                <div class="mt-6 flex justify-between">
                                    <button wire:click="exportPdf" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Export as PDF
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No teachers assigned</h3>
                                    <p class="mt-1 text-sm text-gray-500">There are no teachers assigned to this class yet.</p>
                                    <div class="mt-6">
                                        <button
                                            wire:click="toggleAssignTeacher(<?php echo e($selectedClassForAssignment); ?>)"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Assign Teacher
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                </div>
                <?php else: ?>
                    <!-- Classes Table -->
                    <?php echo $__env->make('livewire.partials.class-management.classes-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>

    <!-- Students Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showStudentsModal): ?>
        <div class="fixed inset-0 overflow-hidden z-50" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                    <div class="relative w-screen max-w-6xl">
                        <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                            <div class="px-4 py-6 sm:px-6 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900">
                                        Students in <?php echo e($modalStreamName); ?>

                                    </h2>
                                    <div class="ml-3 h-7 flex items-center">
                                        <button 
                                            wire:click="$set('showStudentsModal', false)" 
                                            class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                        </button>
                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Total Students: <span class="font-medium"><?php echo e($modalStudentsCount); ?></span>
                                </p>
                            </div>
                            
                            <div class="flex-1 px-4 py-6 sm:px-6 overflow-auto">
                                <div class="border border-gray-200 rounded-md overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Number</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KCPE</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                                </tr>
                            </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $modalStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($student->adm_no); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                                                                <!--[if BLOCK]><![endif]--><?php if($student->photo): ?>
                                                                    <img class="h-10 w-10 rounded-full object-cover" src="<?php echo e(asset($student->photo)); ?>" alt="">
                                                                <?php else: ?>
                                                                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900">
                                                                    <?php echo e($student->first_name); ?> <?php echo e($student->middle_name); ?> <?php echo e($student->last_name); ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($student->email); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(ucfirst($student->gender)); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($student->kcpe); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($student->phone); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(\Carbon\Carbon::parse($student->dob)->format('d M, Y')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                            </div>
                            
                            <div class="flex-shrink-0 px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200">
                                <div class="flex justify-end">
                                    <button
                                        wire:click="$set('showStudentsModal', false)"
                                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>

    <?php
        $__scriptKey = '3468741155-0';
        ob_start();
    ?>
<script>
// Only initialize Alpine data if not already done
document.addEventListener('livewire:load', function() {
    // Nothing here for now, as we're using x-data on individual components
    });
</script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/class-management.blade.php ENDPATH**/ ?>