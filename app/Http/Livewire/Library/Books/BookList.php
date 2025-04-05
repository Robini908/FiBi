<?php

namespace App\Http\Livewire\Library\Books;

use App\Helpers\Qs;
use App\Models\BookCategory;
use App\Models\LibraryBook;
use Livewire\Component;
use Livewire\WithPagination;

class BookList extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $sortField = 'title';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $categories = [];
    
    // For the create/edit form modal
    public $showBookModal = false;
    public $bookId = null;
    public $title;
    public $subtitle;
    public $isbn;
    public $isbn13;
    public $description;
    public $category_id;
    public $publisher;
    public $publication_date;
    public $edition;
    public $pages;
    public $language;
    public $dewey_decimal;
    public $call_number;
    public $is_reference_only = false;
    public $is_featured = false;
    public $is_active = true;
    public $replacement_cost;
    public $cover_image;

    protected $listeners = ['refreshBooks' => '$refresh'];

    public function mount()
    {
        $this->categories = BookCategory::active()->ordered()->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    public function render()
    {
        // Role-based check for edit/delete permissions
        $canManageBooks = Qs::isLibrarian() || Qs::isAdministrator();
        
        $books = LibraryBook::query()
            ->when($this->search, function ($query) {
                return $query->search($this->search);
            })
            ->when($this->categoryFilter, function ($query) {
                return $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->statusFilter === 'active', function ($query) {
                return $query->where('is_active', true);
            })
            ->when($this->statusFilter === 'inactive', function ($query) {
                return $query->where('is_active', false);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->with(['category', 'authors', 'copies'])
            ->paginate($this->perPage);
        
        return view('livewire.library.books.book-list', [
            'books' => $books,
            'canManageBooks' => $canManageBooks,
        ]);
    }

    public function createBook()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to create books']);
            return;
        }
        
        $this->resetForm();
        $this->showBookModal = true;
    }

    public function editBook($id)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to edit books']);
            return;
        }
        
        $this->bookId = $id;
        $book = LibraryBook::findOrFail($id);
        
        $this->title = $book->title;
        $this->subtitle = $book->subtitle;
        $this->isbn = $book->isbn;
        $this->isbn13 = $book->isbn13;
        $this->description = $book->description;
        $this->category_id = $book->category_id;
        $this->publisher = $book->publisher;
        $this->publication_date = $book->publication_date ? $book->publication_date->format('Y-m-d') : null;
        $this->edition = $book->edition;
        $this->pages = $book->pages;
        $this->language = $book->language;
        $this->dewey_decimal = $book->dewey_decimal;
        $this->call_number = $book->call_number;
        $this->is_reference_only = $book->is_reference_only;
        $this->is_featured = $book->is_featured;
        $this->is_active = $book->is_active;
        $this->replacement_cost = $book->replacement_cost;
        
        $this->showBookModal = true;
    }

    public function saveBook()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to save books']);
            return;
        }
        
        $this->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'isbn13' => 'nullable|string|max:20',
            'category_id' => 'nullable|exists:book_categories,id',
            'publication_date' => 'nullable|date',
            'pages' => 'nullable|integer',
            'replacement_cost' => 'nullable|numeric',
        ]);
        
        if ($this->bookId) {
            // Update existing book
            $book = LibraryBook::findOrFail($this->bookId);
            $message = 'Book updated successfully';
        } else {
            // Create new book
            $book = new LibraryBook();
            $message = 'Book created successfully';
        }
        
        $book->title = $this->title;
        $book->subtitle = $this->subtitle;
        $book->isbn = $this->isbn;
        $book->isbn13 = $this->isbn13;
        $book->description = $this->description;
        $book->category_id = $this->category_id;
        $book->publisher = $this->publisher;
        $book->publication_date = $this->publication_date;
        $book->edition = $this->edition;
        $book->pages = $this->pages;
        $book->language = $this->language;
        $book->dewey_decimal = $this->dewey_decimal;
        $book->call_number = $this->call_number;
        $book->is_reference_only = $this->is_reference_only;
        $book->is_featured = $this->is_featured;
        $book->is_active = $this->is_active;
        $book->replacement_cost = $this->replacement_cost;
        
        $book->save();
        
        $this->resetForm();
        $this->dispatchBrowserEvent('toast-success', ['message' => $message]);
        $this->emit('refreshBooks');
    }

    public function confirmDelete($id)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to delete books']);
            return;
        }
        
        $this->bookId = $id;
        $this->dispatchBrowserEvent('show-delete-confirmation');
    }

    public function deleteBook()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to delete books']);
            return;
        }
        
        $book = LibraryBook::findOrFail($this->bookId);
        $book->delete();
        
        $this->dispatchBrowserEvent('toast-success', ['message' => 'Book deleted successfully']);
        $this->emit('refreshBooks');
    }

    private function resetForm()
    {
        $this->bookId = null;
        $this->title = '';
        $this->subtitle = '';
        $this->isbn = '';
        $this->isbn13 = '';
        $this->description = '';
        $this->category_id = '';
        $this->publisher = '';
        $this->publication_date = null;
        $this->edition = '';
        $this->pages = null;
        $this->language = '';
        $this->dewey_decimal = '';
        $this->call_number = '';
        $this->is_reference_only = false;
        $this->is_featured = false;
        $this->is_active = true;
        $this->replacement_cost = null;
        $this->cover_image = null;
        $this->showBookModal = false;
    }
} 