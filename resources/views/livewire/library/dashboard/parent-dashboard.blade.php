<div>
    <div class="row">
        <!-- Statistics cards -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-blue-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-users4 icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ count($children) }}</h3>
                        <span class="text-uppercase font-size-xs">My Children</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-warning-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-bag icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ $totalActiveLoans ?? 0 }}</h3>
                        <span class="text-uppercase font-size-xs">Current Loans</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-danger-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-alarm icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ $totalOverdueLoans ?? 0 }}</h3>
                        <span class="text-uppercase font-size-xs">Overdue Items</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-indigo-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-coin-dollar icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ number_format($totalFines ?? 0, 2) }}</h3>
                        <span class="text-uppercase font-size-xs">Total Fines</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick actions -->
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title">Quick Actions</h6>
                </div>

                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <a href="{{ route('library.catalog') }}" class="btn bg-teal-400 btn-icon btn-lg mb-3 rounded-round">
                                <i class="icon-books"></i>
                            </a>
                            <h5 class="mb-0">Browse Catalog</h5>
                            <div class="text-muted">Find books</div>
                        </div>

                        <div class="col-4">
                            <a href="{{ route('library.my-children-books') }}" class="btn bg-primary-400 btn-icon btn-lg mb-3 rounded-round">
                                <i class="icon-bag"></i>
                            </a>
                            <h5 class="mb-0">Children's Loans</h5>
                            <div class="text-muted">View & manage</div>
                        </div>

                        <div class="col-4">
                            <a href="{{ route('library.reservations.user') }}" class="btn bg-warning-400 btn-icon btn-lg mb-3 rounded-round">
                                <i class="icon-alarm-check"></i>
                            </a>
                            <h5 class="mb-0">Reservations</h5>
                            <div class="text-muted">Check status</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($children) > 0)
        <!-- Children's sections -->
        @foreach($children as $child)
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header header-elements-inline bg-light">
                            <h5 class="card-title">
                                <i class="icon-user mr-2"></i> {{ $child->name }}'s Library Activity
                                @if($child->student && $child->student->class)
                                    <span class="badge badge-info ml-2">{{ $child->student->class->name ?? '' }}</span>
                                @endif
                            </h5>
                            <div class="header-elements">
                                <a href="{{ route('library.my-children-books', ['student_id' => $child->id]) }}" class="text-primary">View All</a>
                            </div>
                        </div>

                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-body bg-blue-100 shadow-none border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h3 class="mb-0">{{ $child->activeLoans ?? 0 }}</h3>
                                                <span class="text-muted">Active Loans</span>
                                            </div>
                                            <div>
                                                <i class="icon-book icon-2x opacity-75 text-blue-800"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-body bg-warning-100 shadow-none border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h3 class="mb-0">{{ $child->overdueLoans ?? 0 }}</h3>
                                                <span class="text-muted">Overdue Items</span>
                                            </div>
                                            <div>
                                                <i class="icon-alarm icon-2x opacity-75 text-warning-800"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-body bg-success-100 shadow-none border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h3 class="mb-0">{{ $child->reservations ?? 0 }}</h3>
                                                <span class="text-muted">Reservations</span>
                                            </div>
                                            <div>
                                                <i class="icon-calendar icon-2x opacity-75 text-success-800"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-body bg-indigo-100 shadow-none border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h3 class="mb-0">{{ number_format($child->fines ?? 0, 2) }}</h3>
                                                <span class="text-muted">Unpaid Fines</span>
                                            </div>
                                            <div>
                                                <i class="icon-coin-dollar icon-2x opacity-75 text-indigo-800"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!empty($child->loans) && count($child->loans) > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Book</th>
                                            <th>Checkout Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($child->loans as $loan)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($loan->book->cover_image)
                                                            <div class="mr-3">
                                                                <img src="{{ asset('storage/'.$loan->book->cover_image) }}" class="rounded-circle" width="40" height="40" alt="">
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <a href="{{ route('library.books.show', $loan->book_id) }}" class="text-body font-weight-semibold">{{ $loan->book->title }}</a>
                                                            <div class="text-muted font-size-sm">
                                                                @if($loan->book->authors)
                                                                    By {{ $loan->book->authors->pluck('name')->join(', ') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $loan->issue_date->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="{{ $loan->isOverdue() ? 'text-danger font-weight-semibold' : '' }}">
                                                        {{ $loan->due_date->format('M d, Y') }}
                                                    </span>
                                                    @if($loan->isOverdue())
                                                        <span class="badge badge-danger ml-2">{{ $loan->days_overdue }} days overdue</span>
                                                    @elseif($loan->due_date->diffInDays(now()) <= 3 && $loan->due_date > now())
                                                        <span class="badge badge-warning ml-2">Due soon</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $loan->status == 'Active' ? 'badge-success' : ($loan->status == 'Overdue' ? 'badge-danger' : 'badge-secondary') }}">
                                                        {{ $loan->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card-body pt-0">
                                <div class="alert alert-info text-center mb-0">
                                    {{ $child->name }} doesn't have any active book loans.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-warning mb-0">
                            <h5 class="alert-heading">No Children Found!</h5>
                            <p>We couldn't find any children associated with your account. If you believe this is an error, please contact the school administrator to link your children to your account.</p>
                            <hr>
                            <p class="mb-0">Once your children are linked to your account, you'll be able to view their library activities, book loans, and other information.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recommended books -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Recommended Books for Students</h5>
                    <div class="header-elements">
                        <a href="{{ route('library.catalog') }}" class="text-primary">Browse All</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        @forelse($featuredBooks ?? [] as $book)
                            <div class="col-xl-3 col-md-4 col-sm-6">
                                <div class="card">
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
                                            @if($book->copies_available > 0)
                                                <a href="#" wire:click.prevent="reserveBook({{ $book->id }})" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
                                                    <i class="icon-calendar3"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="d-flex align-items-start flex-nowrap">
                                            <div>
                                                <h6 class="font-weight-semibold mr-2">{{ Str::limit($book->title, 50) }}</h6>
                                                <span class="font-size-sm text-muted">
                                                    @if($book->authors)
                                                        By {{ $book->authors->pluck('name')->join(', ') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer d-flex justify-content-between">
                                        <span class="text-muted">{{ $book->category->name }}</span>
                                        <span class="badge {{ $book->copies_available > 0 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $book->copies_available > 0 ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center">
                                <p>No recommended books at this time.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservation Modal -->
    @if(isset($selectedBook))
        <div wire:ignore.self class="modal fade" id="reserveBookModal" tabindex="-1" role="dialog" aria-labelledby="reserveBookModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="reserveBookModalLabel">Reserve Book for Child</h5>
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
                            <label>Select Child:</label>
                            <select wire:model="selectedChildId" class="form-control @error('selectedChildId') is-invalid @enderror">
                                <option value="">-- Select child --</option>
                                @foreach($children as $child)
                                    <option value="{{ $child->id }}">{{ $child->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedChildId')
                                <span class="text-danger">{{ $errors->first('selectedChildId') }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Reservation Date:</label>
                            <input type="date" wire:model="reservationDate" class="form-control @error('reservationDate') is-invalid @enderror" 
                                min="{{ now()->format('Y-m-d') }}" 
                                max="{{ now()->addDays(14)->format('Y-m-d') }}">
                            @error('reservationDate')
                                <span class="text-danger">{{ $errors->first('reservationDate') }}</span>
                            @enderror
                        </div>
                        
                        <p class="text-muted">
                            <small>
                                * Reservations are valid for 2 weeks. If the book becomes available during this period, 
                                your child will be notified and have 48 hours to check it out.
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