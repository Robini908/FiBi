<div>
    <!-- Search and filters -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4 sm:mb-0">All Grading Systems</h3>
        
        <div class="w-full sm:w-auto">
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    wire:model.debounce.300ms="search" 
                    class="input input-bordered pl-10 py-2 w-full" 
                    placeholder="Search grading systems..."
                >
            </div>
        </div>
    </div>
    
    <!-- Grading Systems Grid -->
    <div 
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        wire:loading.class="opacity-50"
        x-data="{ navigating: false }"
        x-bind:class="{ 'opacity-50 pointer-events-none': navigating }"
    >
        @forelse ($gradingSystems as $gs)
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-200">
                <div class="card-body p-5">
                    <h3 class="card-title text-lg font-semibold text-gray-900">{{ $gs->name }}</h3>
                    <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                        {{ $gs->description ?: 'Grading system for ' . $gs->name }}
                    </p>
                    
                    <div class="divider my-2"></div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Effective from:</span>
                            <span class="badge badge-outline">{{ $gs->effective_date ? date('d M, Y', strtotime($gs->effective_date)) : 'Not set' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Grades defined:</span>
                            <span class="badge {{ $gs->gradeRanges->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} gap-1">
                                {{ $gs->gradeRanges->count() }}
                            </span>
                        </div>
                        
                        @if($gs->gradeRanges->count() == 0)
                            <div class="alert alert-warning mt-2 py-2 text-xs flex items-center">
                                <svg class="h-4 w-4 mr-1.5 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>No grade ranges defined yet</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-actions justify-between bg-gray-50 px-5 py-3 border-t border-gray-200">
                    <button 
                        wire:click="editGradingSystem({{ $gs->id }})" 
                        wire:loading.attr="disabled"
                        class="btn btn-sm btn-ghost gap-1 text-gray-600 hover:text-green-700 transition-colors duration-150"
                        x-data=""
                        @click.prevent="navigating = true; $dispatch('navigate-to', {route: '{{ route('exams.grading-systems.edit', $gs->id) }}'})"
                    >
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        <span>Edit</span>
                        <span class="loading loading-spinner loading-xs text-green-600" wire:loading wire:target="editGradingSystem({{ $gs->id }})"></span>
                    </button>
                    
                    <a 
                        href="{{ route('exams.grading-systems.show', $gs->id) }}" 
                        class="btn btn-sm bg-green-600 hover:bg-green-700 text-white gap-1 border-none"
                        x-data=""
                        @click.prevent="navigating = true; $dispatch('navigate-to', {route: '{{ route('exams.grading-systems.show', $gs->id) }}'})"
                    >
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        <span>View Details</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full card bg-base-100 p-8 border-2 border-dashed border-gray-300 text-center">
                <div class="card-body items-center text-center">
                    <svg class="mx-auto h-14 w-14 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-base font-medium text-gray-900 mb-1">No grading systems found</h3>
                    <p class="text-sm text-gray-500 mb-6">Get started by creating your first grading system</p>
                    <button 
                        wire:click="showGradingSystemForm" 
                        class="btn bg-green-600 hover:bg-green-700 text-white gap-2 border-none"
                        x-data=""
                        @click.prevent="navigating = true; $dispatch('navigate-to', {route: '{{ route('exams.grading-systems.create') }}'})"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Create Grading System</span>
                        <span class="loading loading-spinner loading-xs" wire:loading wire:target="showGradingSystemForm"></span>
                    </button>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if(isset($gradingSystems) && method_exists($gradingSystems, 'links') && $gradingSystems->hasPages())
        <div class="mt-8">
            <div class="pagination-wrapper">
                {{ $gradingSystems->links() }}
    </div>
        </div>
    @endif
</div> 