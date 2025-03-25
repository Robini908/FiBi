<?php if(Session::has('_toast_script')): ?>
    <?php echo Session::get('_toast_script'); ?>

<?php endif; ?>

<script>
    window.addEventListener('toast', event => {
        window.livewire.emit('toast', event.detail.type, event.detail.message, event.detail.title, event.detail.duration);
    });
</script> <?php /**PATH C:\projects\MbukuErp\resources\views/components/toast-scripts.blade.php ENDPATH**/ ?>