<div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
    <div class="card-header">
        <h2 class="h5">Champion Leaderboard</h2>
    </div>
    <div class="card-body">
        <div class="form-row mb-4">
            <!-- Class Selection -->
            <div class="col-md-4">
                <label for="classSelect" class="form-label">Select Class:</label>
                <div class="input-group">
                    <select wire:model.live="classId" id="classSelect" class="form-control">
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

            <!--[if BLOCK]><![endif]--><?php if($classId): ?>
                <!-- Stream Selection -->
                <div class="col-md-4">
                    <label for="streamSelect" class="form-label">Select Stream:</label>
                    <div class="input-group">
                        <select wire:model.live="streamId" id="streamSelect" class="form-control">
                            <option value="">Select Stream</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classes->find($classId)->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($section->id); ?>"><?php echo e($section->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <div wire:loading wire:target="streamId" class="input-group-append">
                            <span class="input-group-text">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if($classId): ?>
                <!-- Exam Selection -->
                <div class="col-md-4">
                    <label for="examSelect" class="form-label">Select Exam (Mandatory):</label>
                    <div class="input-group">
                        <select wire:model.live="examId" id="examSelect" class="form-control">
                            <option value="">Select Exam (Mandatory)</option>
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

            <!-- Term and Year Filtering -->
            <!--[if BLOCK]><![endif]--><?php if($examId): ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="term">Term</label>
                        <select wire:model.live="term" id="term" class="form-control"
                            <?php echo e(!$classId ? 'disabled' : ''); ?>>
                            <option value="">Select Term</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $terms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($term); ?>"><?php echo e($term); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year">Year</label>
                        <select wire:model.live="year" id="year" class="form-control"
                            <?php echo e(!$classId ? 'disabled' : ''); ?>>
                            <option value="">Select Year</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>

        <!-- Get Champions Button -->
        <!--[if BLOCK]><![endif]--><?php if($examId): ?>
            <button wire:click="getChampions" class="btn btn-primary d-flex align-items-center">
                Get Champions
                <div wire:loading wire:target="getChampions" class="spinner-border spinner-border-sm text-light ms-2"
                    role="status"></div>
            </button>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if($errorMessage && $classId && $examId): ?>
            <div class="text-danger mt-2"><?php echo e($errorMessage); ?></div> <!-- Display error message -->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if($champions && $champions->isNotEmpty()): ?>
            <h3 class="h6 mt-4">Champions for <?php echo e($examId ? $exams->find($examId)->name : ''); ?>

                <!--[if BLOCK]><![endif]--><?php if($classId): ?>
                    in <?php echo e($classes->find($classId)->name); ?>

                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <!--[if BLOCK]><![endif]--><?php if($streamId): ?>
                    - <?php echo e($classes->find($classId)->sections->find($streamId)->name); ?>

                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </h3>
            <div class="d-flex justify-content-end mb-4">
                <!-- Export to PDF Button -->
                <button wire:click="exportPDF" wire:loading.attr="disabled" wire:loading.class="btn-secondary"
                    wire:target="exportPDF" class="btn btn-danger btn-sm mx-1">
                    <i class="fas fa-file-pdf"></i>
                    <span wire:loading.remove wire:target="exportPDF">PDF</span>
                    <span wire:loading wire:target="exportPDF">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>

                <!-- Export to Excel Button -->
                <button wire:click="exportExcel" wire:loading.attr="disabled" wire:loading.class="btn-secondary"
                    wire:target="exportExcel" class="btn btn-success btn-sm mx-1">
                    <i class="fas fa-file-excel"></i>
                    <span wire:loading.remove wire:target="exportExcel">Excel</span>
                    <span wire:loading wire:target="exportExcel">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>
            </div>


            <div class="table-responsive">


                <table id="championsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Subject</th>
                            <th scope="col">Student</th>
                            
                            <th scope="col">Stream</th>
                            <th scope="col">Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $champions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $champion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--[if BLOCK]><![endif]--><?php if($champion && $champion->subject && $champion->student && $champion->student->section): ?>
                                <tr>
                                    <td><?php echo e($champion->subject->subject_name); ?></td>
                                    <td><?php echo e($champion->student->first_name); ?> <?php echo e($champion->student->last_name); ?> - <?php echo e($champion->student->adm_no); ?></td>
                                    <td><?php echo e($champion->student->section->name); ?></td>
                                    <td><?php echo e($champion->marks); ?></td>
                                </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>





            <!--[if BLOCK]><![endif]--><?php if($errorMessage): ?>
                <div class="alert alert-danger">
                    <?php echo e($errorMessage); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php else: ?>
            <div class="mt-4 text-muted">No champions found for the selected criteria.</div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/subject-champions.blade.php ENDPATH**/ ?>