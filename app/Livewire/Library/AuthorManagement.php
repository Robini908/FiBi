<?php

namespace App\Livewire\Library;

use App\Helpers\Qs;
use App\Models\BookAuthor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AuthorManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filter properties
    public $search = '';
    public $onlyFeatured = false;
    public $onlyActive = true;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Author form properties
    public $authorId = null;
    public $name;
    public $biography;
    public $birth_date;
    public $death_date;
    public $nationality;
    public $website;
    public $email;
    public $image_path;
    public $existing_image_path;
    public $newImage;
    public $is_featured = false;
    public $is_active = true;

    // UI State
    public $formModalOpen = false;
    public $deleteModalOpen = false;
    public $viewModalOpen = false;
    public $selectedAuthor = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'biography' => 'nullable|string',
        'birth_date' => 'nullable|date|before:today',
        'death_date' => 'nullable|date|after:birth_date',
        'nationality' => 'nullable|string|max:100',
        'website' => 'nullable|url|max:255',
        'email' => 'nullable|email|max:255',
        'newImage' => 'nullable|image|max:2048',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $listeners = [
        'refreshAuthors' => '$refresh',
        'confirmDelete' => 'confirmDelete'
    ];

    public function mount()
    {
        // Check permissions
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            abort(403, 'Unauthorized access to author management.');
        }
    }

    public function render()
    {
        $authors = BookAuthor::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nationality', 'like', '%' . $this->search . '%')
                      ->orWhere('biography', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->onlyFeatured, function ($query) {
                $query->where('is_featured', true);
            })
            ->when($this->onlyActive, function ($query) {
                $query->where('is_active', true);
            })
            ->withCount('books')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.library.author-management', [
            'authors' => $authors,
            'canCreate' => Qs::isLibrarian() || Qs::isAdministrator(),
            'canEdit' => Qs::isLibrarian() || Qs::isAdministrator(),
            'canDelete' => Qs::isAdministrator(),
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedOnlyFeatured()
    {
        $this->resetPage();
    }

    public function updatedOnlyActive()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to create authors.');
            return;
        }

        $this->resetForm();
        $this->formModalOpen = true;
    }

    public function openEditModal($authorId)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to edit authors.');
            return;
        }

        $author = BookAuthor::findOrFail($authorId);
        
        $this->authorId = $author->id;
        $this->name = $author->name;
        $this->biography = $author->biography;
        $this->birth_date = $author->birth_date ? $author->birth_date->format('Y-m-d') : null;
        $this->death_date = $author->death_date ? $author->death_date->format('Y-m-d') : null;
        $this->nationality = $author->nationality;
        $this->website = $author->website;
        $this->email = $author->email;
        $this->existing_image_path = $author->image_path;
        $this->is_featured = $author->is_featured;
        $this->is_active = $author->is_active;
        
        $this->formModalOpen = true;
    }

    public function openViewModal($authorId)
    {
        $this->selectedAuthor = BookAuthor::with(['books' => function ($query) {
            $query->where('is_active', true)->take(10);
        }])->findOrFail($authorId);
        
        $this->viewModalOpen = true;
    }

    public function save()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to save authors.');
            return;
        }

        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'biography' => $this->biography,
                'birth_date' => $this->birth_date,
                'death_date' => $this->death_date,
                'nationality' => $this->nationality,
                'website' => $this->website,
                'email' => $this->email,
                'is_featured' => $this->is_featured,
                'is_active' => $this->is_active,
            ];

            // Handle image upload
            if ($this->newImage) {
                // Delete old image if exists
                if ($this->existing_image_path) {
                    Storage::disk('public')->delete($this->existing_image_path);
                }
                
                $data['image_path'] = $this->newImage->store('authors', 'public');
            }

            if ($this->authorId) {
                $author = BookAuthor::findOrFail($this->authorId);
                $author->update($data);
                session()->flash('success', 'Author updated successfully.');
            } else {
                BookAuthor::create($data);
                session()->flash('success', 'Author created successfully.');
            }

            $this->resetForm();
            $this->formModalOpen = false;
            $this->dispatch('refreshAuthors');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving the author: ' . $e->getMessage());
        }
    }

    public function openDeleteModal($authorId)
    {
        if (!Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to delete authors.');
            return;
        }

        $this->authorId = $authorId;
        $this->deleteModalOpen = true;
    }

    public function confirmDelete()
    {
        if (!Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to delete authors.');
            return;
        }

        try {
            $author = BookAuthor::findOrFail($this->authorId);
            
            // Check if author has books
            if ($author->books()->count() > 0) {
                session()->flash('error', 'Cannot delete author with associated books. Please remove books first.');
                $this->deleteModalOpen = false;
                return;
            }

            // Delete image if exists
            if ($author->image_path) {
                Storage::disk('public')->delete($author->image_path);
            }

            $author->delete();
            session()->flash('success', 'Author deleted successfully.');
            
            $this->deleteModalOpen = false;
            $this->dispatch('refreshAuthors');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the author: ' . $e->getMessage());
        }
    }

    public function toggleFeatured($authorId)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to modify authors.');
            return;
        }

        $author = BookAuthor::findOrFail($authorId);
        $author->update(['is_featured' => !$author->is_featured]);
        
        session()->flash('success', 'Author featured status updated.');
    }

    public function toggleActive($authorId)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to modify authors.');
            return;
        }

        $author = BookAuthor::findOrFail($authorId);
        $author->update(['is_active' => !$author->is_active]);
        
        session()->flash('success', 'Author status updated.');
    }

    private function resetForm()
    {
        $this->authorId = null;
        $this->name = '';
        $this->biography = '';
        $this->birth_date = null;
        $this->death_date = null;
        $this->nationality = '';
        $this->website = '';
        $this->email = '';
        $this->existing_image_path = null;
        $this->newImage = null;
        $this->is_featured = false;
        $this->is_active = true;
        
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->formModalOpen = false;
        $this->deleteModalOpen = false;
        $this->viewModalOpen = false;
        $this->resetForm();
    }
}
