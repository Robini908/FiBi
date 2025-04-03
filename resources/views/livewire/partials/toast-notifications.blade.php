{{-- Google-like Toast Notifications --}}
<div x-data="{ 
    notices: [], 
    visible: [],
    add(notice) {
        const id = Date.now();
        this.notices.push({ 
            id, 
            type: notice.type || 'info',
            title: notice.title || '',
            message: notice.message,
            autoDismiss: notice.autoDismiss ?? true
        });
        this.visible.push(id);
        
        if (notice.autoDismiss ?? true) {
            setTimeout(() => {
                this.dismiss(id);
            }, 5000);
        }
    },
    dismiss(id) {
        const index = this.visible.indexOf(id);
        if (index > -1) {
            this.visible.splice(index, 1);
        }
    }
}" 
@notify.window="add($event.detail)"
class="fixed top-4 right-4 z-50 flex flex-col space-y-4 max-w-sm">
    <template x-for="notice in notices" :key="notice.id">
        <div 
            x-show="visible.includes(notice.id)" 
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="google-card bg-white shadow-md relative overflow-hidden"
            :class="{
                'border-l-4 border-blue-500': notice.type === 'info',
                'border-l-4 border-green-500': notice.type === 'success',
                'border-l-4 border-yellow-500': notice.type === 'warning',
                'border-l-4 border-red-500': notice.type === 'error'
            }">
            <!-- Progress bar for auto-dismiss -->
            <div 
                x-show="notice.autoDismiss"
                class="absolute top-0 left-0 right-0 h-0.5" 
                :class="{
                    'bg-blue-500': notice.type === 'info',
                    'bg-green-500': notice.type === 'success',
                    'bg-yellow-500': notice.type === 'warning',
                    'bg-red-500': notice.type === 'error'
                }">
                <div class="h-full bg-gray-100" style="animation: toast-progress 5s linear forwards;"></div>
            </div>
            
            <div class="p-4 flex">
                <!-- Icon -->
                <div class="flex-shrink-0 mr-3">
                    <!-- Success Icon -->
                    <svg x-show="notice.type === 'success'" class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <!-- Info Icon -->
                    <svg x-show="notice.type === 'info'" class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <!-- Warning Icon -->
                    <svg x-show="notice.type === 'warning'" class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <!-- Error Icon -->
                    <svg x-show="notice.type === 'error'" class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                
                <!-- Content -->
                <div class="flex-1">
                    <div x-show="notice.title" class="font-medium text-gray-900" x-text="notice.title"></div>
                    <div class="text-sm text-gray-600" x-text="notice.message"></div>
                </div>
                
                <!-- Close Button -->
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="dismiss(notice.id)" type="button" class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
@keyframes toast-progress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>

<script>
    window.addEventListener('toast', event => {
        window.dispatchEvent(new CustomEvent('notify', { 
            detail: {
                type: event.detail.type,
                title: event.detail.title,
                message: event.detail.message,
                autoDismiss: event.detail.autoDismiss
            }
        }));
    });

    // Map LivewireAlert to our toast system
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof window.livewire !== 'undefined') {
            window.addEventListener('alert', event => {
                const type = event.detail.type || 'info';
                const message = event.detail.message || '';
                const title = type.charAt(0).toUpperCase() + type.slice(1);
                
                window.dispatchEvent(new CustomEvent('notify', { 
                    detail: {
                        type: type === 'success' ? 'success' :
                              type === 'error' ? 'error' :
                              type === 'warning' ? 'warning' : 'info',
                        title: title,
                        message: message,
                        autoDismiss: true
                    }
                }));
            });
        }
    });
</script> 