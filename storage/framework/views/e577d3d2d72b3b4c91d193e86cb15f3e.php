<div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <!-- Dropdown to select exam -->
    <div class="form-group col-6">
        <label for="exam">Select Exam:</label>
        <select wire:model.live="examId" wire:change="getGradesCount" id="exam" class="form-control">
            <option value="">-- Choose Exam --</option>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($exam->id); ?>"><?php echo e($exam->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </select>
    </div>

    <!-- Display error message if any -->
    <!--[if BLOCK]><![endif]--><?php if($errorMessage): ?>
    <div class="alert alert-danger mt-3" style="border-radius: 0.5rem; font-weight: bold;">
        <strong>Error!</strong> <?php echo e($errorMessage); ?>

    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Show spinner while loading grades data -->
    
            
        
    

    <div wire:click="examId"  wire:loading.attr="disabled">
        
        <span wire:loading wire:target="examId"><i class="fas fa-spinner fa-spin text-primary"></i>Loading grades...</span>
    </div>



    <!-- Display the exam analysis header -->
    <!--[if BLOCK]><![endif]--><?php if($examId && !empty($gradesCount) && !$errorMessage): ?>
    <h3 class="mt-4 text-primary font-weight-bold">Analysis for Exam: <span class="text-success"><?php echo e($examName); ?></span>
        - Class: <span class="text-success"><?php echo e($className); ?></span></h3>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Grades table: Display only if no error and grades data exists -->
    <!--[if BLOCK]><![endif]--><?php if(!empty($gradesCount) && !$errorMessage): ?>
    <table class="table table-bordered table-hover mt-4" style="border-radius: 0.5rem; overflow: hidden;">
        <thead class="thead-light">
            <tr>
                <th rowspan="2">Class</th>
                <th rowspan="2">Section</th>
                <th rowspan="2">Student Count</th>
                <th rowspan="2">Mean Score</th>
                <th colspan="14" class="text-center">Grades Distribution</th>
            </tr>
            <tr>
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
                <th>F</th>
            </tr>
        </thead>
        <tbody>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $gradesCount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classId => $classData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $sectionCount = count($classData['sections']); ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classData['sections']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionId => $sectionData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <!--[if BLOCK]><![endif]--><?php if($loop->first): ?>
                <td rowspan="<?php echo e($sectionCount); ?>"><?php echo e($classData['class_name']); ?></td>
                <!-- Class name displayed only once -->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <td><?php echo e($sectionData['section_name']); ?></td>
                <td><?php echo e($sectionData['student_count']); ?></td> <!-- Display student count -->
                <td><?php echo e(number_format($sectionData['mean_score'], 2)); ?></td> <!-- Display mean score -->
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sectionData['grades']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($count); ?></td> <!-- Display grade counts -->
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </tbody>
    </table>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Display message if no grades found -->
    <!--[if BLOCK]><![endif]--><?php if(empty($gradesCount) && !$errorMessage): ?>
    <div class="alert alert-info mt-4" style="border-radius: 0.5rem; font-weight: bold;">
        <strong>Note:</strong> No grades data found for the selected exam: <span class="text-success"><?php echo e($examName); ?></span>.
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Additional logic to set error message if there are no grades -->
    <?php if(empty($gradesCount) && $examId): ?>
    <?php
    $errorMessage = "No student results found for the selected exam: " . $examName;
    ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\projects\MbukuErp\resources\views/livewire/class-analysis.blade.php ENDPATH**/ ?>