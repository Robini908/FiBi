<button wire:click="closeAction"
    class="mb-4 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-colors">
    <svg class="mr-2 h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <!--[if BLOCK]><![endif]--><?php if($isAddingStudent): ?>
        Back to Student List
    <?php else: ?>
        Back to Students
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</button> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/back-button.blade.php ENDPATH**/ ?>