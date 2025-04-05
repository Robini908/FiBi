<div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="img-fluid rounded" style="max-height: 250px;">
                        @else
                            <div class="bg-slate-200 rounded p-3" style="height: 250px;">
                                <i class="icon-book2 icon-5x mt-5 text-slate-500"></i>
                                <p class="mt-3">No cover image available</p>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3 text-center">
                        <span class="badge {{ $book->is_available ? 'badge-success' : 'badge-secondary' }} mb-2">
                            {{ $availableCopies }} / {{ $copies->count() }} Copies Available
                        </span>
                        
                        @if($book->is_reference_only)
                            <span class="badge badge-info mb-2">Reference Only</span>
                        @endif
                        
                        @if($book->is_featured)
                            <span class="badge badge-primary mb-2">Featured</span>
                        @endif
                        
                        <span class="badge {{ $book->is_active ? 'badge-success' : 'badge-danger' }} mb-2">
                            {{ $book->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if(!$book->is_reference_only && $availableCopies > 0 && $canRequestBorrow)
                        <div class="text-center mb-3">
                            @if($userHasActiveLoan)
                                <button type="button" class="btn btn-secondary btn-block" disabled>
                                    <i class="icon-checkmark4 mr-2"></i> Currently Borrowed
                                </button>
                            @elseif($userHasActiveReservation)
                                <button type="button" class="btn btn-secondary btn-block" disabled>
                                    <i class="icon-checkmark4 mr-2"></i> Already Reserved
                                </button>
                            @else
                                <button type="button" class="btn btn-success btn-block" wire:click="openReservationModal">
                                    <i class="icon-bookmark mr-2"></i> Reserve Book
                                </button>
                            @endif
                        </div>
                    @endif

                    @if($canManageBook)
                        <div class="text-center mb-3">
                            <div class="btn-group btn-block">
                                <button type="button" class="btn btn-primary" onclick="window.location='{{ route('library.books.edit', $book->id) }}'">
                                    <i class="icon-pencil7 mr-2"></i> Edit
                                </button>
                                <button type="button" class="btn btn-primary" wire:click="openCopyModal">
                                    <i class="icon-plus2 mr-2"></i> Add Copy
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title font-weight-semibold">Book Details</h6>
                </div>

                <div class="card-body">
                    <div class="mb-2">
                        <span class="font-weight-semibold">ISBN:</span>
                        <span class="float-right">{{ $book->isbn ?: 'N/A' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">ISBN-13:</span>
                        <span class="float-right">{{ $book->isbn13 ?: 'N/A' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">Category:</span>
                        <span class="float-right">{{ $book->category ? $book->category->name : 'Uncategorized' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">Language:</span>
                        <span class="float-right">{{ $book->language ?: 'N/A' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">Publisher:</span>
                        <span class="float-right">{{ $book->publisher ?: 'N/A' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">Publication Date:</span>
                        <span class="float-right">{{ $book->publication_date ? $book->publication_date->format('M d, Y') : 'N/A' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">Edition:</span>
                        <span class="float-right">{{ $book->edition ?: 'N/A' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">Pages:</span>
                        <span class="float-right">{{ $book->pages ?: 'N/A' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">Dewey Decimal:</span>
                        <span class="float-right">{{ $book->dewey_decimal ?: 'N/A' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">Call Number:</span>
                        <span class="float-right">{{ $book->call_number ?: 'N/A' }}</span>
                    </div>

                    <div class="mb-2">
                        <span class="font-weight-semibold">Replacement Cost:</span>
                        <span class="float-right">${{ number_format($book->replacement_cost, 2) ?: 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="font-weight-semibold mt-1">{{ $book->title }}</h4>
                    @if($book->subtitle)
                        <h6 class="text-muted">{{ $book->subtitle }}</h6>
                    @endif

                    <div class="mb-3">
                        <h6 class="font-weight-semibold">Author(s):</h6>
                        <div>
                            @forelse($book->authors as $author)
                                <span class="badge badge-flat badge-pill badge-light mr-1 mb-1">{{ $author->name }}</span>
                            @empty
                                <span class="text-muted">No authors listed</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-semibold">Description:</h6>
                        <div class="text-muted">
                            {!! nl2br(e($book->description ?: 'No description available.')) !!}
                        </div>
                    </div>

                    @if($book->table_of_contents)
                        <div class="mb-3">
                            <h6 class="font-weight-semibold">Table of Contents:</h6>
                            <div class="text-muted">
                                {!! nl2br(e($book->table_of_contents)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Copies and Availability Card -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title font-weight-semibold">Book Copies</h6>
                    <div class="header-elements">
                        <span class="badge badge-info ml-auto">{{ $copies->count() }} Total Copies</span>
                    </div>
                </div>

                <div class="card-body">
                    @if($copies->isEmpty())
                        <div class="alert alert-warning">
                            No copies of this book are currently in the library inventory.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Copy #</th>
                                        <th>Barcode/RFID</th>
                                        <th>Location</th>
                                        <th>Condition</th>
                                        <th>Status</th>
                                        @if($canManageBook)
                                            <th class="text-center">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($copies as $copy)
                                        <tr>
                                            <td>{{ $copy->copy_number }}</td>
                                            <td>{{ $copy->barcode ?: ($copy->rfid_tag ?: 'N/A') }}</td>
                                            <td>{{ $copy->shelf_location ?: 'Unspecified' }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($copy->condition == 'New') badge-success
                                                    @elseif($copy->condition == 'Good') badge-info
                                                    @elseif($copy->condition == 'Fair') badge-warning
                                                    @elseif($copy->condition == 'Poor') badge-danger
                                                    @else badge-secondary
                                                    @endif">
                                                    {{ $copy->condition }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($copy->status == 'Available') badge-success
                                                    @elseif($copy->status == 'On Loan') badge-warning
                                                    @elseif($copy->status == 'In Repair') badge-danger
                                                    @elseif($copy->status == 'Lost') badge-dark
                                                    @else badge-secondary
                                                    @endif">
                                                    {{ $copy->status }}
                                                </span>
                                            </td>
                                            @if($canManageBook)
                                                <td class="text-center">
                                                    <div class="list-icons">
                                                        <div class="dropdown">
                                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                                <i class="icon-menu9"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                @if($copy->status === 'Available')
                                                                    <a href="#" class="dropdown-item">
                                                                        <i class="icon-bag"></i> Checkout
                                                                    </a>
                                                                @elseif($copy->status === 'On Loan')
                                                                    <a href="#" class="dropdown-item">
                                                                        <i class="icon-enter"></i> Check In
                                                                    </a>
                                                                @endif
                                                                <a href="#" class="dropdown-item">
                                                                    <i class="icon-pencil7"></i> Edit
                                                                </a>
                                                                <a href="#" class="dropdown-item">
                                                                    <i class="icon-history"></i> View History
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Current Loans Card -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title font-weight-semibold">Current Loans</h6>
                    <div class="header-elements">
                        <span class="badge badge-primary ml-auto">{{ $activeLoanCount }} Active</span>
                    </div>
                </div>

                <div class="card-body">
                    @if($activeLoanCount == 0)
                        <div class="alert alert-info">
                            No copies of this book are currently on loan.
                        </div>
                    @else
                        <p>There are currently {{ $activeLoanCount }} copies of this book on loan.</p>
                        @if($canManageBook)
                            <a href="{{ route('library.loans.index') }}?book_id={{ $book->id }}" class="btn btn-primary">
                                <i class="icon-eye mr-2"></i> View Loan Details
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Reservations Card -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title font-weight-semibold">Reservations</h6>
                    <div class="header-elements">
                        <span class="badge badge-warning ml-auto">{{ $reservationCount }} Pending</span>
                    </div>
                </div>

                <div class="card-body">
                    @if($reservationCount == 0)
                        <div class="alert alert-info">
                            No reservations for this book are currently pending.
                        </div>
                    @else
                        <p>There are currently {{ $reservationCount }} pending reservations for this book.</p>
                        @if($canManageBook)
                            <a href="{{ route('library.reservations.index') }}?book_id={{ $book->id }}" class="btn btn-primary">
                                <i class="icon-eye mr-2"></i> View Reservation Details
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Copy Modal -->
    @if($showCopyModal)
    <div class="modal fade show" tabindex="-1" style="display: block; padding-right: 17px; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="icon-copy4 mr-2"></i>
                        Add New Book Copy
                    </h5>
                    <button type="button" class="close" wire:click="$set('showCopyModal', false)">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="saveCopy">
                        <div class="form-group">
                            <label>Copy Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('copyNumber') is-invalid @enderror" wire:model="copyNumber" placeholder="Copy identifier (e.g. C001)">
                            @error('copyNumber') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Barcode</label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" wire:model="barcode" placeholder="Barcode">
                                    @error('barcode') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>RFID Tag</label>
                                    <input type="text" class="form-control @error('rfidTag') is-invalid @enderror" wire:model="rfidTag" placeholder="RFID tag">
                                    @error('rfidTag') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Acquisition Date</label>
                                    <input type="date" class="form-control @error('acquisitionDate') is-invalid @enderror" wire:model="acquisitionDate">
                                    @error('acquisitionDate') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </span>
                                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" wire:model="price" placeholder="0.00">
                                    </div>
                                    @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Acquisition Source</label>
                            <input type="text" class="form-control" wire:model="acquisitionSource" placeholder="Where or who the book was acquired from">
                        </div>

                        <div class="form-group">
                            <label>Shelf Location</label>
                            <input type="text" class="form-control @error('shelfLocation') is-invalid @enderror" wire:model="shelfLocation" placeholder="Shelf location">
                            @error('shelfLocation') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Condition <span class="text-danger">*</span></label>
                                    <select class="form-control @error('condition') is-invalid @enderror" wire:model="condition">
                                        <option value="New">New</option>
                                        <option value="Good">Good</option>
                                        <option value="Fair">Fair</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                    @error('condition') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Condition Notes</label>
                                    <input type="text" class="form-control" wire:model="conditionNotes" placeholder="Notes about condition">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" wire:click="$set('showCopyModal', false)">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="saveCopy">
                        <i class="icon-checkmark3 mr-2"></i> Save Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Reservation Modal -->
    @if($showReservationModal)
    <div class="modal fade show" tabindex="-1" style="display: block; padding-right: 17px; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-teal">
                    <h5 class="modal-title">
                        <i class="icon-bookmark mr-2"></i>
                        Reserve Book
                    </h5>
                    <button type="button" class="close" wire:click="$set('showReservationModal', false)">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="saveReservation">
                        <div class="form-group">
                            <label>Book</label>
                            <input type="text" class="form-control" readonly value="{{ $book->title }}">
                        </div>

                        <div class="form-group">
                            <label>Notes (Optional)</label>
                            <textarea class="form-control @error('reservationNotes') is-invalid @enderror" wire:model="reservationNotes" rows="3" placeholder="Any special notes or requests"></textarea>
                            @error('reservationNotes') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Priority</label>
                            <select class="form-control @error('reservationPriority') is-invalid @enderror" wire:model="reservationPriority">
                                <option value="Low">Low</option>
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                            </select>
                            @error('reservationPriority') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="icon-info22 mr-2"></i> Your reservation will be reviewed by a librarian. You will be notified when the book is available for pickup.
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" wire:click="$set('showReservationModal', false)">Cancel</button>
                    <button type="button" class="btn btn-teal" wire:click="saveReservation">
                        <i class="icon-checkmark3 mr-2"></i> Submit Reservation
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div> 