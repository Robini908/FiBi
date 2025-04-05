<div>
    <div class="row">
        <!-- Report type selection and filters -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Library Reports</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Report Type:</label>
                                <select wire:model="reportType" class="form-control">
                                    <option value="overdue">Overdue Books</option>
                                    <option value="circulation">Circulation History</option>
                                    <option value="inventory">Inventory</option>
                                    <option value="popular">Popular Books</option>
                                    <option value="borrower">Borrower History</option>
                                    <option value="fines">Fines Report</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Search:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search by title, ISBN, or borrower name..." 
                                        wire:model.debounce.300ms="search">
                                    <span class="input-group-append">
                                        <button class="btn bg-primary" type="button">
                                            <i class="icon-search4"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date range filters for circulation, popular, borrower reports -->
                    @if(in_array($reportType, ['circulation', 'popular', 'borrower']))
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date From:</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-calendar22"></i></span>
                                    </span>
                                    <input type="date" class="form-control" wire:model="dateStart">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date To:</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-calendar22"></i></span>
                                    </span>
                                    <input type="date" class="form-control" wire:model="dateEnd">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Category filter for inventory report -->
                    @if($reportType === 'inventory')
                    <div class="row">
                        <div class="col-md-6">
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
                    </div>
                    @endif

                    <!-- Borrower selection for borrower report -->
                    @if($reportType === 'borrower')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Borrower:</label>
                                <div class="input-group">
                                    <select class="form-control select-search" wire:model="borrowerId">
                                        <option value="">-- Select a borrower --</option>
                                        @foreach($borrowers ?? [] as $borrower)
                                            <option value="{{ $borrower->id }}">{{ $borrower->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-light" type="button" wire:click="openBorrowerSearch">
                                            <i class="icon-search4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-light" wire:click="resetFilters">
                                <i class="icon-rotate-ccw2 mr-2"></i> Reset Filters
                            </button>
                        </div>
                        <div>
                            <span class="text-muted mr-3">Display:</span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary {{ $perPage == 25 ? 'active' : '' }}" wire:click="$set('perPage', 25)">25</button>
                                <button type="button" class="btn btn-outline-secondary {{ $perPage == 50 ? 'active' : '' }}" wire:click="$set('perPage', 50)">50</button>
                                <button type="button" class="btn btn-outline-secondary {{ $perPage == 100 ? 'active' : '' }}" wire:click="$set('perPage', 100)">100</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Report data display -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title">
                        @if($reportType === 'overdue')
                            Overdue Books Report
                        @elseif($reportType === 'circulation')
                            Circulation History Report
                        @elseif($reportType === 'inventory')
                            Inventory Report
                        @elseif($reportType === 'popular')
                            Popular Books Report
                        @elseif($reportType === 'borrower')
                            Borrower History Report
                        @elseif($reportType === 'fines')
                            Fines Report
                        @endif
                    </h6>
                    <div class="header-elements">
                        <span class="badge badge-info">{{ $reportData->total() ?? 0 }} {{ Str::plural('record', $reportData->total() ?? 0) }}</span>
                    </div>
                </div>

                @if($reportType === 'overdue')
                <!-- Overdue Books Report -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Borrower</th>
                                <th>Due Date</th>
                                <th>Days Overdue</th>
                                <th>Fine</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $loan)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <a href="{{ route('library.books.show', $loan->bookCopy->book->id) }}" class="text-body font-weight-semibold">
                                                    {{ $loan->bookCopy->book->title }}
                                                </a>
                                                <div class="text-muted font-size-sm">
                                                    Copy #{{ $loan->bookCopy->copy_number ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $loan->borrower->name }}</td>
                                    <td>{{ $loan->due_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="font-weight-semibold text-danger">
                                            {{ now()->diffInDays($loan->due_date) }} days
                                        </span>
                                    </td>
                                    <td>{{ number_format($loan->fine_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No overdue books found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @elseif($reportType === 'circulation')
                <!-- Circulation History Report -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Borrower</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $loan)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <a href="{{ route('library.books.show', $loan->bookCopy->book->id) }}" class="text-body font-weight-semibold">
                                                    {{ $loan->bookCopy->book->title }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $loan->borrower->name }}</td>
                                    <td>{{ $loan->issue_date->format('M d, Y') }}</td>
                                    <td>{{ $loan->due_date->format('M d, Y') }}</td>
                                    <td>{{ $loan->return_date ? $loan->return_date->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($loan->status == 'Active') badge-success
                                            @elseif($loan->status == 'Overdue') badge-danger
                                            @elseif($loan->status == 'Returned') badge-info
                                            @else badge-secondary
                                            @endif">
                                            {{ $loan->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No circulation records found for the selected period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @elseif($reportType === 'inventory')
                <!-- Inventory Report -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>ISBN</th>
                                <th>Category</th>
                                <th>Total Copies</th>
                                <th>Available</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $book)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <a href="{{ route('library.books.show', $book->id) }}" class="text-body font-weight-semibold">
                                                    {{ $book->title }}
                                                </a>
                                                @if($book->subtitle)
                                                    <div class="text-muted font-size-sm">{{ $book->subtitle }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $book->isbn ?? 'N/A' }}</td>
                                    <td>{{ $book->category->name ?? 'Uncategorized' }}</td>
                                    <td>{{ $book->copies_count }}</td>
                                    <td>{{ $book->copies_available }}</td>
                                    <td>
                                        @if($book->is_reference)
                                            <span class="badge badge-info">Reference Only</span>
                                        @elseif($book->copies_available > 0)
                                            <span class="badge badge-success">Available</span>
                                        @else
                                            <span class="badge badge-danger">Unavailable</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No books found matching the criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @elseif($reportType === 'popular')
                <!-- Popular Books Report -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Authors</th>
                                <th>Times Borrowed</th>
                                <th>Available Copies</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $book)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <a href="{{ route('library.books.show', $book->id) }}" class="text-body font-weight-semibold">
                                                    {{ $book->title }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $book->category->name ?? 'Uncategorized' }}</td>
                                    <td>
                                        @if($book->authors && $book->authors->count() > 0)
                                            {{ $book->authors->pluck('name')->join(', ') }}
                                        @else
                                            Unknown
                                        @endif
                                    </td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $book->loans_count }}</span>
                                    </td>
                                    <td>{{ $book->copies_available }} / {{ $book->copies_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No popular books found for the selected period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @elseif($reportType === 'borrower')
                <!-- Borrower History Report -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Copy #</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $loan)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <a href="{{ route('library.books.show', $loan->bookCopy->book->id) }}" class="text-body font-weight-semibold">
                                                    {{ $loan->bookCopy->book->title }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $loan->bookCopy->copy_number ?? 'N/A' }}</td>
                                    <td>{{ $loan->issue_date->format('M d, Y') }}</td>
                                    <td>{{ $loan->due_date->format('M d, Y') }}</td>
                                    <td>{{ $loan->return_date ? $loan->return_date->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($loan->status == 'Active') badge-success
                                            @elseif($loan->status == 'Overdue') badge-danger
                                            @elseif($loan->status == 'Returned') badge-info
                                            @else badge-secondary
                                            @endif">
                                            {{ $loan->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        @if($borrowerId)
                                            No loan history found for this borrower.
                                        @else
                                            Please select a borrower to view their loan history.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @elseif($reportType === 'fines')
                <!-- Fines Report -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Borrower</th>
                                <th>Book</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Fine Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $loan)
                                <tr>
                                    <td>{{ $loan->borrower->name }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <a href="{{ route('library.books.show', $loan->bookCopy->book->id) }}" class="text-body font-weight-semibold">
                                                    {{ $loan->bookCopy->book->title }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $loan->due_date->format('M d, Y') }}</td>
                                    <td>{{ $loan->return_date ? $loan->return_date->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <span class="font-weight-semibold">{{ number_format($loan->fine_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($loan->is_fine_paid)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-danger">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No fines found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                <div class="card-footer bg-white d-flex justify-content-center">
                    {{ $reportData->links() }}
                </div>
            </div>
        </div>

        <!-- Export options -->
        <div class="col-md-4">
            @include('livewire.library.reports.export-report')
        </div>
    </div>

    <!-- Borrower search modal -->
    @if($reportType === 'borrower')
    <div wire:ignore.self class="modal fade" id="borrowerSearchModal" tabindex="-1" role="dialog" aria-labelledby="borrowerSearchModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="borrowerSearchModalLabel">Search Borrower</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Search:</label>
                        <input type="text" class="form-control" wire:model.debounce.300ms="borrowerSearch" placeholder="Enter name, email, or ID...">
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($searchedBorrowers ?? [] as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role->name ?? 'N/A' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                wire:click="selectBorrower({{ $user->id }})" 
                                                data-dismiss="modal">
                                                Select
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No users found matching your search.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(!empty($searchedBorrowers) && $searchedBorrowers->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $searchedBorrowers->links() }}
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('page_scripts')
    <script>
        window.addEventListener('show-borrower-search-modal', event => {
            $('#borrowerSearchModal').modal('show');
        });
    </script>
    @endpush
</div> 