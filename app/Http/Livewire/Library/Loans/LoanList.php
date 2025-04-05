<?php

namespace App\Http\Livewire\Library\Loans;

use App\Helpers\Qs;
use App\Models\BookCopy;
use App\Models\BookLoan;
use App\Models\LibraryBook;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LoanList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $sortField = 'issue_date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // For issuing a new loan
    public $showLoanModal = false;
    public $bookId;
    public $bookCopyId;
    public $userId;
    public $issueDate;
    public $dueDate;
    public $notes;
    
    // For returning a book
    public $showReturnModal = false;
    public $loanId;
    public $returnDate;
    public $conditionOnReturn = 'Good';
    public $returnNotes;
    public $fineAmount;
    public $isFinePaid = false;
    
    // For viewing a student's loans (for parents)
    public $showingStudentLoans = false;
    public $selectedStudentId;
    public $children = [];
    
    // For the book copy selection
    public $availableCopies;
    public $selectedBook;
    
    // For user selection
    public $students = [];
    public $searchStudent = '';
    
    protected $listeners = ['refreshLoans' => '$refresh'];

    public function mount()
    {
        $this->issueDate = Carbon::now()->format('Y-m-d');
        $this->dueDate = Carbon::now()->addDays(14)->format('Y-m-d');
        $this->returnDate = Carbon::now()->format('Y-m-d');
        $this->availableCopies = collect();
        
        // If the user is a parent, load their children
        if (Qs::isParent()) {
            $this->children = Qs::findMyChildren(Auth::id())->toArray();
            if (count($this->children) > 0) {
                $this->selectedStudentId = $this->children[0]->user_id;
                $this->showingStudentLoans = true;
            }
        }
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
    
    public function showStudentLoans($studentId)
    {
        // Verify that the parent has permission to view this student's loans
        if (Qs::isParent() && !Qs::isParentOfStudent($studentId, Auth::id())) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to view this student\'s loans']);
            return;
        }
        
        $this->selectedStudentId = $studentId;
        $this->showingStudentLoans = true;
    }
    
    public function resetFilters()
    {
        $this->showingStudentLoans = false;
        $this->selectedStudentId = null;
        $this->search = '';
        $this->statusFilter = '';
        $this->dateFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = BookLoan::query()
            ->with(['bookCopy.book', 'borrower', 'issuedByUser']);
        
        // Role-based access controls
        if (Qs::isLibrarian() || Qs::isAdministrator()) {
            // Librarians and admins can see all loans
            $query->when($this->search, function ($q) {
                return $q->whereHas('bookCopy.book', function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('isbn', 'like', '%' . $this->search . '%')
                        ->orWhere('isbn13', 'like', '%' . $this->search . '%');
                })->orWhereHas('borrower', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            });
        } elseif (Qs::isStudent()) {
            // Students can only see their own loans
            $query->where('user_id', Auth::id());
        } elseif (Qs::isParent() && $this->showingStudentLoans) {
            // Parents can see their children's loans
            $query->where('user_id', $this->selectedStudentId);
        } elseif (Qs::isParent()) {
            // If parent but no child selected, show a placeholder (empty result)
            $query->where('user_id', -1); // This will return no results
        } elseif (Qs::isTeacher()) {
            // Teachers can see their own loans
            $query->where('user_id', Auth::id());
        } else {
            // Other roles shouldn't see any loans
            $query->where('id', -1); // This will return no results
        }
        
        // Apply filters
        $query->when($this->statusFilter === 'active', function ($q) {
            return $q->where('status', 'Active');
        })->when($this->statusFilter === 'overdue', function ($q) {
            return $q->where('status', 'Active')
                ->where('due_date', '<', Carbon::now());
        })->when($this->statusFilter === 'returned', function ($q) {
            return $q->where('status', 'Returned');
        })->when($this->dateFilter === 'today', function ($q) {
            return $q->whereDate('issue_date', Carbon::today());
        })->when($this->dateFilter === 'this_week', function ($q) {
            return $q->whereBetween('issue_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        })->when($this->dateFilter === 'this_month', function ($q) {
            return $q->whereMonth('issue_date', Carbon::now()->month)
                ->whereYear('issue_date', Carbon::now()->year);
        });
        
        $loans = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        
        // Permissions based on role
        $canManageLoans = Qs::isLibrarian() || Qs::isAdministrator();
        $canSeeAllLoans = $canManageLoans;
        $canIssueLoans = $canManageLoans;
        $showStudentSelector = Qs::isParent() && count($this->children) > 1;
        
        return view('livewire.library.loans.loan-list', [
            'loans' => $loans,
            'canManageLoans' => $canManageLoans,
            'canSeeAllLoans' => $canSeeAllLoans,
            'canIssueLoans' => $canIssueLoans,
            'showStudentSelector' => $showStudentSelector,
            'children' => $this->children,
        ]);
    }
    
    public function openLoanModal()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to issue loans']);
            return;
        }
        
        $this->resetLoanForm();
        $this->students = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['student', 'teacher']);
        })->get();
        
        $this->showLoanModal = true;
    }
    
    public function bookSelected()
    {
        if (!$this->bookId) {
            $this->availableCopies = collect();
            return;
        }
        
        $this->selectedBook = LibraryBook::findOrFail($this->bookId);
        $this->availableCopies = BookCopy::where('book_id', $this->bookId)
            ->where('status', 'Available')
            ->get();
            
        if ($this->availableCopies->isEmpty()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'No copies available for this book']);
        }
    }
    
    public function saveLoan()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to issue loans']);
            return;
        }
        
        $this->validate([
            'bookCopyId' => 'required|exists:book_copies,id',
            'userId' => 'required|exists:users,id',
            'issueDate' => 'required|date',
            'dueDate' => 'required|date|after_or_equal:issueDate',
        ]);
        
        // Check if the book copy is available
        $copy = BookCopy::findOrFail($this->bookCopyId);
        if ($copy->status !== 'Available') {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'This copy is not available for loan']);
            return;
        }
        
        // Create a new loan
        $loan = new BookLoan();
        $loan->book_copy_id = $this->bookCopyId;
        $loan->user_id = $this->userId;
        $loan->issued_by = Auth::id();
        $loan->issue_date = $this->issueDate;
        $loan->due_date = $this->dueDate;
        $loan->status = 'Active';
        $loan->notes = $this->notes;
        
        $loan->save();
        
        $this->resetLoanForm();
        $this->dispatchBrowserEvent('toast-success', ['message' => 'Book loaned successfully']);
        $this->emit('refreshLoans');
    }
    
    public function openReturnModal($id)
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to return books']);
            return;
        }
        
        $this->loanId = $id;
        $loan = BookLoan::findOrFail($id);
        
        // Calculate fine if overdue
        $this->fineAmount = 0;
        if (Carbon::now()->gt($loan->due_date)) {
            $daysOverdue = Carbon::now()->diffInDays($loan->due_date);
            $this->fineAmount = $daysOverdue * 0.50; // $0.50 per day overdue
        }
        
        $this->showReturnModal = true;
    }
    
    public function returnBook()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to return books']);
            return;
        }
        
        $this->validate([
            'returnDate' => 'required|date',
            'conditionOnReturn' => 'required|string',
        ]);
        
        $loan = BookLoan::findOrFail($this->loanId);
        
        // Update the loan record
        $loan->return_date = $this->returnDate;
        $loan->received_by = Auth::id();
        $loan->status = 'Returned';
        $loan->condition_on_return = $this->conditionOnReturn;
        $loan->notes = $this->returnNotes;
        
        // Set fine if applicable
        if ($this->fineAmount > 0) {
            $loan->fine_amount = $this->fineAmount;
            $loan->is_fine_paid = $this->isFinePaid;
            if ($this->isFinePaid) {
                $loan->fine_paid_date = Carbon::now();
            }
        }
        
        $loan->save();
        
        // Update the book copy status
        $copy = $loan->bookCopy;
        $copy->status = 'Available';
        $copy->save();
        
        $this->resetReturnForm();
        $this->dispatchBrowserEvent('toast-success', ['message' => 'Book returned successfully']);
        $this->emit('refreshLoans');
    }
    
    private function resetLoanForm()
    {
        $this->bookId = null;
        $this->bookCopyId = null;
        $this->userId = null;
        $this->issueDate = Carbon::now()->format('Y-m-d');
        $this->dueDate = Carbon::now()->addDays(14)->format('Y-m-d');
        $this->notes = null;
        $this->availableCopies = collect();
        $this->selectedBook = null;
        $this->showLoanModal = false;
    }
    
    private function resetReturnForm()
    {
        $this->loanId = null;
        $this->returnDate = Carbon::now()->format('Y-m-d');
        $this->conditionOnReturn = 'Good';
        $this->returnNotes = null;
        $this->fineAmount = 0;
        $this->isFinePaid = false;
        $this->showReturnModal = false;
    }
} 