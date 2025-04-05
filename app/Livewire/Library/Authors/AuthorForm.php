<?php

namespace App\Livewire\Library\Authors;

use App\Models\BookAuthor;
use App\Livewire\Support\ModalComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AuthorForm extends ModalComponent
{
    use WithFileUploads;
    
    public $authorId = null;
    public $author = [];
    public $uploadedImage;
    public $imagePreview = null;
    
    protected $rules = [
        'author.name' => 'required|string|max:255',
        'author.nationality' => 'nullable|string|max:100',
        'author.birth_date' => 'nullable|date',
        'author.death_date' => 'nullable|date|after_or_equal:author.birth_date',
        'author.biography' => 'nullable|string',
        'author.website' => 'nullable|url|max:255',
        'author.email' => 'nullable|email|max:255',
        'author.is_active' => 'boolean',
        'author.is_featured' => 'boolean',
        'uploadedImage' => 'nullable|image|max:2048'
    ];
    
    public function mount($authorId = null)
    {
        $this->authorId = $authorId;
        
        // Default values
        $this->author = [
            'is_active' => true,
            'is_featured' => false,
        ];
        
        if ($this->authorId) {
            $this->loadAuthor();
        }
    }
    
    protected function loadAuthor()
    {
        $author = BookAuthor::findOrFail($this->authorId);
        
        $this->author = $author->toArray();
        
        // Format dates for input fields
        if ($this->author['birth_date']) {
            $this->author['birth_date'] = date('Y-m-d', strtotime($this->author['birth_date']));
        }
        
        if ($this->author['death_date']) {
            $this->author['death_date'] = date('Y-m-d', strtotime($this->author['death_date']));
        }
        
        // Load image preview if exists
        if ($author->image_path) {
            $this->imagePreview = Storage::url($author->image_path);
        }
    }
    
    public function createAuthor()
    {
        if (!$this->checkPermission('create')) {
            toast()->danger('You do not have permission to create authors')
                ->duration(3000)
                ->push();
            return;
        }
        
        $this->validate();
        
        try {
            $author = new BookAuthor();
            $author->name = $this->author['name'];
            $author->nationality = $this->author['nationality'] ?? null;
            $author->birth_date = $this->author['birth_date'] ?? null;
            $author->death_date = $this->author['death_date'] ?? null;
            $author->biography = $this->author['biography'] ?? null;
            $author->website = $this->author['website'] ?? null;
            $author->email = $this->author['email'] ?? null;
            $author->is_active = $this->author['is_active'] ?? true;
            $author->is_featured = $this->author['is_featured'] ?? false;
            $author->created_by = Auth::id();
            
            // Save author to get ID for image path
            $author->save();
            
            // Handle image upload
            if ($this->uploadedImage) {
                $imagePath = $this->uploadedImage->store('authors', 'public');
                $author->image_path = $imagePath;
                $author->save();
            }
            
            toast()->success('Author created successfully')
                ->duration(3000)
                ->push();
            
            $this->closeModalWithEvents([
                'authorCreated',
                'refreshAuthors'
            ]);
            
        } catch (\Exception $e) {
            toast()->danger('Failed to create author: ' . $e->getMessage())
                ->duration(5000)
                ->push();
        }
    }
    
    public function updateAuthor()
    {
        if (!$this->checkPermission('update')) {
            toast()->danger('You do not have permission to update authors')
                ->duration(3000)
                ->push();
            return;
        }
        
        $this->validate();
        
        try {
            $author = BookAuthor::findOrFail($this->authorId);
            $author->name = $this->author['name'];
            $author->nationality = $this->author['nationality'] ?? null;
            $author->birth_date = $this->author['birth_date'] ?? null;
            $author->death_date = $this->author['death_date'] ?? null;
            $author->biography = $this->author['biography'] ?? null;
            $author->website = $this->author['website'] ?? null;
            $author->email = $this->author['email'] ?? null;
            $author->is_active = $this->author['is_active'] ?? true;
            $author->is_featured = $this->author['is_featured'] ?? false;
            $author->updated_by = Auth::id();
            
            // Handle image upload
            if ($this->uploadedImage) {
                // Delete old image if exists
                if ($author->image_path) {
                    Storage::disk('public')->delete($author->image_path);
                }
                
                $imagePath = $this->uploadedImage->store('authors', 'public');
                $author->image_path = $imagePath;
            }
            
            $author->save();
            
            toast()->success('Author updated successfully')
                ->duration(3000)
                ->push();
            
            $this->closeModalWithEvents([
                'authorUpdated',
                'refreshAuthors'
            ]);
            
        } catch (\Exception $e) {
            toast()->danger('Failed to update author: ' . $e->getMessage())
                ->duration(5000)
                ->push();
        }
    }
    
    public function removeImage()
    {
        if ($this->authorId) {
            $author = BookAuthor::find($this->authorId);
            if ($author && $author->image_path) {
                Storage::disk('public')->delete($author->image_path);
                $author->image_path = null;
                $author->save();
            }
        }
        
        $this->uploadedImage = null;
        $this->imagePreview = null;
    }
    
    private function checkPermission($action)
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->hasAnyRole(['librarian', 'admin', 'superadmin'])) {
            return true;
        }
        
        return false;
    }

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }
    
    public function render()
    {
        return view('partials.library.authors.form-modal');
    }
} 