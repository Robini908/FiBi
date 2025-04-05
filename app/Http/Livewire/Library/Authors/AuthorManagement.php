<?php

namespace App\Http\Livewire\Library\Authors;

use App\Helpers\Qs;
use App\Models\BookAuthor;
use App\Models\LibraryBook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use WireElements\Modal\Modal;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

class AuthorManagement extends Component
{
    use WithPagination, WithFileUploads;

    // UI State
    public $formModalOpen = false;
    public $deleteModalOpen = false;
    public $profileModalOpen = false;
    public $canCreate = false;
    public $canExport = false;
    public $showFilters = false;
    public $perPage = 10;

    // Form Properties
    public $authorId = null;
    public $name = '';
    public $biography = '';
    public $birth_date = null;
    public $death_date = null;
    public $nationality = '';
    public $website = '';
    public $email = '';
    public $is_featured = false;
    public $is_active = true;
    public $existing_image_path = null;
    public $newAuthorImage = null;

    // Selected author for deletion or profile viewing
    public $author = null;

    // Filtering and Sorting
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $showOnlyActive = false;
    public $showOnlyFeatured = false;
    public $showOnlyWithBooks = false;
    public $selectedNationalities = [];

    // Available nationalities for filtering
    public $nationalities = [];

    // Author's books for profile modal
    public $authorBooks = [];
    public $totalBooks = 0;
    public $primaryBooks = 0;

    protected $listeners = [
        'refreshAuthors' => '$refresh',
        'authorCreated' => '$refresh',
        'authorUpdated' => '$refresh',
        'authorDeleted' => '$refresh'
    ];

    // Validation Rules
    protected $rules = [
        'name' => 'required|string|max:100',
        'biography' => 'nullable|string',
        'birth_date' => 'nullable|date',
        'death_date' => 'nullable|date|after_or_equal:birth_date',
        'nationality' => 'nullable|string|max:50',
        'website' => 'nullable|url|max:100',
        'email' => 'nullable|email|max:100',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'newAuthorImage' => 'nullable|image|max:1024'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedNationalities' => ['except' => []],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
        'showOnlyActive' => ['except' => false],
        'showOnlyFeatured' => ['except' => false],
        'showOnlyWithBooks' => ['except' => false],
    ];

    public function mount()
    {
        $this->checkPermissions();
        $this->loadNationalities();
    }

    public function checkPermissions()
    {
        $this->canCreate = Qs::isLibrarian() || Qs::isAdministrator();
        $this->canExport = Qs::isLibrarian() || Qs::isAdministrator() || Qs::isAccountant();
    }

    public function loadNationalities()
    {
        // Get distinct nationalities from authors
        $this->nationalities = BookAuthor::whereNotNull('nationality')
            ->distinct()
            ->pluck('nationality')
            ->toArray();
    }

