<div>
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Library Book Collection</h5>
            <div class="header-elements">
                <div class="list-icons">
                    @if($canManageBooks)
                    <button type="button" wire:click="createBook" class="btn btn-teal">
                        <i class="icon-plus2 mr-2"></i>
                        Add New Book
                    </button>
                    @endif
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="reload"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-search4"></i></span>
                            </span>
                            <input type="text" class="form-control" placeholder="Search by title, author, ISBN..." wire:model.debounce.300ms="search">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" wire:model="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" wire:model="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" wire:model="perPage">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('title')" style="cursor: pointer;" class="@if($sortField === 'title') text-primary @endif">
                                Title 
                                @if($sortField === 'title')
                                    <i class="icon-arrow-{{ $sortDirection === 'asc' ? 'up5' : 'down5' }} ml-1"></i>
                                @endif
                            </th>
                            <th>Author(s)</th>
                            <th wire:click="sortBy('isbn')" style="cursor: pointer;" class="@if($sortField === 'isbn') text-primary @endif">
                                ISBN
                                @if($sortField === 'isbn')
                                    <i class="icon-arrow-{{ $sortDirection === 'asc' ? 'up5' : 'down5' }} ml-1"></i>
                                @endif
                            </th>
                            <th>Category</th>
                            <th wire:click="sortBy('publication_date')" style="cursor: pointer;" class="@if($sortField === 'publication_date') text-primary @endif">
                                Publication Date
                                @if($sortField === 'publication_date')
                                    <i class="icon-arrow-{{ $sortDirection === 'asc' ? 'up5' : 'down5' }} ml-1"></i>
                                @endif
                            </th>
                            <th>Availability</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($book->cover_image)
                                        <div class="mr-3">
                                            <img src="{{ asset('storage/' . $book->cover_image) }}" width="40" height="50" alt="{{ $book->title }}" class="rounded">
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('library.books.show', $book->id) }}" class="font-weight-semibold">{{ $book->title }}</a>
                                        @if($book->subtitle)
                                            <div class="text-muted">{{ Str::limit($book->subtitle, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $book->author_names }}</td>
                            <td>{{ $book->isbn }}</td>
                            <td>{{ $book->category ? $book->category->name : 'Uncategorized' }}</td>
                            <td>{{ $book->publication_date ? $book->publication_date->format('M d, Y') : '' }}</td>
                            <td>
                                <span class="badge {{ $book->is_available ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $book->copies_available }} / {{ $book->total_copies }} Available
                                </span>
                                @if($book->is_reference_only)
                                    <span class="badge badge-info ml-1">Reference Only</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $book->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $book->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('library.books.show', $book->id) }}" class="dropdown-item">
                                                <i class="icon-file-eye"></i> View Details
                                            </a>
                                            @if($canManageBooks)
                                                <a href="#" wire:click.prevent="editBook({{ $book->id }})" class="dropdown-item">
                                                    <i class="icon-pencil7"></i> Edit
                                                </a>
                                                <a href="#" wire:click.prevent="confirmDelete({{ $book->id }})" class="dropdown-item">
                                                    <i class="icon-trash"></i> Delete
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $books->links() }}
            </div>
        </div>
    </div>

    <!-- Book Form Modal -->
    @if($showBookModal)
    <div class="modal fade show" tabindex="-1" style="display: block; padding-right: 17px; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="icon-book mr-2"></i>
                        {{ $bookId ? 'Edit Book' : 'Add New Book' }}
                    </h5>
                    <button type="button" class="close" wire:click="$set('showBookModal', false)">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="saveBook">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title" placeholder="Book title">
                                    @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Subtitle</label>
                                    <input type="text" class="form-control" wire:model="subtitle" placeholder="Book subtitle (optional)">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ISBN</label>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" wire:model="isbn" placeholder="ISBN">
                                    @error('isbn') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ISBN-13</label>
                                    <input type="text" class="form-control @error('isbn13') is-invalid @enderror" wire:model="isbn13" placeholder="ISBN-13">
                                    @error('isbn13') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" wire:model="category_id">
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" wire:model="description" rows="3" placeholder="Book description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Publisher</label>
                                    <input type="text" class="form-control" wire:model="publisher" placeholder="Publisher">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Publication Date</label>
                                    <input type="date" class="form-control @error('publication_date') is-invalid @enderror" wire:model="publication_date">
                                    @error('publication_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Edition</label>
                                    <input type="text" class="form-control" wire:model="edition" placeholder="Edition">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Pages</label>
                                    <input type="number" class="form-control @error('pages') is-invalid @enderror" wire:model="pages" placeholder="Number of pages">
                                    @error('pages') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Language</label>
                                    <input type="text" class="form-control" wire:model="language" placeholder="Language">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Dewey Decimal</label>
                                    <input type="text" class="form-control" wire:model="dewey_decimal" placeholder="Dewey decimal">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Call Number</label>
                                    <input type="text" class="form-control" wire:model="call_number" placeholder="Call number">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Replacement Cost</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </span>
                                        <input type="number" step="0.01" class="form-control @error('replacement_cost') is-invalid @enderror" wire:model="replacement_cost" placeholder="0.00">
                                    </div>
                                    @error('replacement_cost') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-checkbox mr-3 d-inline-block">
                                        <input type="checkbox" class="custom-control-input" id="is_reference_only" wire:model="is_reference_only">
                                        <label class="custom-control-label" for="is_reference_only">Reference Only</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3 d-inline-block">
                                        <input type="checkbox" class="custom-control-input" id="is_featured" wire:model="is_featured">
                                        <label class="custom-control-label" for="is_featured">Featured</label>
                                    </div>
                                    <div class="custom-control custom-checkbox d-inline-block">
                                        <input type="checkbox" class="custom-control-input" id="is_active" wire:model="is_active">
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" wire:click="$set('showBookModal', false)">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="saveBook">
                        <i class="icon-checkmark3 mr-2"></i> {{ $bookId ? 'Update Book' : 'Save Book' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div> 