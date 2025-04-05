<?php

namespace App\Http\Livewire\Library\Books;

use App\Helpers\Qs;
use App\Models\BookCopy;
use App\Models\BookLoan;
use App\Models\BookReservation;
use App\Models\LibraryBook;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class BookDetail extends Component
{
    public $book;
    public $copies;
    public $availableCopies;
    public $activeLoanCount;
    public $reservationCount;
    
    // For adding new copies
    public $showCopyModal = false;
    public $copyNumber;
    public $barcode;
    public $rfidTag;
    public $acquisitionDate;
    public $price;
    public $acquisitionSource;
    public $shelfLocation;
    public $condition = 'Good';
    public $conditionNotes;
    
    // For reservations
    public $showReservationModal = false;
    public $reservationNotes;
    public $reservationPriority = 'Normal';
    
    protected $listeners = [
        'refreshCopies' => 'refreshCopiesData', 
        'copyAdded' => 'handleCopyAdded'
    ];

    public function mount($bookId)
    {
        $this->loadBookData($bookId);
    }
    
    private function loadBookData($bookId)
    {
        $this->book = LibraryBook::with(['category', 'authors'])->findOrFail($bookId);
        $this->refreshCopiesData();
    }
    
    public function refreshCopiesData()
    {
        $this->copies = BookCopy::where('book_id', $this->book->id)
            ->with(['loans' => function($query) {
                $query->where('status', 'Active');
            }])
            ->get();
            
        $this->availableCopies = $this->copies->where('status', 'Available')->count();
        $this->activeLoanCount = BookLoan::whereIn('book_copy_id', $this->copies->pluck('id'))
            ->where('status', 'Active')
            ->count();
            
        $this->reservationCount = BookReservation::where('book_id', $this->book->id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->count();
    }
    
    public function render()
    {
        $canManageBook = Qs::isLibrarian() || Qs::isAdministrator();
        $canBorrowBook = !$this->book->is_reference_only && $this->availableCopies > 0;
        
        // Role-based permissions for borrowing
        $canRequestBorrow = false;
        
        if (Qs::isStudent() || Qs::isTeacher()) {
            $canRequestBorrow = true;
        } elseif (Qs::isParent()) {
            // Parents can request books on behalf of their children
            $canRequestBorrow = Qs::findMyChildren(Auth::id())->count() > 0;
        }
        
        $studentId = null;
        if (Qs::isStudent()) {
            $studentId = Auth::id();
        }
        
        // Check if the current user has already borrowed this book
        $userHasActiveLoan = false;
        if ($studentId) {
            $userHasActiveLoan = BookLoan::whereIn('book_copy_id', $this->copies->pluck('id'))
                ->where('user_id', $studentId)
                ->where('status', 'Active')
                ->exists();
        }
        
        // Check if the user has an active reservation
        $userHasActiveReservation = false;
        if ($studentId) {
            $userHasActiveReservation = BookReservation::where('book_id', $this->book->id)
                ->where('user_id', $studentId)
                ->whereIn('status', ['Pending', 'Approved'])
                ->exists();
        }
        
        return view('livewire.library.books.book-detail', [
            'canManageBook' => $canManageBook,
            'canBorrowBook' => $canBorrowBook,
            'canRequestBorrow' => $canRequestBorrow,
            'userHasActiveLoan' => $userHasActiveLoan,
            'userHasActiveReservation' => $userHasActiveReservation
        ]);
    }
    
    public function openCopyModal()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to add book copies']);
            return;
        }
        
        $this->resetCopyForm();
        $this->showCopyModal = true;
    }
    
    public function saveCopy()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to add book copies']);
            return;
        }
        
        $this->validate([
            'copyNumber' => 'required|string',
            'barcode' => 'nullable|string',
            'rfidTag' => 'nullable|string',
            'acquisitionDate' => 'nullable|date',
            'price' => 'nullable|numeric',
            'shelfLocation' => 'nullable|string',
            'condition' => 'required|string',
        ]);
        
        // Create a new book copy
        $copy = new BookCopy();
        $copy->book_id = $this->book->id;
        $copy->copy_number = $this->copyNumber;
        $copy->barcode = $this->barcode;
        $copy->rfid_tag = $this->rfidTag;
        $copy->acquisition_date = $this->acquisitionDate;
        $copy->price = $this->price;
        $copy->acquisition_source = $this->acquisitionSource;
        $copy->shelf_location = $this->shelfLocation;
        $copy->condition = $this->condition;
        $copy->condition_notes = $this->conditionNotes;
        $copy->status = 'Available';
        $copy->last_inventory_date = Carbon::now();
        
        $copy->save();
        
        $this->resetCopyForm();
        $this->dispatchBrowserEvent('toast-success', ['message' => 'Book copy added successfully']);
        $this->emit('copyAdded');
        $this->refreshCopiesData();
    }
    
    public function handleCopyAdded()
    {
        $this->refreshCopiesData();
    }
    
    public function openReservationModal()
    {
        if (!Qs::isStudent() && !Qs::isTeacher() && !Qs::isParent()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to reserve books']);
            return;
        }
        
        // Check if the user can borrow the book
        if ($this->book->is_reference_only) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'This book is for reference only and cannot be reserved']);
            return;
        }
        
        $this->resetReservationForm();
        $this->showReservationModal = true;
    }
    
    public function saveReservation()
    {
        if (!Qs::isStudent() && !Qs::isTeacher() && !Qs::isParent()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to reserve books']);
            return;
        }
        
        $this->validate([
            'reservationNotes' => 'nullable|string|max:255',
            'reservationPriority' => 'required|in:Low,Normal,High',
        ]);
        
        // Create the reservation
        $reservation = new BookReservation();
        $reservation->book_id = $this->book->id;
        $reservation->user_id = Auth::id();
        $reservation->reservation_date = Carbon::now();
        $reservation->status = 'Pending';
        $reservation->notes = $this->reservationNotes;
        $reservation->priority = $this->reservationPriority;
        
        $reservation->save();
        
        $this->resetReservationForm();
        $this->dispatchBrowserEvent('toast-success', ['message' => 'Book reserved successfully. The librarian will contact you when it becomes available.']);
        $this->refreshCopiesData();
    }
    
    private function resetCopyForm()
    {
        $this->copyNumber = '';
        $this->barcode = '';
        $this->rfidTag = '';
        $this->acquisitionDate = null;
        $this->price = null;
        $this->acquisitionSource = '';
        $this->shelfLocation = '';
        $this->condition = 'Good';
        $this->conditionNotes = '';
        $this->showCopyModal = false;
    }
    
    private function resetReservationForm()
    {
        $this->reservationNotes = '';
        $this->reservationPriority = 'Normal';
        $this->showReservationModal = false;
    }
} 