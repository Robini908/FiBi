<?php

namespace App\Livewire\Library\Authors;

use App\Models\BookAuthor;
use App\Livewire\Support\ModalComponent;
use Illuminate\Support\Facades\Auth;

class AuthorView extends ModalComponent
{
    public $author;
    
    public function mount($authorId)
    {
        $this->author = BookAuthor::with('books')->findOrFail($authorId);
    }
    
    public function editAuthor()
    {
        if ($this->checkPermission('update')) {
            $this->closeModal();
            $this->dispatch('openModal', [
                'component' => 'library.authors.author-form',
                'arguments' => [
                    'authorId' => $this->author->id
                ]
            ]);
        } else {
            toast()->danger('You do not have permission to edit authors')
                ->duration(3000)
                ->push();
        }
    }
    
    public function generateProfile()
    {
        if ($this->checkPermission('view')) {
            // Close modal first
            $this->closeModal();
            
            // Redirect to profile generation
            return redirect()->route('library.authors.profile', ['author' => $this->author->id]);
        } else {
            toast()->danger('You do not have permission to view author profiles')
                ->duration(3000)
                ->push();
        }
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
        
        if ($action === 'view' && $user->hasAnyRole(['teacher', 'student', 'parent'])) {
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
        return view('partials.library.authors.view-modal');
    }
} 