<?php

namespace App\Livewire\Support;

use LivewireUI\Modal\ModalComponent as BaseModalComponent;

abstract class ModalComponent extends BaseModalComponent
{
    /**
     * Closes the modal and emits any events.
     *
     * @param array $events Events to emit when the modal is closed
     * @return void
     */
    public function closeModalWithEvents(array $events): void
    {
        parent::closeModal();
        
        foreach ($events as $event) {
            if (is_array($event)) {
                $this->dispatch($event['event'], $event['params'] ?? []);
            } else {
                $this->dispatch($event);
            }
        }
    }
    
    /**
     * Get the preferred modal width.
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return 'md';
    }
    
    /**
     * Forwards calls to component's render method.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return parent::render();
    }
} 