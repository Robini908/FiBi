<div>
    <!-- Student Rankings -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-medium text-gray-700">Student Attendance Rankings</h3>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission #</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $studentAttendanceData ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="<?php echo e($student['photo'] ?? asset('global_assets/images/user.png')); ?>" alt="<?php echo e($student['name']); ?>">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($student['name']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($student['adm_no']); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($student['stats']['present_days'] ?? 0); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($student['stats']['absent_days'] ?? 0); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($student['stats']['late_days'] ?? 0); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!--[if BLOCK]><![endif]--><?php if(isset($student['stats']['attendance_rate'])): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo e($student['stats']['attendance_rate'] >= 90 ? 'bg-green-100 text-green-800' : 
                                            ($student['stats']['attendance_rate'] >= 75 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                                            <?php echo e($student['stats']['attendance_rate']); ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-500">N/A</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <div class="flex flex-col items-center justify-center py-5">
                                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No student attendance data available</p>
                                        <p class="text-xs text-gray-400 mt-1">Select a class and section to view student attendance rankings</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Top Students Chart -->
    <!--[if BLOCK]><![endif]--><?php if(isset($topStudents) && count($topStudents) > 0): ?>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-medium text-gray-700">Top Attending Students</h3>
            </div>
            <div class="p-4">
                <div class="space-y-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $topStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover" src="<?php echo e($student['photo'] ?? asset('global_assets/images/user.png')); ?>" alt="<?php echo e($student['name']); ?>">
                            </div>
                            <div class="ml-4 flex-grow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($student['name']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e($student['adm_no']); ?></div>
                                    </div>
                                    <div class="text-sm font-semibold 
                                        <?php echo e($student['attendance_rate'] >= 90 ? 'text-green-600' : 
                                        ($student['attendance_rate'] >= 75 ? 'text-yellow-600' : 'text-red-600')); ?>">
                                        <?php echo e($student['attendance_rate']); ?>%
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                    <div class="h-2.5 rounded-full <?php echo e($student['attendance_rate'] >= 90 ? 'bg-green-600' : 
                                        ($student['attendance_rate'] >= 75 ? 'bg-yellow-500' : 'bg-red-600')); ?>" 
                                        style="width: <?php echo e($student['attendance_rate']); ?>%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-xs text-gray-500">Present: <?php echo e($student['present_days']); ?>/<?php echo e($student['total_days']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Student Ranking Chart -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Students by Attendance Rate</h3>
        <div class="h-80">
            <!--[if BLOCK]><![endif]--><?php if(!empty($studentAttendanceData)): ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('livewire-column-chart', ['columnChartModel' => $this->getStudentRankingChartModel()]);

$__html = app('livewire')->mount($__name, $__params, ''.e($classId.$sectionId.now()).'-student-ranking', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php else: ?>
                <div class="flex items-center justify-center h-full">
                    <p class="text-gray-500">No student data available</p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/partials/analytics-students.blade.php ENDPATH**/ ?>