<div>
    <!-- Search and Filters -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Library Catalog</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Search:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search by title, author, or ISBN..." 
                                wire:model.debounce.300ms="search">
                            <span class="input-group-append">
                                <button class="btn bg-primary" type="button">
                                    <i class="icon-search4"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Category:</label>
                        <select class="form-control" wire:model="categoryId">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Availability:</label>
                        <select class="form-control" wire:model="availability">
                            <option value="">All</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Publication Year:</label>
                        <select class="form-control" wire:model="publicationYear">
                            <option value="">All Years</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sort By:</label>
                        <select class="form-control" wire:model="sortField">
                            <option value="title">Title</option>
                            <option value="publication_date">Publication Date</option>
                            <option value="copies_available">Availability</option>
                            <option value="created_at">Recently Added</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Order:</label>
                        <select class="form-control" wire:model="sortDirection">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Display:</label>
                        <select class="form-control" wire:model="perPage">
                            <option value="12">12</option>
                            <option value="24">24</option>
                            <option value="36">36</option>
                            <option value="48">48</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="font-size-sm text-muted">
                    Showing {{ $books->firstItem() ?? 0 }} to {{ $books->lastItem() ?? 0 }} of {{ $books->total() ?? 0 }} books
                </div>
                <div>
                    <button type="button" class="btn btn-light" wire:click="resetFilters">
                        <i class="icon-rotate-ccw2 mr-2"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="row">
        @forelse($books as $book)
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <div class="card h-100">
                    <div class="card-img-actions mx-1 mt-1">
                        @if($book->cover_image)
                            <img class="card-img img-fluid" src="{{ asset('storage/'.$book->cover_image) }}" alt="{{ $book->title }}">
                        @else
                            <div class="bg-light text-center py-4">
                                <i class="icon-book icon-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-img-actions-overlay card-img">
                            <a href="{{ route('library.books.show', $book->id) }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
                                <i class="icon-eye"></i>
                            </a>
                            
                            @if(auth()->user() && !Qs::userIsLibrarian() && !$book->is_reference && $book->copies_available > 0)
                                <a href="#" wire:click.prevent="reserveBook({{ $book->id }})" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
                                    <i class="icon-calendar3"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-start flex-nowrap">
                            <div>
                                <h6 class="font-weight-semibold mr-2">
                                    <a href="{{ route('library.books.show', $book->id) }}" class="text-body">
                                        {{ Str::limit($book->title, 40) }}
                                    </a>
                                </h6>
                                @if($book->subtitle)
                                    <div class="text-muted mb-2">{{ Str::limit($book->subtitle, 50) }}</div>
                                @endif
                                <span class="font-size-sm text-muted">
                                    @if($book->authors)
                                        By {{ $book->authors->pluck('name')->join(', ') }}
                                    @endif
                                </span>
                                
                                <div class="mt-2 mb-2">
                                    @if($book->is_reference)
                                        <span class="badge badge-info">Reference Only</span>
                                    @endif
                                    
                                    @if($book->is_featured)
                                        <span class="badge badge-primary">Featured</span>
                                    @endif
                                    
                                    @if($book->copies_available <= 0)
                                        <span class="badge badge-danger">Unavailable</span>
                                    @else
                                        <span class="badge badge-success">{{ $book->copies_available }} Available</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer d-flex justify-content-between">
                        <span class="text-muted">{{ $book->category->name }}</span>
                        @if($book->publication_date)
                            <span class="text-muted">{{ $book->publication_date->format('Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="icon-books icon-2x mb-2"></i>
                    <h4>No Books Found</h4>
                    <p>Try adjusting your search or filter criteria</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $books->links() }}
    </div>

    <!-- Reservation Modal -->
    @if(isset($selectedBook))
        <div wire:ignore.self class="modal fade" id="reserveBookModal" tabindex="-1" role="dialog" aria-labelledby="reserveBookModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="reserveBookModalLabel">Reserve Book</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to reserve:</p>
                        <h4>{{ $selectedBook->title }}</h4>
                        <p class="text-muted">
                            @if($selectedBook->authors)
                                By {{ $selectedBook->authors->pluck('name')->join(', ') }}
                            @endif
                        </p>
                        
                        <div class="form-group">
                            <label>Reservation Date:</label>
                            <input type="date" wire:model="reservationDate" class="form-control" 
                                min="{{ now()->format('Y-m-d') }}" 
                                max="{{ now()->addDays(14)->format('Y-m-d') }}">
                            @error('reservationDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <p class="text-muted">
                            <small>
                                * Reservations are valid for 2 weeks. If the book becomes available during this period, 
                                you will be notified and have 48 hours to check it out.
                            </small>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                        <button type="button" wire:click="submitReservation" wire:loading.attr="disabled" class="btn btn-primary">
                            <span wire:loading wire:target="submitReservation" class="spinner-border spinner-border-sm mr-2"></span>
                            Reserve Book
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('page_scripts')
    <script>
        window.addEventListener('show-reserve-modal', event => {
            $('#reserveBookModal').modal('show');
        });
        
        window.addEventListener('hide-reserve-modal', event => {
            $('#reserveBookModal').modal('hide');
            if(event.detail.message) {
                Swal.fire({
                    icon: event.detail.type,
                    title: event.detail.title,
                    text: event.detail.message,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    </script>
    @endpush
</div> 