<form wire:submit.prevent="saveGradingSystem" x-data="{ 
    currentFocus: 0,
    rangeCount: <?php if ((object) ('gradeRanges') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('gradeRanges'->value()); ?>')<?php echo e('gradeRanges'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('gradeRanges'); ?>')<?php endif; ?>.length,
    init() {
        window.addEventListener('keydown', (e) => {
            // Alt+N - Add new grade range
            if (e.altKey && e.key === 'n') {
                e.preventDefault();
                $wire.addGradeRange();
            }
            // Alt+S - Save grading system
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                $wire.call('saveGradingSystem');
            }
            // Escape - Cancel
            if (e.key === 'Escape') {
                e.preventDefault();
                $wire.resetGradingSystemForm();
            }
        });

        this.$watch('rangeCount', value => {
            // Focus on the first input of newly added grade range
            if (value > 0) {
                setTimeout(() => {
                    const lastGradeInput = document.getElementById(`grade-${value-1}`);
                    if (lastGradeInput) {
                        lastGradeInput.focus();
                        // Scroll to the newly added grade range
                        lastGradeInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 100);
            }
        });
        
        // Listen for the gradeRangeAdded event from Livewire
        Livewire.on('gradeRangeAdded', (data) => {
            setTimeout(() => this.scrollToBottom(), 100);
        });
    },
    scrollToBottom() {
        const container = document.getElementById('grade-ranges-container');
        if (container) {
            setTimeout(() => {
                // Get the last grade range row
                const ranges = this.rangeCount;
                if (ranges > 0) {
                    const lastRow = document.getElementById(`grade-range-row-${ranges-1}`);
                    if (lastRow) {
                        lastRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        // Focus on the grade input of the new row
                        const gradeInput = document.getElementById(`grade-${ranges-1}`);
                        if (gradeInput) {
                            gradeInput.focus();
                        }
                    }
                }
            }, 100);
        }
    }
}">
    <div class="space-y-6">
        <!-- Basic Information Section -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
            <h4 class="text-base font-medium text-gray-900 mb-4">Basic Information</h4>
            <div class="space-y-4">
                <div>
                    <label for="gradingSystemName" class="block text-sm font-medium text-gray-700">System Name*</label>
                    <input
                        type="text"
                        wire:model="gradingSystemName"
                        id="gradingSystemName"
                        class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="e.g., Standard Grading System"
                        autofocus
                    >
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['gradingSystemName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="gradingSystemEffectiveDate" class="block text-sm font-medium text-gray-700">Effective Date*</label>
                        <input
                            type="date"
                            wire:model="gradingSystemEffectiveDate"
                            id="gradingSystemEffectiveDate"
                            class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        >
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['gradingSystemEffectiveDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <div>
                        <label for="subjectsApplied" class="block text-sm font-medium text-gray-700">Applied to Subjects</label>
                        <div class="relative">
                            <select
                                wire:model="selectedSubjects"
                                id="subjectsApplied"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                                multiple
                            >
                                <option value="">All Subjects (Default)</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->subject_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <div class="mt-1 text-xs text-gray-500">Ctrl+Click to select multiple subjects</div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="gradingSystemDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                        wire:model="gradingSystemDescription"
                        id="gradingSystemDescription"
                        rows="2"
                        class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="Briefly describe the grading system..."
                    ></textarea>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['gradingSystemDescription'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>

        <!-- Grade Ranges Section -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-base font-medium text-gray-900">Grade Ranges</h4>
                <button
                    type="button"
                    wire:click="addGradeRange"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                >
                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Grade Range
                </button>
            </div>

            <!--[if BLOCK]><![endif]--><?php if(count($gradeRanges) > 0): ?>
                <div class="overflow-hidden bg-gray-50 rounded-lg border border-gray-200">
                    <!-- Headers -->
                    <div class="bg-gray-100 p-3 grid grid-cols-12 gap-2 border-b border-gray-200 text-sm font-medium text-gray-700 sticky top-0 z-10">
                        <div class="col-span-1 text-center">#</div>
                        <div class="col-span-2">Grade</div>
                        <div class="col-span-3">Min Score (%)</div>
                        <div class="col-span-3">Max Score (%)</div>
                        <div class="col-span-2">Remark</div>
                        <div class="col-span-1 text-center">Actions</div>
                    </div>

                    <!-- Ranges list -->
                    <div class="divide-y divide-gray-200 max-h-80 overflow-y-auto" id="grade-ranges-container">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $gradeRanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-3 grid grid-cols-12 gap-2 items-center hover:bg-gray-50" :class="{'bg-green-50': currentFocus === <?php echo e($index); ?>}" id="grade-range-row-<?php echo e($index); ?>">
                                <div class="col-span-1 text-center font-medium text-gray-700"><?php echo e($index + 1); ?></div>
                                
                                <div class="col-span-2">
                                    <input
                                        type="text"
                                        wire:model="gradeRanges.<?php echo e($index); ?>.grade"
                                        id="grade-<?php echo e($index); ?>"
                                        class="focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="A, B+, etc."
                                        @focus="currentFocus = <?php echo e($index); ?>"
                                        @keydown.enter.prevent="$event.target.closest('.grid').querySelector('input[id^=min-score]').focus()"
                                    >
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["gradeRanges.{$index}.grade"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                
                                <div class="col-span-3">
                                    <div class="flex items-center">
                                        <input
                                            type="number"
                                            wire:model="gradeRanges.<?php echo e($index); ?>.min_score"
                                            id="min-score-<?php echo e($index); ?>"
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            class="focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            placeholder="0-100"
                                            @focus="currentFocus = <?php echo e($index); ?>"
                                            @keydown.enter.prevent="$event.target.closest('.grid').querySelector('input[id^=max-score]').focus()"
                                        >
                                        <span class="ml-2 text-gray-500">%</span>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["gradeRanges.{$index}.min_score"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                
                                <div class="col-span-3">
                                    <div class="flex items-center">
                                        <input
                                            type="number"
                                            wire:model="gradeRanges.<?php echo e($index); ?>.max_score"
                                            id="max-score-<?php echo e($index); ?>"
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            class="focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            placeholder="0-100"
                                            @focus="currentFocus = <?php echo e($index); ?>"
                                            @keydown.enter.prevent="$event.target.closest('.grid').querySelector('input[id^=remark]').focus()"
                                        >
                                        <span class="ml-2 text-gray-500">%</span>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["gradeRanges.{$index}.max_score"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                
                                <div class="col-span-2">
                                    <input
                                        type="text"
                                        wire:model="gradeRanges.<?php echo e($index); ?>.remark"
                                        id="remark-<?php echo e($index); ?>"
                                        class="focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Excellent, etc."
                                        @focus="currentFocus = <?php echo e($index); ?>"
                                        @keydown.enter.prevent="$wire.addGradeRange()"
                                    >
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["gradeRanges.{$index}.remark"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                
                                <div class="col-span-1 text-center">
                                    <button
                                        type="button"
                                        wire:click="removeGradeRange(<?php echo e($index); ?>)"
                                        class="text-red-500 hover:text-red-700 focus:outline-none p-1 transition-colors duration-200"
                                        title="Remove grade range"
                                    >
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                
                <!-- Bottom Add Button - NEW -->
                <div class="mt-4 flex justify-center sticky bottom-0 py-3 px-2 bg-white bg-opacity-95 border-t border-gray-200 shadow-md z-20">
                    <button
                        type="button"
                        wire:click="addGradeRange"
                        @click="setTimeout(() => scrollToBottom(), 150)"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-3 sm:py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                    >
                        <svg class="-ml-0.5 mr-2 h-5 w-5 sm:h-4 sm:w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Another Grade Range
                    </button>
                </div>
                
                <div class="mt-3 p-3 bg-gray-50 rounded-md text-sm text-gray-600">
                    <p>
                        <span class="font-medium">Tips:</span>
                        <span class="ml-1">Press Enter to move to the next field. Enter on the remark field adds a new range.</span>
                    </p>
                    <p class="mt-1">
                        <span class="font-medium">Ranges:</span>
                        <span class="ml-1">Ensure your grade ranges don't overlap and cover the full 0-100% scale.</span>
                    </p>
                </div>
                
                <!-- Visual Scale -->
                <div class="mt-4 px-2">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Grade Scale Visualization</h5>
                    <div class="h-8 w-full bg-gray-200 rounded-md relative overflow-hidden">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $gradeRanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $startPercent = max(0, min(100, $range['min_score']));
                                $endPercent = max(0, min(100, $range['max_score']));
                                $width = $endPercent - $startPercent;
                                $left = $startPercent;
                                
                                // Generate a color based on the score range
                                $hue = 120 - ($startPercent * 1.2); // 120 (green) for high scores, 0 (red) for low scores
                                $color = "hsla($hue, 70%, 50%, 0.7)";
                            ?>
                            
                            <div class="absolute h-full flex items-center justify-center text-xs font-bold text-white overflow-hidden" 
                                 style="left: <?php echo e($left); ?>%; width: <?php echo e($width); ?>%; background-color: <?php echo e($color); ?>;">
                                <!--[if BLOCK]><![endif]--><?php if($width > 5): ?>
                                    <?php echo e($range['grade']); ?>

                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <!-- Score markers -->
                        <div class="absolute top-0 w-full h-full">
                            <!--[if BLOCK]><![endif]--><?php for($i = 0; $i <= 100; $i += 10): ?>
                                <div class="absolute h-2 border-l border-gray-400" style="left: <?php echo e($i); ?>%"></div>
                                <div class="absolute text-xs text-gray-600" style="left: <?php echo e($i); ?>%; top: 10px"><?php echo e($i); ?></div>
                            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
                
            <?php else: ?>
                <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No grade ranges defined</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding your first grade range.</p>
                    <div class="mt-4">
                        <button
                            type="button"
                            wire:click="addGradeRange"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                        >
                            <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add First Grade Range
                        </button>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['gradeRanges'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="mt-1 text-red-500 text-sm"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Quick Templates Section -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Quick Templates</h4>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="applyTemplate('standard')" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Standard (A-F)
                </button>
                <button type="button" wire:click="applyTemplate('letter')" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Letter Grades
                </button>
                <button type="button" wire:click="applyTemplate('percentage')" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Percentage Based
                </button>
                <button type="button" wire:click="applyTemplate('gpa')" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    GPA Style
                </button>
            </div>
            <p class="mt-2 text-xs text-gray-500">Click a template to quickly populate common grade ranges</p>
        </div>

        <!-- Footer/Buttons -->
        <div class="pt-5 border-t border-gray-200 flex justify-end space-x-3">
            <button
                type="button"
                wire:click="resetGradingSystemForm"
                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors duration-200"
            >
                Cancel
            </button>
            <button
                type="submit"
                class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors duration-200"
            >
                <?php echo e($isEditingGradingSystem ? 'Update Grading System' : 'Create Grading System'); ?>

            </button>
        </div>
    </div>
</form> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/exams/partials/cards/grading-system-form.blade.php ENDPATH**/ ?>