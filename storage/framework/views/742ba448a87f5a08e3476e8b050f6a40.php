<div>
    <!-- Class Sections Comparison -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-medium text-gray-700">Section Comparison</h3>
        </div>
        <div class="p-4">
            <!--[if BLOCK]><![endif]--><?php if(!$classId): ?>
                <div class="flex flex-col items-center justify-center py-6">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Select a class to view section comparison</p>
                </div>
            <?php elseif(count($comparisonData ?? []) === 0): ?>
                <div class="flex flex-col items-center justify-center py-6">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No sections available for comparison</p>
                    <p class="text-xs text-gray-400 mt-1">The selected class may not have multiple sections with attendance data</p>
                </div>
            <?php else: ?>
                <!-- Section Comparison Chart -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Section Comparison (by Attendance Rate)</h3>
                    <div class="h-80">
                        <!--[if BLOCK]><![endif]--><?php if(!empty($comparisonData)): ?>
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('livewire-column-chart', ['columnChartModel' => $this->getSectionComparisonChartModel()]);

$__html = app('livewire')->mount($__name, $__params, ''.e($classId).'-comparison-'.e(now()).'', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full">
                                <p class="text-gray-500">No data available for section comparison</p>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                
                <!-- Detailed Section Comparison Data -->
                <!--[if BLOCK]><![endif]--><?php if(!empty($comparisonData)): ?>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-sm font-medium text-gray-700">Detailed Section Comparison</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Rate</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $comparisonData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="<?php echo e($data['section_id'] == $sectionId ? 'bg-green-50' : ''); ?>">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo e($data['section_name']); ?>

                                                        <!--[if BLOCK]><![endif]--><?php if($data['section_id'] == $sectionId): ?>
                                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                Current
                                                            </span>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                        <div class="bg-green-600 h-2.5 rounded-full" style="width: <?php echo e($data['stats']['attendance_rate']); ?>%"></div>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-900"><?php echo e($data['stats']['attendance_rate']); ?>%</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <?php echo e($data['stats']['present_count']); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <?php echo e($data['stats']['absent_count']); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <?php echo e($data['stats']['late_count']); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">No section comparison data available for the selected class and date range.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    
    <!-- Performance Analysis -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <h3 class="text-sm font-medium text-gray-700">Attendance Performance Analysis</h3>
        </div>
        <div class="p-4">
            <!--[if BLOCK]><![endif]--><?php if(!$classId || count($comparisonData ?? []) === 0): ?>
                <div class="flex flex-col items-center justify-center py-5">
                    <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No data available for analysis</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php
                        // Get best performing section
                        $bestSection = collect($comparisonData)->sortByDesc(function ($item) {
                            return $item['stats']['attendance_rate'] ?? 0;
                        })->first();
                        
                        // Get worst performing section
                        $worstSection = collect($comparisonData)->sortBy(function ($item) {
                            return $item['stats']['attendance_rate'] ?? 0;
                        })->first();
                        
                        // Get average attendance rate
                        $avgRate = collect($comparisonData)->avg(function ($item) {
                            return $item['stats']['attendance_rate'] ?? 0;
                        });
                    ?>
                    
                    <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                        <h4 class="text-sm font-medium text-green-800 mb-1">Best Performing Section</h4>
                        <p class="text-lg font-bold text-green-700"><?php echo e($bestSection['section_name']); ?></p>
                        <p class="text-sm text-green-600 mt-1"><?php echo e($bestSection['stats']['attendance_rate'] ?? 0); ?>% Attendance Rate</p>
                        <p class="text-xs text-green-500 mt-3">Highest attendance rate among all sections</p>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <h4 class="text-sm font-medium text-blue-800 mb-1">Average Performance</h4>
                        <p class="text-lg font-bold text-blue-700"><?php echo e(number_format($avgRate, 1)); ?>%</p>
                        <p class="text-sm text-blue-600 mt-1">Overall Attendance Rate</p>
                        <p class="text-xs text-blue-500 mt-3">Average of all sections in this class</p>
                    </div>
                    
                    <div class="bg-red-50 rounded-lg p-4 border border-red-100">
                        <h4 class="text-sm font-medium text-red-800 mb-1">Needs Improvement</h4>
                        <p class="text-lg font-bold text-red-700"><?php echo e($worstSection['section_name']); ?></p>
                        <p class="text-sm text-red-600 mt-1"><?php echo e($worstSection['stats']['attendance_rate'] ?? 0); ?>% Attendance Rate</p>
                        <p class="text-xs text-red-500 mt-3">Lowest attendance rate among all sections</p>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/partials/analytics-comparison.blade.php ENDPATH**/ ?>