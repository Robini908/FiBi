<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class MessageManager extends Component
{
    public $view = 'inbox'; // Default view
    public $selectedUser = null;
    public $isTyping = false;
    public $otherUserTyping = false;
    public $searchTerm = '';
    public $message = '';
    public $messages = [];
    public $editReply = false;
    public $replyMessageText = '';
    public $messageBeingRepliedTo = null;

    public $messageReply = ''; // Store the reply message
    public $messageId = null;  // Store the ID of the message being replied to

    public $editedMessage = '';
    public $selectedUserId = null;
    public $recentMessages = [];
    public $unreadMessages = [];
    public $allUsers = [];
    public $messageBeingEdited = null;
    public $typingTimer = null;

    protected $queryString = [
        'view' => ['except' => 'inbox'],
        'selectedUserId' => ['except' => null, 'as' => 'user'],
    ];

    protected $listeners = [
        'messageRead' => 'loadMessages',
        'echo:typing,UserTyping' => 'handleUserTyping',
        'echo:typing,UserStoppedTyping' => 'handleUserStoppedTyping',
        'cancelTyping' => 'cancelTyping',
    ];

    public function editMessage($messageId)
    {
        $this->messageBeingRepliedTo = collect($this->messages)->firstWhere('id', $messageId);
        $this->editedMessage = $this->messageBeingRepliedTo['message'];
            $this->editReply = true;
    }

    public function updateMessage($messageId)
    {
        $this->validate([
            'editedMessage' => 'required|string|max:1000',
        ]);
        
        $message = ChatMessage::find($messageId);
        
        if ($message && $message->sender_id == auth()->id()) {
            $message->update([
                'message' => $this->editedMessage,
            ]);
            
            $this->loadMessages();
            $this->cancelEdit();
        }
    }

    public function cancelEdit()
    {
        $this->messageBeingRepliedTo = null;
        $this->editReply = false;
        $this->editedMessage = '';
    }

    public function replyMessage($messageId)
    {
        $this->messageBeingRepliedTo = ChatMessage::find($messageId); // Set message to reply to
        $this->editReply = true; // Enable the reply form
    }

    public function sendReply($messageId)
    {
        $message = new ChatMessage();
        $message->sender_id = auth()->id();
        $message->receiver_id = $this->messageBeingRepliedTo->sender_id; // Set the receiver to the original sender
        $message->message = $this->replyMessageText; // Set the reply message
        $message->reply_to = $messageId; // Link to the original message
        $message->save();

        $this->editReply = false; // Reset edit state
        $this->replyMessageText = ''; // Clear reply input
    }

    public function cancelReply()
    {
        $this->editReply = false; // Reset edit state
        $this->replyMessageText = ''; // Clear the reply text
    }

    public function handleUserTyping($data)
    {
        if ($data['senderId'] == $this->selectedUserId && $data['receiverId'] == auth()->id()) {
            $this->otherUserTyping = true;
            
            // Automatically cancel typing indication after 5 seconds
            $this->js("setTimeout(function() { $wire.otherUserTyping = false; }, 5000)");
        }
    }

    public function markMessagesAsRead($userId)
    {
        ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function handleUserStoppedTyping($data)
    {
        if ($data['senderId'] == $this->selectedUserId && $data['receiverId'] == auth()->id()) {
            $this->otherUserTyping = false;
        }
    }

    public function mount($userId = null)
    {
        if ($userId) {
            $this->selectedUserId = $userId;
            $this->view = 'chat';
            $this->loadSelectedUser();
        $this->loadMessages();
        } else {
            $this->loadRecentMessages();
            $this->loadUnreadMessages();
        }
    }

    /**
     * Load recent messages for the sidebar
     */
    protected function loadRecentMessages()
    {
        $authUser = auth()->user();
        
        // Get users with recent messages (either sender or receiver)
        $this->recentMessages = User::where('users.id', '!=', $authUser->id)
            ->whereExists(function ($query) use ($authUser) {
                $query->select(\DB::raw(1))
                    ->from('chat_messages')
                    ->whereRaw('(chat_messages.sender_id = users.id AND chat_messages.receiver_id = ?) OR (chat_messages.sender_id = ? AND chat_messages.receiver_id = users.id)', [$authUser->id, $authUser->id]);
            })
            ->with(['latestMessage' => function ($query) use ($authUser) {
                $query->where(function ($q) use ($authUser) {
                    $q->where('sender_id', $authUser->id)
                        ->orWhere('receiver_id', $authUser->id);
                })
                ->latest()
                ->limit(1);
            }])
            ->get();
    }
    
    /**
     * Load all users for the "All" tab
     */
    protected function loadAllUsers()
    {
        // Get all users except the current user
        $this->allUsers = User::where('id', '!=', auth()->id())
            ->when(auth()->user()->hasRole('teacher'), function ($query) {
                // If current user is a teacher, show only students and admins
                $query->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['student', 'admin', 'super_admin']);
                });
            })
            ->when(auth()->user()->hasRole('student'), function ($query) {
                // If current user is a student, show only teachers and admins
                $query->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['teacher', 'admin', 'super_admin']);
                });
            })
            ->when(auth()->user()->hasRole(['admin', 'super_admin']), function ($query) {
                // If current user is admin, show all users
                return $query;
            })
            ->orderBy('name')
            ->get();
    }
    
    /**
     * Load unread messages for the "Unread" tab
     */
    protected function loadUnreadMessages()
    {
        $authUser = auth()->user();
        
        // Get users with unread messages
        $this->unreadMessages = User::where('users.id', '!=', $authUser->id)
            ->whereExists(function ($query) use ($authUser) {
                $query->select(\DB::raw(1))
                    ->from('chat_messages')
                    ->where('chat_messages.sender_id', '=', 'users.id')
                    ->where('chat_messages.receiver_id', '=', $authUser->id)
                    ->whereNull('chat_messages.read_at');
            })
            ->with(['latestMessage' => function ($query) use ($authUser) {
                $query->where(function ($q) use ($authUser) {
                    $q->where('sender_id', '=', 'users.id')
                        ->where('receiver_id', '=', $authUser->id);
                })
                ->whereNull('read_at')
                ->latest()
                ->limit(1);
            }])
            ->get();
    }

    /**
     * Select the first unread conversation
     */
    public function selectFirstUnread()
    {
        if (count($this->unreadMessages) > 0) {
            $this->selectUser($this->unreadMessages[0]->id);
        }
    }

    /**
     * Update the search results when the search term changes
     */
    public function updatedSearchTerm()
    {
        if (empty($this->searchTerm)) {
            $this->loadRecentMessages();
            $this->loadUnreadMessages();
        }
    }

    /**
     * Get search results as a regular property for direct view access
     */
    public function getSearchResults()
    {
        if (empty($this->searchTerm)) {
            return [];
        }
        
        return User::where('id', '!=', auth()->id())
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            })
            ->limit(10) // Limit results for performance
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            });
    }

    public function startTyping()
    {
        if ($this->selectedUserId) {
            broadcast(new \App\Events\UserTyping(auth()->id(), $this->selectedUserId));
        }
    }

    public function stopTyping()
    {
        // Stop typing after 3 seconds of inactivity using JavaScript dispatch
        $this->dispatch('setTimeout', ['duration' => 3000]);
    }

    public function cancelTyping() 
    {
        if ($this->selectedUserId) {
            broadcast(new \App\Events\UserStoppedTyping(auth()->id(), $this->selectedUserId));
        }
    }

    /**
     * Select a user to chat with
     */
    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->loadSelectedUser();
        $this->loadMessages();
        $this->view = 'chat';
        
        // Mark messages as read
        ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        // Refresh sidebar data
        $this->loadRecentMessages();
        $this->loadUnreadMessages();
        
        // Dispatch event to update unread count in header
        $this->dispatch('messageRead');
    }

    public function getUnreadCount($userId)
    {
        return ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    public function loadMessages()
    {
        if (!$this->selectedUserId) {
            $this->messages = [];
            return;
        }
        
        $messages = ChatMessage::where(function ($query) {
            $query->where(function ($q) {
                $q->where('sender_id', auth()->id())
                    ->where('receiver_id', $this->selectedUserId);
            })->orWhere(function ($q) {
                $q->where('sender_id', $this->selectedUserId)
                    ->where('receiver_id', auth()->id());
            });
        })
                ->orderBy('created_at', 'asc')
                ->get()
        ->map(function ($message) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'read_at' => $message->read_at,
                'created_at' => $message->created_at,
            ];
        })->toArray();
        
        $this->messages = $messages;
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedUserId,
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->loadMessages();
        
        // Refresh sidebar data
        $this->loadRecentMessages();
        
        // Dispatch event for real-time updates
        $this->dispatch('messageSent');
    }

    public function backToInbox()
    {
        $this->selectedUserId = null;
        $this->selectedUser = null;
        $this->view = 'inbox';
        $this->loadRecentMessages();
        $this->loadUnreadMessages();
    }

    public function render()
    {
        return view('livewire.message-manager');
    }

    public function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
            if (strlen($initials) >= 2) break;
        }
        
        return $initials;
    }

    public function getRandomColor($userId)
    {
        // Generate a consistent color based on user ID
        $colors = [
            'from-green-400 to-green-600',
            'from-blue-400 to-blue-600',
            'from-purple-400 to-purple-600',
            'from-red-400 to-red-600',
            'from-yellow-400 to-yellow-600',
            'from-pink-400 to-pink-600',
            'from-indigo-400 to-indigo-600',
        ];
        
        return $colors[$userId % count($colors)];
    }

    /**
     * Check if a user is online using an algorithm-based approach
     * This method uses a combination of factors to determine online status:
     * 1. Active session presence
     * 2. Recent message activity
     * 3. Typing indicators
     * 4. Time-based decay
     *
     * @param int $userId The user ID to check
     * @return bool Whether the user is online
     */
    public function isOnline($userId)
    {
        $user = User::find($userId);
        
        if (!$user || !$user->last_active_at) {
            return false;
        }
        
        // Consider a user online if they've been active in the last 5 minutes
        return now()->diffInMinutes($user->last_active_at) < 5;
    }

    public function deleteMessage($messageId, $deleteForAll = false)
    {
        $message = ChatMessage::find($messageId);

        if (!$message) return;
        
        // Check if user is authorized to delete
        $isReceiver = $message->receiver_id == auth()->id();
        $isSender = $message->sender_id == auth()->id();
        
        if ($deleteForAll) {
            // Only the sender can delete for everyone
            if ($isSender) {
                $message->delete();
            }
        } else {
            // Receiver can delete for themselves only
            if ($isReceiver) {
                $message->update([
                    'deleted_by_receiver' => true
                ]);
            }
            // Sender can delete for themselves
            else if ($isSender) {
                $message->update([
                    'deleted_by_sender' => true
                ]);
            }
        }
        
        $this->loadMessages();
    }

    public function archiveMessage($messageId)
    {
        $message = ChatMessage::find($messageId);

        if ($message) {
            // Archive the message by setting archived_at to the current time
            $message->update(['archived_at' => now()]);
            $this->loadMessages();
        }
    }

    public function shareMessage($messageId)
    {
        $message = ChatMessage::find($messageId);

        if ($message) {
            // Implement the sharing logic here, like opening a modal or sending the message
            // For now, we will just return the message content
            session()->flash('shared_message', $message->message);
            $this->loadMessages();
        }
    }

    public function replyToMessage($messageId, $replyText)
    {
        $message = ChatMessage::find($messageId);

        if ($message) {
            // Create a new reply message
            ChatMessage::create([
                'message' => $replyText,
                'sender_id' => auth()->id(),
                'receiver_id' => $message->sender_id,
                'parent_id' => $messageId, // Associate it with the original message
            ]);
            $this->loadMessages();
        }
    }

    /**
     * Update unread count from dropdown
     */
    public function updateUnreadCount()
    {
        // Refresh unread messages
        $this->loadUnreadMessages();
        
        // Emit the count for the badge
        $count = $this->unreadMessages->count();
        $this->dispatch('unreadCountUpdated', count: $count);
        
        return $count;
    }
    
    /**
     * Get total unread messages count
     */
    public function getTotalUnreadCountProperty()
    {
        return $this->unreadMessages->sum(function ($user) {
            return $this->getUnreadCount($user->id);
        });
    }

    public function loadSelectedUser()
    {
        $this->selectedUser = User::find($this->selectedUserId);
    }
}