    public function render()
    {
        $authorsQuery = BookAuthor::query()
            ->when($this->search, function (Builder $query) {
                return $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nationality', 'like', '%' . $this->search . '%')
                    ->orWhere('biography', 'like', '%' . $this->search . '%');
            })
            ->when($this->showOnlyFeatured, fn ($query) => $query->featured())
            ->when($this->showOnlyActive, fn ($query) => $query->active())
            ->when(!empty($this->selectedNationalities), function ($query) {
                return $query->whereIn('nationality', $this->selectedNationalities);
            })
            ->when($this->showOnlyWithBooks, function ($query) {
                return $query->whereHas('books');
            });

        // Apply sorting
        $authors = $authorsQuery->orderBy($this->sortField, $this->sortDirection)
            ->withCount('books')
            ->paginate($this->perPage);

        return view('livewire.library.authors.author-management', [
            'authors' => $authors,
            'nationalities' => $this->getAllNationalities(),
            'canCreate' => $this->canCreate,
            'canExport' => $this->canExport,
            'activeFilters' => $this->getActiveFiltersCount()
        ]);
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

    public function toggleSortDirection()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function resetFilters()
    {
        $this->reset(['search', 'showOnlyActive', 'showOnlyFeatured', 'showOnlyWithBooks', 'selectedNationalities']);
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->formModalOpen = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->authorId = $id;
        $author = BookAuthor::findOrFail($id);
        
        $this->name = $author->name;
        $this->biography = $author->biography;
        $this->birth_date = optional($author->birth_date)->format('Y-m-d');
        $this->death_date = optional($author->death_date)->format('Y-m-d');
        $this->nationality = $author->nationality;
        $this->website = $author->website;
        $this->email = $author->email;
        $this->is_featured = $author->is_featured;
        $this->is_active = $author->is_active;
        $this->existing_image_path = $author->image_path;
        
        $this->formModalOpen = true;
    }

    public function confirmDelete($id)
    {
        if (!$this->checkPermission('delete')) {
            return;
        }
        
        $this->authorId = $id;
        $this->author = BookAuthor::with('books')->find($id);
        $this->deleteModalOpen = true;
    }

    public function delete()
    {
        if (!$this->checkPermission('delete')) {
            return;
        }
        
        $author = BookAuthor::findOrFail($this->authorId);
        
        // Delete image if exists
        if ($author->image_path && Storage::exists($author->image_path)) {
            Storage::delete($author->image_path);
        }
        
        $author->delete();
        
        $this->deleteModalOpen = false;
        
        session()->flash('success', 'Author successfully deleted.');
    }

    public function viewProfile($id)
    {
        $this->authorId = $id;
        $this->author = BookAuthor::findOrFail($id);
        
        // Load author's books
        $books = $this->author->books()->withPivot('is_primary')->get();
        $this->authorBooks = $books->take(5);
        $this->totalBooks = $books->count();
        $this->primaryBooks = $books->where('pivot.is_primary', true)->count();
        
        $this->profileModalOpen = true;
    }

    public function save()
    {
        if ($this->authorId && !$this->checkPermission('edit')) {
            return;
        }
        
        if (!$this->authorId && !$this->checkPermission('create')) {
            return;
        }
        
        $this->validate();
        
        $isNewAuthor = !$this->authorId;
        
        // Get or create the author
        $author = $isNewAuthor
            ? new BookAuthor()
            : BookAuthor::findOrFail($this->authorId);
        
        // Set the author properties
        $author->name = $this->name;
        $author->slug = Str::slug($this->name);
        $author->biography = $this->biography;
        $author->birth_date = $this->birth_date;
        $author->death_date = $this->death_date;
        $author->nationality = $this->nationality;
        $author->website = $this->website;
        $author->email = $this->email;
        $author->is_featured = $this->is_featured;
        $author->is_active = $this->is_active;
        
        // Handle image upload
        if ($this->newAuthorImage) {
            // Delete old image if exists
            if ($author->image_path && Storage::exists($author->image_path)) {
                Storage::delete($author->image_path);
            }
            
            // Store new image
            $imagePath = $this->newAuthorImage->store('authors', 'public');
            $author->image_path = $imagePath;
        }
        
        $author->save();
        
        $this->formModalOpen = false;
        
        session()->flash('success', $isNewAuthor
            ? 'Author created successfully.'
            : 'Author updated successfully.');
        
        $this->resetForm();
    }

    public function closeModals()
    {
        $this->formModalOpen = false;
        $this->deleteModalOpen = false;
        $this->profileModalOpen = false;
    }

    public function removeAuthorImage()
    {
        $this->newAuthorImage = null;
        $this->existing_image_path = null;
    }

    public function exportToExcel()
    {
        if (!$this->checkPermission('export')) {
            return;
        }
        
        $this->dispatchBrowserEvent('export-authors', [
            'url' => route('library.export.authors', [
                'search' => $this->search,
                'featuredOnly' => $this->showOnlyFeatured,
                'activeOnly' => $this->showOnlyActive,
                'withBooksOnly' => $this->showOnlyWithBooks,
                'nationalities' => $this->selectedNationalities,
                'sortField' => $this->sortField,
                'sortDirection' => $this->sortDirection
            ])
        ]);
    }

    public function printData()
    {
        $this->dispatchBrowserEvent('print-authors');
    }

    public function generateAuthorProfile($id)
    {
        $this->dispatchBrowserEvent('generate-author-profile', [
            'url' => route('library.authors.profile', ['id' => $id])
        ]);
    }

    private function checkPermission($action)
    {
        switch ($action) {
            case 'create':
            case 'edit':
            case 'delete':
                return Qs::isLibrarian() || Qs::isAdministrator();
            case 'export':
                return $this->canExport;
            default:
                return false;
        }
    }

    private function resetForm()
    {
        $this->reset([
            'authorId', 'name', 'biography', 'birth_date', 'death_date',
            'nationality', 'website', 'email', 'is_featured', 'is_active',
            'existing_image_path', 'newAuthorImage'
        ]);
        
        $this->resetErrorBag();
    }

    public function updatingSelectedNationalities()
    {
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function createAuthor()
    {
        if ($this->canCreate()) {
            $this->dispatch('modal.open', 'library.authors.author-form');
        } else {
            toast()->danger('You do not have permission to create authors')
                ->duration(3000)
                ->push();
        }
    }

    public function confirmAuthorDelete($authorId)
    {
        if ($this->canDelete()) {
            $this->dispatch('modal.open', 'library.authors.author-delete', ['authorId' => $authorId]);
        } else {
            toast()->danger('You do not have permission to delete authors')
                ->duration(3000)
                ->push();
        }
    }

    public function viewAuthor($authorId)
    {
        if ($this->canView()) {
            $this->dispatch('modal.open', 'library.authors.author-view', ['authorId' => $authorId]);
        } else {
            toast()->danger('You do not have permission to view author details')
                ->duration(3000)
                ->push();
        }
    }

    public function editAuthor($authorId)
    {
        if ($this->canUpdate()) {
            $this->dispatch('modal.open', 'library.authors.author-form', ['authorId' => $authorId]);
        } else {
            toast()->danger('You do not have permission to edit authors')
                ->duration(3000)
                ->push();
        }
    }

    public function exportAuthors()
    {
        if ($this->canExport()) {
            return redirect()->route('library.authors.export');
        } else {
            toast()->danger('You do not have permission to export authors')
                ->duration(3000)
                ->push();
        }
    }

    public function canCreate()
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['librarian', 'admin', 'superadmin']);
    }

