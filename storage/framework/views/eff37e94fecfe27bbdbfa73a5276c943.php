<div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <!-- Session Alerts -->
    <?php if (isset($component)) { $__componentOriginalcca61bfded94b5a7635453a4dc55dd1d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcca61bfded94b5a7635453a4dc55dd1d = $attributes; } ?>
<?php $component = App\View\Components\FlashMessages::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\FlashMessages::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcca61bfded94b5a7635453a4dc55dd1d)): ?>
<?php $attributes = $__attributesOriginalcca61bfded94b5a7635453a4dc55dd1d; ?>
<?php unset($__attributesOriginalcca61bfded94b5a7635453a4dc55dd1d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcca61bfded94b5a7635453a4dc55dd1d)): ?>
<?php $component = $__componentOriginalcca61bfded94b5a7635453a4dc55dd1d; ?>
<?php unset($__componentOriginalcca61bfded94b5a7635453a4dc55dd1d); ?>
<?php endif; ?>

    <!-- Class Selection -->
    <div class="form-group col-span-6">
        <label for="class">Select Class:</label>
        <select wire:model.live="selectedClass" class="form-control" id="class">
            <option value="">-- Select Class --</option>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </select>

        <div wire:loading wire:target="selectedClass">
            <div class="d-flex justify-content-center my-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>

            </div>
        </div>
    </div>




    <!-- Selected Class Information -->
    <!--[if BLOCK]><![endif]--><?php if($selectedClass): ?>
        <div class="alert alert-info mt-3">
            <strong>Instructions:</strong> You have selected the class <strong><?php echo e($selectedClassName); ?></strong>.
            Now, please choose the exam from the list below to assign marks for students.
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Exam Selection -->
    <!--[if BLOCK]><![endif]--><?php if($selectedClass): ?>
        <div class="form-group mt-3">
            <label for="exam">Select Exam:</label>
            <select wire:model.live="selectedExam" class="form-control" id="exam">
                <option value="">-- Select Exam --</option>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($exam->id); ?>"><?php echo e($exam->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </select>
            <div wire:loading wire:target="selectedExam">
                <div class="d-flex justify-content-center my-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>

                </div>
            </div>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Section Selection -->
    <!--[if BLOCK]><![endif]--><?php if($selectedExam): ?>


        <div>
            <!--[if BLOCK]><![endif]--><?php if($sections->isNotEmpty()): ?>


                <div class="form-group mb-4 alert alert-info">
                    <label>Select the Stream:</label>
                    <div class="d-flex flex-wrap">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check mr-4 mb-2">
                                <input type="radio" wire:model.live="selectedSection" value="<?php echo e($section->id); ?>"
                                    id="section_<?php echo e($section->id); ?>" class="form-check-input"
                                    wire:key="section-<?php echo e($section->id); ?>">
                                <label for="section_<?php echo e($section->id); ?>"
                                    class="form-check-label"><?php echo e($section->name); ?></label>

                                <!--[if BLOCK]><![endif]--><?php if($selectedSection == $section->id): ?>
                                    <div wire:loading wire:target="selectedSection" class="mt-1">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>





                <!--[if BLOCK]><![endif]--><?php if($selectedSection): ?>
                <div class="mt-3">
                    <div class="alert alert-info">
                        <b><?php echo e(count($students)); ?></b> students found in this section.
                    </div>
                </div>
            
                <form wire:submit.prevent="assignMarks" class="mt-3" id="marks-form">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 25%;">Student Name</th>
                                    <th scope="col" style="width: 15%;">Admission No</th>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th scope="col" style="width: 20%;"><?php echo e($subject->subject_name); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tr>
                            </thead>
                            <tbody>
                                <!--[if BLOCK]><![endif]--><?php if($students->isEmpty()): ?>
                                    <tr>
                                        <td colspan="<?php echo e(count($subjects) + 3); ?>" class="text-center text-muted">
                                            No students available for this section.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $isSubjectSelectionEnabled = $this->isSubjectSelectionEnabled($student->my_class_id);
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo e($index + 1); ?></th>
                                            <td><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></td>
                                            <td><?php echo e($student->adm_no); ?></td>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td>
                                                    <!--[if BLOCK]><![endif]--><?php if(!$isSubjectSelectionEnabled || $student->subjects->contains($subject->id)): ?>
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <input type="number" class="form-control"
                                                                wire:model="marks.<?php echo e($student->id); ?>.<?php echo e($subject->id); ?>"
                                                                min="0" max="100" placeholder="Marks"
                                                                style="width: 100px;"
                                                                <?php if(!empty($this->specialGrades[$student->id][$subject->id])): ?> disabled <?php endif; ?>>
                                                            <select class="form-control"
                                                                wire:model="specialGrades.<?php echo e($student->id); ?>.<?php echo e($subject->id); ?>"
                                                                style="width: 100px;"
                                                                <?php if(!empty($this->marks[$student->id][$subject->id])): ?> disabled <?php endif; ?>>
                                                                <option value="">Grade</option>
                                                                <option value="X">X</option>
                                                                <option value="Y">Y</option>
                                                                <option value="Z">Z</option>
                                                            </select>
                                                        </div>
                                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["marks.{$student->id}.{$subject->id}"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <small class="text-danger"><?php echo e($message); ?></small>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["specialGrades.{$student->id}.{$subject->id}"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <small class="text-danger"><?php echo e($message); ?></small>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                                    <?php else: ?>
                                                        <span class="text-muted">Not Enrolled</span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
            
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo e($buttonText); ?>

                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-circle"></i> Please select a section to proceed.
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php else: ?>
                <p class="text-danger mt-3">No sections available for this class and exam combination.</p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Bootstrap tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Show tooltip on focus and input
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    tooltipTriggerEl.addEventListener('focus', function() {
                        var tooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                        if (tooltip) {
                            tooltip.show();
                        }
                    });

                    tooltipTriggerEl.addEventListener('blur', function() {
                        var tooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                        if (tooltip) {
                            tooltip.hide();
                        }
                    });

                    // Show tooltip on input
                    tooltipTriggerEl.addEventListener('input', function() {
                        var tooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                        if (tooltip) {
                            tooltip.show();
                        }
                    });
                });

                // Real-time validation for marks input fields
                const inputs = document.querySelectorAll('.mark-input');
                const successAlert = document.getElementById('success-alert');

                // Function to validate all inputs and highlight empty ones
                function validateMarks() {
                    let allValid = true;

                    // Loop through each input field
                    inputs.forEach(input => {
                        if (input.value === '') {
                            // If input is empty, highlight it with a red border
                            input.style.border = '2px solid red';
                            allValid = false;
                        } else {
                            // If input is filled, remove any red border
                            input.style.border = '';
                        }
                    });

                    // Display success message if all fields are valid
                    if (allValid) {
                        successAlert.style.display = 'block';
                        successAlert.innerText = 'All marks have been assigned successfully!';
                    } else {
                        successAlert.style.display = 'none'; // Hide success alert if any field is invalid
                    }
                }

                // Initial validation when the form loads
                validateMarks();

                // Real-time validation as user interacts with the form
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        validateMarks(); // Validate fields as they are updated
                    });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>

</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/assign-batch-marks.blade.php ENDPATH**/ ?>