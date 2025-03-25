<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Toasts extends Component
{
    public $toasts = [];
    
    protected $listeners = ['toast'];

    public function render()
    {
        return view('livewire.toasts');
    }

    public function toast($type, $message, $title = null, $duration = 5000)
    {
        $this->toasts[] = [
            'id' => uniqid(),
            'type' => $type,
            'message' => $message,
            'title' => $title,
            'duration' => $duration,
        ];
    }

    public function remove($id)
    {
        $this->toasts = array_filter($this->toasts, function($toast) use ($id) {
            return $toast['id'] !== $id;
        });
    }
} 