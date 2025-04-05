<?php

namespace App\Livewire\Library\Authors;

use App\Models\BookAuthor;
use App\Livewire\Support\ModalComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Toast;

class AuthorDelete extends ModalComponent
{
    public $authorToDelete;
    
    public function mount($authorId)
    {
        $this->authorToDelete = BookAuthor::withCount('books')->findOrFail($authorId);
    }
    
    public function deleteAuthor()
    {
        if (!$this->checkPermission('delete')) {
            toast()->danger('You do not have permission to delete authors')
                ->duration(3000)
                ->push();
            return;
        }
        
        try {
            // Store the name for the success message
            $authorName = $this->authorToDelete->name;
            
            // Delete the author
            $this->authorToDelete->delete();
            
            toast()->success("Author '{$authorName}' deleted successfully")
                ->duration(3000)
                ->push();
            
            $this->closeModalWithEvents([
                'authorDeleted',
                'refreshAuthors'
            ]);
            
        } catch (\Exception $e) {
            toast()->danger('Failed to delete author: ' . $e->getMessage())
                ->duration(5000)
                ->push();
        }
    }
    
    private function checkPermission($action)
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->hasAnyRole(['admin', 'superadmin'])) {
            return true;
        }
        
        if ($action === 'delete' && $user->hasRole('librarian')) {
            return true;
        }
        
        return false;
    }
    
    public static function modalMaxWidth(): string
    {
        return 'md';
    }
    
    public function render()
    {
        return view('partials.library.authors.delete-modal');
    }
} 