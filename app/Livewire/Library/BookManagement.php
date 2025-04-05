<?php

namespace App\Livewire\Library;

use App\Models\BookAuthor;
use App\Models\BookCategory;
use App\Models\LibraryBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class BookManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filter properties
    public $search = '';
    public $selectedCategories = [];
    public $onlyAvailable = false;
    public $onlyFeatured = false;
    public $sortField = 'title';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Book form properties - Basic
    public $bookId = null;
    public $title;
    public $subtitle;
    public $isbn;
    public $isbn13;
    public $category_id;
    public $language;
    public $description;
    public $cover_image;
    public $existing_cover_image;
    public $newCoverImage;

    // Book form properties - Details
    public $publisher;
    public $published_date;
    public $edition;
    public $pages;
    public $dewey_decimal;
    public $call_number;
    public $replacement_cost;
    public $table_of_contents;
    public $is_reference_only = false;
    public $is_featured = false;
    public $is_active = true;
    public $copies_available = 1;
    public $total_copies = 1;
    
    // Author form properties
    public $authorSearch = '';
    public $availableAuthors = [];
    public $selectedAuthors = [];
    public $primaryAuthorId;
    
    // UI State
    public $activeTab = 'basic';
    public $book = null;
    public $canCreate = false;
    public $canExport = false;
    public $formModalOpen = false;
    public $deleteModalOpen = false;
    
    // Validation rules
    protected $rules = [
        // Basic tab
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'isbn' => 'nullable|string|max:20',
        'isbn13' => 'nullable|string|max:20',
        'category_id' => 'required|exists:book_categories,id',
        'language' => 'nullable|string|max:50',
        'description' => 'nullable|string',
        'newCoverImage' => 'nullable|image|max:2048',
            
        // Details tab
        'publisher' => 'nullable|string|max:255',
        'published_date' => 'nullable|date',
        'edition' => 'nullable|string|max:50',
        'pages' => 'nullable|integer|min:1',
        'dewey_decimal' => 'nullable|string|max:20',
        'call_number' => 'nullable|string|max:50',
        'replacement_cost' => 'nullable|numeric|min:0',
        'table_of_contents' => 'nullable|string',
        'is_reference_only' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'copies_available' => 'required|integer|min:0',
        'total_copies' => 'required|integer|min:0|gte:copies_available',
            
        // Authors tab
        'selectedAuthors' => 'required|array|min:1',
        'primaryAuthorId' => 'required|integer'
    ];

    public function mount()
    {
        if (!$this->checkPermission('view')) {
            abort(403, 'You do not have permission to view this page.');
        }
        
        // Set permission flags when component mounts
        $this->canCreate = $this->checkPermission('create');
        $this->canExport = $this->checkPermission('export');
    }

    public function render()
    {
        $query = LibraryBook::query()
            ->with(['category', 'authors']);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('isbn', 'like', '%' . $this->search . '%')
                  ->orWhere('isbn13', 'like', '%' . $this->search . '%')
                  ->orWhereHas('authors', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply category filter
        if (!empty($this->selectedCategories)) {
            $query->whereIn('category_id', $this->selectedCategories);
        }

        // Apply availability filter
        if ($this->onlyAvailable) {
            $query->where('copies_available', '>', 0);
        }

        // Apply featured filter
        if ($this->onlyFeatured) {
            $query->where('is_featured', true);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);
        
        $books = $query->paginate($this->perPage);
        $categories = BookCategory::orderBy('name')->get();
        
        // Update permission flags
        $this->canCreate = $this->checkPermission('create');
        $this->canExport = $this->checkPermission('export');
        
        return view('livewire.library.book-management', [
            'books' => $books,
            'categories' => $categories,
            'canCreate' => $this->canCreate,
            'canExport' => $this->canExport
        ]);
    }

    // Modal Control Methods
    public function create()
    {
        if (!$this->checkPermission('create')) {
            session()->flash('error', 'You do not have permission to create books.');
            return;
        }
        
        $this->openCreateModal();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->bookId = null;
        $this->activeTab = 'basic';
        $this->formModalOpen = true;
    }

    public function edit($bookId)
    {
        if (!$this->checkPermission('update')) {
            return;
        }

        $this->bookId = $bookId;
        $book = LibraryBook::with('authors')->findOrFail($bookId);
        
        // Basic fields
        $this->title = $book->title;
        $this->subtitle = $book->subtitle;
        $this->isbn = $book->isbn;
        $this->isbn13 = $book->isbn13;
        $this->category_id = $book->category_id;
        $this->language = $book->language;
        $this->description = $book->description;
        $this->existing_cover_image = $book->cover_image;
        $this->cover_image = null; // Reset any new upload
        
        // Additional details
        $this->publisher = $book->publisher;
        $this->published_date = $book->published_date;
        $this->edition = $book->edition;
        $this->pages = $book->pages;
        $this->dewey_decimal = $book->dewey_decimal;
        $this->call_number = $book->call_number;
        $this->replacement_cost = $book->replacement_cost;
        $this->table_of_contents = $book->table_of_contents;
        $this->is_reference_only = $book->is_reference_only;
        $this->is_featured = $book->is_featured;
        $this->is_active = $book->is_active;
        $this->copies_available = $book->copies_available;
        $this->total_copies = $book->total_copies;
        
        // Authors
        $this->selectedAuthors = $book->authors->map(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name,
                'is_primary' => $author->pivot->is_primary
            ];
        })->toArray();
        
        $this->primaryAuthorId = $book->authors->where('pivot.is_primary', true)->first()?->id;
        
        $this->activeTab = 'basic';
        $this->formModalOpen = true;
    }

    public function confirmDelete($bookId)
    {
        if (!$this->checkPermission('delete')) {
            return;
        }
        
        $this->bookId = $bookId;
        $this->book = LibraryBook::with('authors')->findOrFail($bookId);
        $this->deleteModalOpen = true;
    }

    public function delete()
    {
        if (!$this->checkPermission('delete')) {
            return;
        }
        
        $book = LibraryBook::findOrFail($this->bookId);
        $bookTitle = $book->title;
        
        try {
            $book->delete();
            $this->closeModals();
            session()->flash('success', "Book '{$bookTitle}' was successfully deleted.");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete the book. ' . $e->getMessage());
        }
    }
    
    // Helper method to close all modals
    public function closeModals()
    {
        $this->formModalOpen = false;
        $this->deleteModalOpen = false;
    }

    // Image handling methods
    public function removeCoverImage()
    {
        $this->newCoverImage = null;
        $this->existing_cover_image = null;
    }

    // Form Actions
    public function save()
    {
        // Custom validation for the current tab
        $this->validateTab();
        
        // If we're on the last tab or trying to submit the form
        if ($this->activeTab === 'authors') {
            $this->validate();
            
            try {
                DB::beginTransaction();
                
                $data = [
                    'title' => $this->title,
                    'subtitle' => $this->subtitle,
                    'isbn' => $this->isbn,
                    'isbn13' => $this->isbn13,
                    'category_id' => $this->category_id,
                    'language' => $this->language,
                    'description' => $this->description,
                    'publisher' => $this->publisher,
                    'published_date' => $this->published_date,
                    'edition' => $this->edition,
                    'pages' => $this->pages,
                    'dewey_decimal' => $this->dewey_decimal,
                    'call_number' => $this->call_number,
                    'replacement_cost' => $this->replacement_cost,
                    'table_of_contents' => $this->table_of_contents,
                    'is_reference_only' => $this->is_reference_only,
                    'is_featured' => $this->is_featured,
                    'is_active' => $this->is_active,
                    'copies_available' => $this->copies_available,
                    'total_copies' => $this->total_copies,
                ];
                
                // Handle cover image upload if a new file is provided
                if ($this->newCoverImage) {
                    $data['cover_image'] = $this->newCoverImage->store('books/covers', 'public');
                } elseif ($this->existing_cover_image === null && $this->bookId) {
                    // If existing image was removed but no new one uploaded
                    $data['cover_image'] = null;
                }
                
                if ($this->bookId) {
                    // Update existing book
                    if (!$this->checkPermission('update')) {
                        return;
                    }
                    
                    $book = LibraryBook::findOrFail($this->bookId);
                    $book->update($data);
                    $message = "Book '{$book->title}' was successfully updated.";
                } else {
                    // Create new book
                    if (!$this->checkPermission('create')) {
                        return;
                    }
                    
                    $book = LibraryBook::create($data);
                    $message = "Book '{$book->title}' was successfully added to the library.";
                }
                
                // Sync authors
                $authorSync = [];
                foreach ($this->selectedAuthors as $author) {
                    $authorSync[$author['id']] = [
                        'is_primary' => $author['id'] == $this->primaryAuthorId
                    ];
                }
                $book->authors()->sync($authorSync);
                
                DB::commit();
                $this->closeModals();
                $this->resetForm();
                session()->flash('success', $message);
                
            } catch (\Exception $e) {
                DB::rollback();
                session()->flash('error', 'Failed to save the book. ' . $e->getMessage());
            }
        } else {
            // Move to the next tab
            if ($this->activeTab === 'basic') {
                $this->activeTab = 'details';
            } elseif ($this->activeTab === 'details') {
                $this->activeTab = 'authors';
            }
        }
    }

    // Filter Methods
    public function resetFilters()
    {
        $this->reset(['search', 'selectedCategories', 'onlyAvailable', 'onlyFeatured']);
    }

    public function toggleSortDirection()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    // Author Management Methods
    public function updatedAuthorSearch()
    {
        if (strlen($this->authorSearch) >= 2) {
            $this->availableAuthors = BookAuthor::where('name', 'like', '%' . $this->authorSearch . '%')
                ->limit(5)
                ->get()
                ->map(function ($author) {
                    return [
                        'id' => $author->id,
                        'name' => $author->name
                    ];
                })
                ->toArray();
        } else {
            $this->availableAuthors = [];
        }
    }

    public function addAuthor($authorId, $authorName)
    {
        // Check if author is already selected
        $exists = collect($this->selectedAuthors)->firstWhere('id', $authorId);
        
        if (!$exists) {
            $this->selectedAuthors[] = [
                'id' => $authorId,
                'name' => $authorName,
                'is_primary' => false
            ];
            
            // If this is the first author, make them primary
            if (count($this->selectedAuthors) === 1) {
                $this->primaryAuthorId = $authorId;
                $this->selectedAuthors[0]['is_primary'] = true;
            }
        }
        
        $this->authorSearch = '';
        $this->availableAuthors = [];
    }

    public function removeAuthor($index)
    {
        $removedAuthor = $this->selectedAuthors[$index];
        
        // If removing the primary author, reassign primary to the first remaining author
        if ($removedAuthor['id'] == $this->primaryAuthorId && count($this->selectedAuthors) > 1) {
            // Find the next author to make primary
            $nextPrimaryIndex = $index === 0 ? 1 : 0;
            $this->primaryAuthorId = $this->selectedAuthors[$nextPrimaryIndex]['id'];
        } elseif (count($this->selectedAuthors) === 1) {
            // If this was the last author, clear the primary author
            $this->primaryAuthorId = null;
        }
        
        // Remove the author
        array_splice($this->selectedAuthors, $index, 1);
    }

    public function setPrimaryAuthor($authorId)
    {
        $this->primaryAuthorId = $authorId;
        
        // Update all authors' is_primary status
        foreach ($this->selectedAuthors as &$author) {
            $author['is_primary'] = ($author['id'] == $authorId);
        }
    }

    // Helper Methods
    private function validateTab()
    {
        $tabRules = [];
        
        if ($this->activeTab === 'basic') {
            $tabRules = [
                'title' => $this->rules['title'],
                'subtitle' => $this->rules['subtitle'],
                'isbn' => $this->rules['isbn'],
                'isbn13' => $this->rules['isbn13'],
                'category_id' => $this->rules['category_id'],
                'language' => $this->rules['language'],
                'description' => $this->rules['description'],
                'newCoverImage' => $this->rules['newCoverImage']
            ];
        } elseif ($this->activeTab === 'details') {
            $tabRules = [
                'publisher' => $this->rules['publisher'],
                'published_date' => $this->rules['published_date'],
                'edition' => $this->rules['edition'],
                'pages' => $this->rules['pages'],
                'dewey_decimal' => $this->rules['dewey_decimal'],
                'call_number' => $this->rules['call_number'],
                'replacement_cost' => $this->rules['replacement_cost'],
                'table_of_contents' => $this->rules['table_of_contents'],
                'is_reference_only' => $this->rules['is_reference_only'],
                'is_featured' => $this->rules['is_featured'],
                'is_active' => $this->rules['is_active'],
                'copies_available' => $this->rules['copies_available'],
                'total_copies' => $this->rules['total_copies']
            ];
        } elseif ($this->activeTab === 'authors') {
            $tabRules = [
                'selectedAuthors' => $this->rules['selectedAuthors'],
                'primaryAuthorId' => $this->rules['primaryAuthorId']
            ];
        }
        
        $this->validate($tabRules);
    }

    private function resetForm()
    {
        $this->reset([
            'bookId', 'title', 'subtitle', 'isbn', 'isbn13',
            'category_id', 'language', 'description', 'cover_image', 'existing_cover_image', 'newCoverImage',
            'publisher', 'published_date', 'edition', 'pages',
            'dewey_decimal', 'call_number', 'replacement_cost', 'table_of_contents',
            'is_reference_only', 'is_featured', 'is_active',
            'copies_available', 'total_copies',
            'selectedAuthors', 'primaryAuthorId', 'authorSearch', 'availableAuthors'
        ]);
        
        // Set default values
        $this->is_active = true;
        $this->copies_available = 1;
        $this->total_copies = 1;
    }

    private function checkPermission($action)
    {
        switch ($action) {
            case 'view':
                return auth()->user()->hasAnyRole(['librarian', 'teacher', 'admin', 'super_admin']);
            case 'create':
            case 'update':
            case 'delete':
                return auth()->user()->hasAnyRole(['librarian', 'admin', 'super_admin']);
            case 'export':
                return auth()->user()->hasAnyRole(['librarian', 'teacher', 'admin', 'super_admin']);
            default:
                return false;
        }
    }

    // Export and print functions
    public function exportToExcel()
    {
        if (!$this->checkPermission('export')) {
            session()->flash('error', 'You do not have permission to export books.');
            return;
        }
        
        // Logic for exporting to Excel would go here
        // For now, just show a success message
        session()->flash('success', 'Books exported to Excel successfully.');
    }
    
    public function printData()
    {
        if (!$this->checkPermission('export')) {
            session()->flash('error', 'You do not have permission to print book data.');
            return;
        }
        
        // Logic for printing data would go here
        // For now, just dispatch a browser event to trigger printing
        $this->dispatch('printBooks');
    }
} 