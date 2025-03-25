@props([
    'type' => 'default',
    'title' => '',
    'message' => '',
    'position' => 'top-right',
    'timeout' => 5000,
    'closeable' => true,
    'progressBar' => true,
])

@php
    // Positioning classes
    $positionClasses = [
        'top-right' => 'top-4 right-4',
        'top-left' => 'top-4 left-4',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-left' => 'bottom-4 left-4',
        'top-center' => 'top-4 left-1/2 transform -translate-x-1/2',
        'bottom-center' => 'bottom-4 left-1/2 transform -translate-x-1/2'
    ][$position] ?? 'top-4 right-4';
    
    // Type-based appearance
    $typeClasses = [
        'default' => [
            'bg' => 'bg-white',
            'border' => 'border-gray-300',
            'icon' => '<svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                       </svg>',
            'progress' => 'bg-gray-500'
        ],
        'success' => [
            'bg' => 'bg-white',
            'border' => 'border-green-300',
            'icon' => '<svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                       </svg>',
            'progress' => 'bg-green-500'
        ],
        'info' => [
            'bg' => 'bg-white',
            'border' => 'border-blue-300',
            'icon' => '<svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                       </svg>',
            'progress' => 'bg-blue-500'
        ],
        'warning' => [
            'bg' => 'bg-white',
            'border' => 'border-yellow-300',
            'icon' => '<svg class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                       </svg>',
            'progress' => 'bg-yellow-500'
        ],
        'error' => [
            'bg' => 'bg-white',
            'border' => 'border-red-300',
            'icon' => '<svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>',
            'progress' => 'bg-red-500'
        ],
    ][$type] ?? $typeClasses['default'];
@endphp

<!-- Toast Container to be included once in your layout -->
<div
    x-data="{ 
        toasts: [],
        add(toast) {
            // Set a unique ID
            toast.id = Date.now();
            
            // Add the toast
            this.toasts.push(toast);
            
            // Auto-remove after timeout if not sticky
            if (toast.timeout) {
                setTimeout(() => {
                    this.remove(toast.id);
                }, toast.timeout);
            }
        },
        remove(id) {
            this.toasts = this.toasts.filter(toast => toast.id !== id);
        }
    }"
    @new-toast.window="add($event.detail)"
    class="fixed z-50 {{ $positionClasses }} w-full max-w-sm"
    aria-live="assertive"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-data="{ 
                progress: 100,
                start: null,
                remaining: toast.timeout,
                paused: false,
                
                init() {
                    if (!toast.timeout || !toast.progressBar) return;
                    
                    this.start = Date.now();
                    this.updateProgress();
                },
                
                updateProgress() {
                    if (this.paused || !toast.progressBar) return;
                    
                    const elapsed = Date.now() - this.start;
                    this.progress = 100 - (elapsed / toast.timeout * 100);
                    
                    if (this.progress > 0) {
                        requestAnimationFrame(() => this.updateProgress());
                    }
                },
                
                pause() {
                    if (!toast.timeout || !toast.progressBar) return;
                    
                    this.paused = true;
                    this.remaining = this.remaining - (Date.now() - this.start);
                },
                
                resume() {
                    if (!toast.timeout || !toast.progressBar) return;
                    
                    this.paused = false;
                    this.start = Date.now();
                    this.updateProgress();
                    
                    setTimeout(() => {
                        $dispatch('remove-toast', toast.id);
                    }, this.remaining);
                }
            }"
            x-init="init()"
            @mouseover="pause()"
            @mouseleave="resume()"
            class="relative overflow-hidden bg-white border rounded-lg shadow-lg mb-3 pointer-events-auto transform transition-all duration-300 ease-out"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            :class="toast.type === 'success' ? 'border-green-300' : 
                   toast.type === 'info' ? 'border-blue-300' : 
                   toast.type === 'warning' ? 'border-yellow-300' : 
                   toast.type === 'error' ? 'border-red-300' : 'border-gray-300'"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0" x-html="
                        toast.type === 'success' ? 
                        '<svg class=\'h-6 w-6 text-green-500\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>' : 
                        toast.type === 'info' ? 
                        '<svg class=\'h-6 w-6 text-blue-500\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>' : 
                        toast.type === 'warning' ? 
                        '<svg class=\'h-6 w-6 text-yellow-500\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z\' /></svg>' : 
                        toast.type === 'error' ? 
                        '<svg class=\'h-6 w-6 text-red-500\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>' : 
                        '<svg class=\'h-6 w-6 text-gray-400\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'
                    "></div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p x-show="toast.title" x-text="toast.title" class="text-sm font-medium text-gray-900"></p>
                        <p x-text="toast.message" class="mt-1 text-sm text-gray-500"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            x-show="toast.closeable !== false"
                            @click="$dispatch('remove-toast', toast.id)"
                            class="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Progress bar -->
            <div 
                x-show="toast.progressBar && toast.timeout"
                class="absolute bottom-0 left-0 h-1"
                :class="
                    toast.type === 'success' ? 'bg-green-500' : 
                    toast.type === 'info' ? 'bg-blue-500' : 
                    toast.type === 'warning' ? 'bg-yellow-500' : 
                    toast.type === 'error' ? 'bg-red-500' : 'bg-gray-500'
                "
                :style="'width: ' + progress + '%'"
            ></div>
        </div>
    </template>
</div>

<script>
// Global Toast functions for ease of use
window.Toast = {
    show(message, title = '', type = 'default', options = {}) {
        const toast = {
            message,
            title,
            type,
            timeout: options.timeout ?? {{ $timeout }},
            closeable: options.closeable ?? {{ $closeable ? 'true' : 'false' }},
            progressBar: options.progressBar ?? {{ $progressBar ? 'true' : 'false' }}
        };
        
        window.dispatchEvent(new CustomEvent('new-toast', { detail: toast }));
    },
    
    success(message, title = '', options = {}) {
        this.show(message, title, 'success', options);
    },
    
    info(message, title = '', options = {}) {
        this.show(message, title, 'info', options);
    },
    
    warning(message, title = '', options = {}) {
        this.show(message, title, 'warning', options);
    },
    
    error(message, title = '', options = {}) {
        this.show(message, title, 'error', options);
    }
};
</script> 