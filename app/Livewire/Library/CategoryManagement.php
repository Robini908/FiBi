<?php

namespace App\Livewire\Library;

use App\Helpers\Qs;
use App\Models\BookCategory;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManagement extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $onlyActive = true;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 15;

    // Category form properties
    public $categoryId = null;
    public $name;
    public $description;
    public $parent_id;
    public $color_code = '#10B981';
    public $display_order = 0;
    public $is_active = true;

    // UI State
    public $formModalOpen = false;
    public $deleteModalOpen = false;
    public $viewModalOpen = false;
    public $selectedCategory = null;
    public $showAsTree = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'parent_id' => 'nullable|exists:book_categories,id',
        'color_code' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        'display_order' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    protected $listeners = [
        'refreshCategories' => '$refresh',
        'confirmDelete' => 'confirmDelete'
    ];

    public function mount()
    {
        // Check permissions
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            abort(403, 'Unauthorized access to category management.');
        }
    }

    public function render()
    {
        $query = BookCategory::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->onlyActive, function ($query) {
                $query->where('is_active', true);
            })
            ->withCount('books');

        if ($this->showAsTree) {
            $categories = $query->whereNull('parent_id')
                ->with(['children' => function ($q) {
                    $q->withCount('books')->orderBy('display_order')->orderBy('name');
                }])
                ->orderBy($this->sortField, $this->sortDirection)
                ->get();
        } else {
            $categories = $query->with('parent')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        }

        $parentCategories = BookCategory::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('livewire.library.category-management', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
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

    public function updatedOnlyActive()
    {
        $this->resetPage();
    }

    public function toggleView()
    {
        $this->showAsTree = !$this->showAsTree;
        $this->resetPage();
    }

    public function openCreateModal($parentId = null)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to create categories.');
            return;
        }

        $this->resetForm();
        $this->parent_id = $parentId;
        $this->formModalOpen = true;
    }

    public function openEditModal($categoryId)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to edit categories.');
            return;
        }

        $category = BookCategory::findOrFail($categoryId);
        
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->color_code = $category->color_code ?? '#10B981';
        $this->display_order = $category->display_order;
        $this->is_active = $category->is_active;
        
        $this->formModalOpen = true;
    }

    public function openViewModal($categoryId)
    {
        $this->selectedCategory = BookCategory::with(['parent', 'children', 'books' => function ($query) {
            $query->where('is_active', true)->take(10);
        }])->findOrFail($categoryId);
        
        $this->viewModalOpen = true;
    }

    public function save()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to save categories.');
            return;
        }

        // Validate parent_id doesn't create circular reference
        if ($this->parent_id && $this->categoryId) {
            $this->validate([
                'parent_id' => [
                    'nullable',
                    'exists:book_categories,id',
                    function ($attribute, $value, $fail) {
                        if ($value == $this->categoryId) {
                            $fail('A category cannot be its own parent.');
                        }
                        
                        // Check for circular reference
                        $category = BookCategory::find($value);
                        while ($category && $category->parent_id) {
                            if ($category->parent_id == $this->categoryId) {
                                $fail('This would create a circular reference.');
                                break;
                            }
                            $category = $category->parent;
                        }
                    }
                ]
            ]);
        } else {
            $this->validate();
        }

        try {
            $data = [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'parent_id' => $this->parent_id,
                'color_code' => $this->color_code,
                'display_order' => $this->display_order,
                'is_active' => $this->is_active,
            ];

            if ($this->categoryId) {
                $category = BookCategory::findOrFail($this->categoryId);
                $category->update($data);
                session()->flash('success', 'Category updated successfully.');
            } else {
                BookCategory::create($data);
                session()->flash('success', 'Category created successfully.');
            }

            $this->resetForm();
            $this->formModalOpen = false;
            $this->dispatch('refreshCategories');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving the category: ' . $e->getMessage());
        }
    }

    public function openDeleteModal($categoryId)
    {
        if (!Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to delete categories.');
            return;
        }

        $this->categoryId = $categoryId;
        $this->deleteModalOpen = true;
    }

    public function confirmDelete()
    {
        if (!Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to delete categories.');
            return;
        }

        try {
            $category = BookCategory::findOrFail($this->categoryId);
            
            // Check if category has books
            if ($category->books()->count() > 0) {
                session()->flash('error', 'Cannot delete category with associated books. Please move books to another category first.');
                $this->deleteModalOpen = false;
                return;
            }

            // Check if category has children
            if ($category->children()->count() > 0) {
                session()->flash('error', 'Cannot delete category with subcategories. Please delete or move subcategories first.');
                $this->deleteModalOpen = false;
                return;
            }

            $category->delete();
            session()->flash('success', 'Category deleted successfully.');
            
            $this->deleteModalOpen = false;
            $this->dispatch('refreshCategories');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the category: ' . $e->getMessage());
        }
    }

    public function toggleActive($categoryId)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            session()->flash('error', 'You do not have permission to modify categories.');
            return;
        }

        $category = BookCategory::findOrFail($categoryId);
        $category->update(['is_active' => !$category->is_active]);
        
        session()->flash('success', 'Category status updated.');
    }

    private function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->description = '';
        $this->parent_id = null;
        $this->color_code = '#10B981';
        $this->display_order = 0;
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
