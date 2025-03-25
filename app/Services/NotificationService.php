<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class NotificationService
{
    /**
     * Flash a success message.
     *
     * @param string $message
     * @param string|null $title
     * @param int $duration
     * @return $this
     */
    public function success(string $message, ?string $title = null, int $duration = 5000)
    {
        return $this->flash('success', $message, $title, $duration);
    }

    /**
     * Flash an info message.
     *
     * @param string $message
     * @param string|null $title
     * @param int $duration
     * @return $this
     */
    public function info(string $message, ?string $title = null, int $duration = 5000)
    {
        return $this->flash('info', $message, $title, $duration);
    }

    /**
     * Flash a warning message.
     *
     * @param string $message
     * @param string|null $title
     * @param int $duration
     * @return $this
     */
    public function warning(string $message, ?string $title = null, int $duration = 5000)
    {
        return $this->flash('warning', $message, $title, $duration);
    }

    /**
     * Flash a danger message.
     *
     * @param string $message
     * @param string|null $title
     * @param int $duration
     * @return $this
     */
    public function danger(string $message, ?string $title = null, int $duration = 5000)
    {
        return $this->flash('danger', $message, $title, $duration);
    }

    /**
     * Flash an error message (alias for danger).
     *
     * @param string $message
     * @param string|null $title
     * @param int $duration
     * @return $this
     */
    public function error(string $message, ?string $title = null, int $duration = 5000)
    {
        return $this->danger($message, $title, $duration);
    }

    /**
     * Flash a debug message.
     *
     * @param string $message
     * @param string|null $title
     * @param int $duration
     * @return $this
     */
    public function debug(string $message, ?string $title = null, int $duration = 5000)
    {
        // Only show debug messages in development
        if (app()->environment('local', 'development', 'testing')) {
            return $this->flash('debug', $message, $title, $duration);
        }
        return $this;
    }

    /**
     * Flash a message for the next request.
     */
    public function pushOnNextPage()
    {
        if ($toast = Session::get('_toast')) {
            Session::flash('_toast', $toast);
        }
        return $this;
    }

    /**
     * Flash a message now.
     */
    public function push()
    {
        if ($toast = Session::get('_toast')) {
            // Push to Livewire component via browser events
            $this->dispatchBrowserEvent($toast);
        }
        return $this;
    }

    /**
     * Store the toast message in the session.
     */
    protected function flash(string $type, string $message, ?string $title = null, int $duration = 5000)
    {
        Session::flash('_toast', [
            'type' => $type,
            'message' => $message, 
            'title' => $title,
            'duration' => $duration
        ]);
        return $this;
    }
    
    /**
     * Dispatch a browser event to trigger the toast.
     */
    protected function dispatchBrowserEvent(array $toast)
    {
        if (request()->expectsJson()) {
            return;
        }
        
        // Add JavaScript to dispatch Livewire event
        $script = "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    window.livewire.emit('toast', '{$toast['type']}', '{$toast['message']}', " . 
                        ($toast['title'] ? "'{$toast['title']}'" : 'null') . 
                        ", {$toast['duration']});
                });
            </script>
        ";
        
        // Store script to be rendered later
        Session::flash('_toast_script', $script);
    }
} 