    public function canUpdate()
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['librarian', 'admin', 'superadmin']);
    }

    public function canDelete()
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['librarian', 'admin', 'superadmin']);
    }

    public function canView()
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['librarian', 'admin', 'superadmin', 'teacher', 'student', 'parent']);
    }

    public function canExport()
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['librarian', 'admin', 'superadmin']);
    }

    public function getAllNationalities()
    {
        return BookAuthor::distinct('nationality')
            ->whereNotNull('nationality')
            ->where('nationality', '!=', '')
            ->orderBy('nationality')
            ->pluck('nationality');
    }

    public function getAuthorsProperty()
    {
        $query = BookAuthor::query()
            ->withCount('books');
            
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('biography', 'like', "%{$this->search}%");
            });
        }
        
        // Use only selectedNationalities for filtering
        if (!empty($this->selectedNationalities)) {
            $query->whereIn('nationality', $this->selectedNationalities);
        }
        
        if ($this->showOnlyActive) {
            $query->where('is_active', true);
        }
        
        if ($this->showOnlyFeatured) {
            $query->where('is_featured', true);
        }
        
        if ($this->showOnlyWithBooks) {
            $query->has('books');
        }
        
        return $query->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage);
    }

    private function getActiveFiltersCount()
    {
        $count = 0;
        
        if ($this->search) $count++;
        if (!empty($this->selectedNationalities)) $count++;
        if ($this->showOnlyActive) $count++;
        if ($this->showOnlyFeatured) $count++;
        if ($this->showOnlyWithBooks) $count++;
        if ($this->sortField !== 'name' || $this->sortDirection !== 'asc') $count++;
        
        return $count;
    }

    public function toggleAuthorStatus($authorId)
    {
        if (!$this->canUpdate()) {
            toast()->danger('You do not have permission to update authors')
                ->duration(3000)
                ->push();
            return;
        }
        
        $author = BookAuthor::findOrFail($authorId);
        $author->is_active = !$author->is_active;
        $author->updated_by = Auth::id();
        $author->save();
        
        $status = $author->is_active ? 'activated' : 'deactivated';
        
        toast()->success("Author '{$author->name}' has been {$status}")
            ->duration(3000)
            ->push();
    }

    public function toggleAuthorFeatured($authorId)
    {
        if (!$this->canUpdate()) {
            toast()->danger('You do not have permission to update authors')
                ->duration(3000)
                ->push();
            return;
        }
        
        $author = BookAuthor::findOrFail($authorId);
        $author->is_featured = !$author->is_featured;
        $author->updated_by = Auth::id();
        $author->save();
        
        $status = $author->is_featured ? 'featured' : 'unfeatured';
        
        toast()->success("Author '{$author->name}' has been {$status}")
            ->duration(3000)
            ->push();
    }
} 