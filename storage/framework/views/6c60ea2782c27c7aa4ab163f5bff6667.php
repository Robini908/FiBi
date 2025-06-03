<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Include the header partial -->
    <?php echo $__env->make('livewire.partials.bulk-import.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="px-6 pb-6">
        <!-- Template information panel (expandable) -->
        <?php echo $__env->make('livewire.partials.bulk-import.template-info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- File upload component -->
        <?php echo $__env->make('livewire.partials.bulk-import.file-upload', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Import results - only shown when results are available -->
        <!--[if BLOCK]><![endif]--><?php if(isset($importResults)): ?>
           
            <?php echo $__env->make('livewire.partials.bulk-import.import-results', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>

    <?php
        $__scriptKey = '1615341837-0';
        ob_start();
    ?>
    <script>
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('fileUploadProgress', progress => {
                // Update progress via Alpine.js
                if (window.Alpine) {
                    window.dispatchEvent(new CustomEvent('upload-progress-updated', {
                        detail: {
                            progress: progress
                        }
                    }));
                }
            });
        });
    </script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/addbulk.blade.php ENDPATH**/ ?>