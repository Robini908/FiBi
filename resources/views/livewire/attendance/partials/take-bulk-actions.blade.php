<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-5">
    <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h4 class="text-sm font-medium text-gray-700">Quick Actions</h4>
    </div>
    
    <div class="px-6 py-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Main action buttons with more prominent styling -->
            <button type="button" wire:click="setAllStatus('present')" 
                class="inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                All Present
            </button>
            
            <button type="button" wire:click="setAllStatus('absent')" 
                class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                All Absent
            </button>
            
            <button type="button" wire:click="setAllStatus('late')" 
                class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                All Late
            </button>
            
            <!-- More options dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button type="button" 
                    @click="open = !open"
                    class="inline-flex justify-center items-center w-full px-3 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    More Options
                </button>
                
                <div x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="transform opacity-0 scale-95" 
                    x-transition:enter-end="transform opacity-100 scale-100" 
                    x-transition:leave="transition ease-in duration-75" 
                    x-transition:leave-start="transform opacity-100 scale-100" 
                    x-transition:leave-end="transform opacity-0 scale-95" 
                    class="origin-top-right absolute right-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                    style="display: none;">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <button type="button" wire:click="setAllStatus('excused')" class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-50" role="menuitem">All Excused</button>
                        <button type="button" wire:click="setAllStatus('sick')" class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-50" role="menuitem">All Sick</button>
                        <button type="button" wire:click="setAllStatus('on_leave')" class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-50" role="menuitem">All On Leave</button>
                        <button type="button" wire:click="setAllStatus('other')" class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-50" role="menuitem">All Other</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Selection tools -->
        <div class="mt-4 border-t border-gray-100 pt-4">
            <div class="flex flex-wrap gap-2">
                <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500">
                    Select All
                </button>
                <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500">
                    Select None
                </button>
                <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500">
                    Invert Selection
                </button>
                <span class="inline-flex items-center text-xs text-gray-500">Select students, then apply an action above</span>
            </div>
        </div>
    </div>
</div> 