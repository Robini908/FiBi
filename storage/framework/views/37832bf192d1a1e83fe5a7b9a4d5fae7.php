<div class="min-w-full">
    <!--[if BLOCK]><![endif]--><?php if(empty($consolidatedData['consolidatedMatrix'])): ?>
        <div class="p-6 text-center text-gray-500">
            No subject data available for the selected timetables.
        </div>
    <?php else: ?>
        <div class="bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Period
                            </th>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $consolidatedData['visibleDays']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <?php echo e(ucfirst($day)); ?>

                                </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $consolidatedData['consolidatedMatrix']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectId => $subjectData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $consolidatedData['periods']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <!--[if BLOCK]><![endif]--><?php if($loop->first): ?>
                                        <td rowspan="<?php echo e(count($consolidatedData['periods'])); ?>" class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 align-top border-r">
                                            <div class="font-bold"><?php echo e($subjectData['subject']->subject_name); ?></div>
                                            <div class="text-xs text-gray-500">
                                                Code: <?php echo e($subjectData['subject']->subject_code); ?><br>
                                                ID: <?php echo e($subjectId); ?>

                                            </div>
                                        </td>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 border-r">
                                        <div><?php echo e($period->name); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e($period->start_time); ?> - <?php echo e($period->end_time); ?></div>
                                    </td>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $consolidatedData['visibleDays']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $cellData = $subjectData['days'][$day][$period->id] ?? null;
                                            $hasEntries = $cellData && isset($cellData['entries']) && count($cellData['entries']) > 0;
                                            $hasConflict = $cellData && isset($cellData['has_conflict']) && $cellData['has_conflict'] && $consolidationOptions['highlight_conflicts'];
                                        ?>
                                        <td class="px-2 py-2 whitespace-nowrap text-sm border text-gray-500 <?php echo e($hasConflict ? 'bg-red-50' : ''); ?>">
                                            <!--[if BLOCK]><![endif]--><?php if($hasEntries): ?>
                                                <div class="space-y-1">
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cellData['entries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="p-1 rounded border <?php echo e($hasConflict ? 'border-red-300 bg-red-100' : 'border-gray-200 bg-white'); ?>">
                                                            <div class="text-xs">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                                    <?php echo e(optional(optional($entry->timetable)->myClass)->name ?? 'Unknown Class'); ?>

                                                                </span>
                                                                
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                                                    <?php echo e(optional($entry->teacher)->name ?? 'No Teacher'); ?>

                                                                </span>
                                                                
                                                                <!--[if BLOCK]><![endif]--><?php if($entry->timetable): ?>
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                                                        <?php echo e($entry->timetable->academic_session); ?>/<?php echo e($entry->timetable->academic_term); ?>

                                                                    </span>
                                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            <?php elseif($consolidationOptions['include_empty_slots']): ?>
                                                <div class="text-xs text-gray-400 text-center">
                                                    Empty
                                                </div>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <tr class="h-4 bg-gray-100">
                                <td colspan="<?php echo e(count($consolidatedData['visibleDays']) + 2); ?>"></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/consolidated-subject-view.blade.php ENDPATH**/ ?>