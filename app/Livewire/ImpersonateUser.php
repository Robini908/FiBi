<?php

namespace App\Livewire;

use Livewire\Component;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helpers\Qs;

class ImpersonateUser extends Component
{
    public $search = '';
    public $users = [];
    public $recentlyImpersonated = [];
    
    public function mount()
    {
        // Load recently impersonated users from session if available
        $this->recentlyImpersonated = session('recently_impersonated', []);
    }

    public function render()
    {
        // Only show the impersonation feature to administrators (admin or super_admin roles)
        if (Auth::check() && Qs::isAdministrator()) {
            // Search for users based on the search term
            if ($this->search) {
                $this->users = User::where(function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->with('roles') // Eager load roles to avoid N+1 queries
                ->where('id', '!=', Auth::id()) // Don't include the current user
                ->orderBy('name')
                ->limit(10)
                ->get();
            } else {
                $this->users = collect(); // Ensure $users is always a collection
            }
        } else {
            $this->users = collect(); // Ensure $users is always a collection
        }

        return view('livewire.impersonate-user');
    }

    /**
     * Begin impersonating a user
     *
     * @param int $userId User ID to impersonate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate($userId)
    {
        // Verify user has administrator privileges before allowing impersonation
        if (!Qs::isAdministrator()) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'You do not have permission to impersonate users.'
            ]);
            return;
        }

        try {
            $user = User::findOrFail($userId);
            
            // Don't allow impersonating yourself
            if ($user->id === Auth::id()) {
                $this->dispatch('toast', [
                    'type' => 'error', 
                    'message' => 'You cannot impersonate yourself.'
                ]);
                return;
            }
            
            // Store the original user ID in the session
            session()->put('impersonated_by', Auth::id());
            
            // Add to recently impersonated users list (maintain max 5 users)
            $recentList = session('recently_impersonated', []);
            
            // Only store essential user info
            $userInfo = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo,
                'role' => $user->roles->first()->name ?? 'User',
                'timestamp' => now()->timestamp
            ];
            
            // Remove if already exists to avoid duplicates
            $recentList = array_filter($recentList, function($item) use ($userId) {
                return $item['id'] != $userId;
            });
            
            // Add to beginning of array
            array_unshift($recentList, $userInfo);
            
            // Keep only the latest 5 users
            $recentList = array_slice($recentList, 0, 5);
            
            session()->put('recently_impersonated', $recentList);
            
            // Log the impersonation event
            Log::info("User {$userId} impersonated by " . Auth::id());

            // Log in as the selected user
            Auth::login($user);

            // Redirect to the dashboard or appropriate page
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Failed to impersonate user: ' . $e->getMessage()
            ]);
            Log::error('Impersonation error: ' . $e->getMessage());
        }
    }

    /**
     * Stop impersonating and return to original user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stopImpersonating()
    {
        try {
            // Get the original user ID from the session
            $originalUserId = session('impersonated_by');

            if ($originalUserId) {
                // Log back in as the original user
                Auth::loginUsingId($originalUserId);

                // Remove the impersonation session data
                session()->forget('impersonated_by');
                
                // Log the end of impersonation
                Log::info("User stopped impersonating, returned to user {$originalUserId}");
                
                // Display success message
                $this->dispatch('toast', [
                    'type' => 'success', 
                    'message' => 'Successfully returned to your account.'
                ]);
            }

            // Redirect to the dashboard or appropriate page
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Failed to stop impersonating: ' . $e->getMessage()
            ]);
            Log::error('Stop impersonation error: ' . $e->getMessage());
        }
    }
    
    /**
     * Loads recently impersonated users from session
     * 
     * @return void
     */
    public function loadRecentUsers()
    {
        $this->recentlyImpersonated = session('recently_impersonated', []);
    }
}