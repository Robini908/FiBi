<?php

namespace App\Http\Livewire\Library\Reservations;

use App\Helpers\Qs;
use App\Models\BookCopy;
use App\Models\BookLoan;
use App\Models\BookReservation;
use App\Models\LibraryBook;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReservationList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortField = 'reservation_date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // For approving reservation
    public $showApproveModal = false;
    public $reservationId;
    public $expiryDate;
    public $approvalNotes;
    
    // For rejecting reservation
    public $showRejectModal = false;
    public $rejectionNotes;
    
    // For fulfilling reservation
    public $showFulfillModal = false;
    public $bookCopyId;
    public $dueDate;
    public $availableCopies = [];
    
    protected $listeners = ['refreshReservations' => '$refresh'];

    public function mount()
    {
        $this->expiryDate = Carbon::now()->addDays(3)->format('Y-m-d');
        $this->dueDate = Carbon::now()->addDays(14)->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    public function render()
    {
        $query = BookReservation::query()
            ->with(['book', 'user', 'approvedBy', 'rejectedBy']);
        
        // Role-based access controls
        if (Qs::isLibrarian() || Qs::isAdministrator()) {
            // Librarians and admins can see all reservations
            $query->when($this->search, function ($q) {
                return $q->whereHas('book', function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('isbn', 'like', '%' . $this->search . '%')
                        ->orWhere('isbn13', 'like', '%' . $this->search . '%');
                })->orWhereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            });
        } elseif (Qs::isStudent() || Qs::isTeacher()) {
            // Students and teachers can only see their own reservations
            $query->where('user_id', Auth::id());
        } elseif (Qs::isParent()) {
            // Parents can see their children's reservations
            $childrenIds = Qs::findMyChildren(Auth::id())->pluck('user_id')->toArray();
            $query->whereIn('user_id', $childrenIds);
        } else {
            // Other roles shouldn't see any reservations
            $query->where('id', -1); // This will return no results
        }
        
        // Apply filters
        $query->when($this->statusFilter === 'pending', function ($q) {
            return $q->where('status', 'Pending');
        })->when($this->statusFilter === 'approved', function ($q) {
            return $q->where('status', 'Approved');
        })->when($this->statusFilter === 'rejected', function ($q) {
            return $q->where('status', 'Rejected');
        })->when($this->statusFilter === 'fulfilled', function ($q) {
            return $q->where('status', 'Fulfilled');
        })->when($this->statusFilter === 'cancelled', function ($q) {
            return $q->where('status', 'Cancelled');
        });
        
        $reservations = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        
        // Permissions based on role
        $canManageReservations = Qs::isLibrarian() || Qs::isAdministrator();
        $canApproveReservations = $canManageReservations;
        $canFulfillReservations = $canManageReservations;
        
        return view('livewire.library.reservations.reservation-list', [
            'reservations' => $reservations,
            'canManageReservations' => $canManageReservations,
            'canApproveReservations' => $canApproveReservations,
            'canFulfillReservations' => $canFulfillReservations,
        ]);
    }
    
    public function openApproveModal($id)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to approve reservations']);
            return;
        }
        
        $this->reservationId = $id;
        $reservation = BookReservation::findOrFail($id);
        
        // Check if reservation is pending
        if ($reservation->status !== 'Pending') {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'Only pending reservations can be approved']);
            return;
        }
        
        $this->showApproveModal = true;
    }
    
    public function approveReservation()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to approve reservations']);
            return;
        }
        
        $this->validate([
            'expiryDate' => 'required|date|after_or_equal:today',
        ]);
        
        $reservation = BookReservation::findOrFail($this->reservationId);
        
        // Approve the reservation
        $reservation->status = 'Approved';
        $reservation->approved_by = Auth::id();
        $reservation->expiry_date = $this->expiryDate;
        $reservation->notes = $this->approvalNotes ?: $reservation->notes;
        
        $reservation->save();
        
        $this->showApproveModal = false;
        $this->dispatchBrowserEvent('toast-success', ['message' => 'Reservation approved successfully']);
        $this->emit('refreshReservations');
    }
    
    public function openRejectModal($id)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to reject reservations']);
            return;
        }
        
        $this->reservationId = $id;
        $reservation = BookReservation::findOrFail($id);
        
        // Check if reservation is pending
        if ($reservation->status !== 'Pending') {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'Only pending reservations can be rejected']);
            return;
        }
        
        $this->showRejectModal = true;
    }
    
    public function rejectReservation()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to reject reservations']);
            return;
        }
        
        $this->validate([
            'rejectionNotes' => 'required|string|max:255',
        ]);
        
        $reservation = BookReservation::findOrFail($this->reservationId);
        
        // Reject the reservation
        $reservation->status = 'Rejected';
        $reservation->rejected_by = Auth::id();
        $reservation->notes = $this->rejectionNotes;
        
        $reservation->save();
        
        $this->showRejectModal = false;
        $this->dispatchBrowserEvent('toast-success', ['message' => 'Reservation rejected successfully']);
        $this->emit('refreshReservations');
    }
    
    public function openFulfillModal($id)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to fulfill reservations']);
            return;
        }
        
        $this->reservationId = $id;
        $reservation = BookReservation::findOrFail($id);
        
        // Check if reservation is approved or pending
        if (!in_array($reservation->status, ['Pending', 'Approved'])) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'Only pending or approved reservations can be fulfilled']);
            return;
        }
        
        // Get available copies of the book
        $this->availableCopies = BookCopy::where('book_id', $reservation->book_id)
            ->where('status', 'Available')
            ->get();
            
        if ($this->availableCopies->isEmpty()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'No copies available for this book']);
            return;
        }
        
        $this->showFulfillModal = true;
    }
    
    public function fulfillReservation()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to fulfill reservations']);
            return;
        }
        
        $this->validate([
            'bookCopyId' => 'required|exists:book_copies,id',
            'dueDate' => 'required|date|after_or_equal:today',
        ]);
        
        $reservation = BookReservation::findOrFail($this->reservationId);
        
        // Check if the book copy is available
        $copy = BookCopy::findOrFail($this->bookCopyId);
        if ($copy->status !== 'Available') {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'This copy is not available for loan']);
            return;
        }
        
        // Create a new loan
        $loan = new BookLoan();
        $loan->book_copy_id = $this->bookCopyId;
        $loan->user_id = $reservation->user_id;
        $loan->issued_by = Auth::id();
        $loan->issue_date = Carbon::now();
        $loan->due_date = $this->dueDate;
        $loan->status = 'Active';
        $loan->notes = 'Fulfilling reservation #' . $reservation->id;
        
        $loan->save();
        
        // Update the reservation
        $reservation->status = 'Fulfilled';
        $reservation->fulfillment_date = Carbon::now();
        $reservation->loan_id = $loan->id;
        
        $reservation->save();
        
        $this->showFulfillModal = false;
        $this->dispatchBrowserEvent('toast-success', ['message' => 'Reservation fulfilled successfully']);
        $this->emit('refreshReservations');
    }
    
    public function cancelReservation($id)
    {
        $reservation = BookReservation::findOrFail($id);
        
        // Check permissions
        if (Qs::isLibrarian() || Qs::isAdministrator()) {
            // Librarians and admins can cancel any reservation
        } elseif ($reservation->user_id === Auth::id()) {
            // Users can cancel their own reservations
        } elseif (Qs::isParent()) {
            // Parents can cancel their children's reservations
            $childrenIds = Qs::findMyChildren(Auth::id())->pluck('user_id')->toArray();
            if (!in_array($reservation->user_id, $childrenIds)) {
                $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to cancel this reservation']);
                return;
            }
        } else {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to cancel this reservation']);
            return;
        }
        
        // Check if reservation can be cancelled
        if (!in_array($reservation->status, ['Pending', 'Approved'])) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'Only pending or approved reservations can be cancelled']);
            return;
        }
        
        // Cancel the reservation
        $reservation->status = 'Cancelled';
        $reservation->notes = $reservation->notes . ' (Cancelled on ' . Carbon::now()->format('Y-m-d') . ')';
        
        $reservation->save();
        
        $this->dispatchBrowserEvent('toast-success', ['message' => 'Reservation cancelled successfully']);
        $this->emit('refreshReservations');
    }
} 