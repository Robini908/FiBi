<div class="p-3 border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <div class="card-body">
        <div class="form-row mb-3">
            <!-- Class Selection -->
            <div class="col-md-4">
                <label for="class" class="form-label">Select Class</label>
                <div class="input-group">
                    <select wire:model.live="classId" id="class" class="form-control">
                        <option value="">Select Class</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <div wire:loading wire:target="classId" class="input-group-append">
                        <span class="input-group-text">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Section Selection -->
           
                
           
            <!--[if BLOCK]><![endif]--><?php if($classId): ?>
            <div class="col-md-4">
                <label for="section" class="form-label">Select Stream (Optional, for stream analysis)</label>
                <div class="input-group">
                    <select wire:model.live="sectionId" id="section" class="form-control">
                        <option value="">All Sections</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>"><?php echo e($section->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <div wire:loading wire:target="sectionId" class="input-group-append">
                        <span class="input-group-text">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </span>
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Exam Selection -->
            <!--[if BLOCK]><![endif]--><?php if($classId): ?>
            <div class="col-md-4">
                <label for="exam" class="form-label">Select Exam</label>
                <div class="input-group">
                    <select wire:model.live="examId" id="exam" class="form-control">
                        <option value="">Select Exam</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($exam->id); ?>"><?php echo e($exam->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <div wire:loading wire:target="examId" class="input-group-append">
                        <span class="input-group-text">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </span>
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Get Subject Analysis Button -->
        <!--[if BLOCK]><![endif]--><?php if($examId): ?>
        <div class="mb-3">
            <button wire:click="getGradesCount" class="btn btn-primary d-flex align-items-center">
                Get Subject Analysis
                <div wire:loading wire:target="getGradesCount" class="spinner-border spinner-border-sm text-light ms-2"
                    role="status"></div>
            </button>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if($errorMessage): ?>
            <div class="alert alert-danger mt-3"><?php echo e($errorMessage); ?></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if(!empty($gradesCount) && is_array($gradesCount) && count($gradesCount) > 0): ?>
            <div class="table-responsive mt-4">
                <table class="table table-striped table-bordered table-hover">
                    <!-- Header row displaying the selected information -->
                    <thead>
                        <tr>
                            <th colspan="13" class="text-center">
                                Subject Analysis for
                                <!--[if BLOCK]><![endif]--><?php if($classId): ?>
                                    <strong><?php echo e($classes->where('id', $classId)->first()->name); ?></strong>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]--><?php if($sectionId): ?>
                                    <!--[if BLOCK]><![endif]--><?php if($classId): ?>
                                        <strong> - <?php echo e($sections->where('id', $sectionId)->first()->name); ?></strong>
                                    <?php else: ?>
                                        <strong><?php echo e($sections->where('id', $sectionId)->first()->name); ?></strong>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <br>
                                <strong>Name of Exam:</strong> <?php echo e($exams->where('id', $examId)->first()->name); ?>

                            </th>
                        </tr>
                        <!-- Fixed header for grades -->
                        <tr>
                            <th>Subject</th>
                            <th>A</th>
                            <th>A-</th>
                            <th>B+</th>
                            <th>B</th>
                            <th>B-</th>
                            <th>C+</th>
                            <th>C</th>
                            <th>C-</th>
                            <th>D+</th>
                            <th>D</th>
                            <th>D-</th>
                            <th>E</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop through each subject's grades -->
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $gradesCount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-start"><?php echo e($grade['subject_name']); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['A'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['A-'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['B+'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['B'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['B-'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['C+'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['C'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['C-'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['D+'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['D'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['D-'] ?? 0); ?></td>
                                <td class="text-center"><?php echo e($grade['grades']['E'] ?? 0); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Display message when no data is available -->
            <div class="mt-4 text-center">
                <p>No data available for analysis.</p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/subject-analysis.blade.php ENDPATH**/ ?>