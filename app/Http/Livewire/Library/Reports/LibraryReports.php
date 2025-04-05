<?php

namespace App\Http\Livewire\Library\Reports;

use App\Helpers\Qs;
use App\Models\BookCopy;
use App\Models\BookLoan;
use App\Models\BookReservation;
use App\Models\LibraryBook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LibraryReports extends Component
{
    use WithPagination;
    
    public $reportType = 'overdue';
    public $dateStart;
    public $dateEnd;
    public $categoryId;
    public $exportFormat = 'pdf';
    public $perPage = 50;
    
    // Filters
    public $search = '';
    
    // For borrower report
    public $borrowerId;
    
    public function mount()
    {
        // Set default date range to current month
        $this->dateStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
    }
    
    public function render()
    {
        // Only librarians, admins and accountants can access reports
        if (!Qs::isLibrarian() && !Qs::isAdministrator() && !Qs::isAccountant()) {
            return view('livewire.library.reports.unauthorized');
        }
        
        $data = [];
        
        switch ($this->reportType) {
            case 'overdue':
                $data = $this->getOverdueReport();
                break;
            case 'circulation':
                $data = $this->getCirculationReport();
                break;
            case 'inventory':
                $data = $this->getInventoryReport();
                break;
            case 'popular':
                $data = $this->getPopularBooksReport();
                break;
            case 'borrower':
                $data = $this->getBorrowerReport();
                break;
            case 'fines':
                $data = $this->getFinesReport();
                break;
        }
        
        $categories = \App\Models\BookCategory::active()->ordered()->get();
        
        return view('livewire.library.reports.library-reports', [
            'reportData' => $data,
            'categories' => $categories,
        ]);
    }
    
    public function updatedReportType()
    {
        $this->resetPage();
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function getOverdueReport()
    {
        return BookLoan::with(['bookCopy.book', 'borrower'])
            ->where('status', 'Active')
            ->where('due_date', '<', Carbon::now())
            ->when($this->search, function($query) {
                return $query->whereHas('borrower', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('bookCopy.book', function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('due_date', 'asc')
            ->paginate($this->perPage);
    }
    
    public function getCirculationReport()
    {
        return BookLoan::with(['bookCopy.book', 'borrower'])
            ->whereBetween('issue_date', [$this->dateStart, $this->dateEnd])
            ->when($this->search, function($query) {
                return $query->whereHas('borrower', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('bookCopy.book', function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('issue_date', 'desc')
            ->paginate($this->perPage);
    }
    
    public function getInventoryReport()
    {
        return LibraryBook::with(['category', 'copies'])
            ->when($this->categoryId, function($query) {
                return $query->where('category_id', $this->categoryId);
            })
            ->when($this->search, function($query) {
                return $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn13', 'like', '%' . $this->search . '%')
                      ->orWhere('publisher', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('title')
            ->paginate($this->perPage);
    }
    
    public function getPopularBooksReport()
    {
        // Get the most popular books by loan count
        $books = LibraryBook::withCount(['loans' => function($query) {
                if ($this->dateStart && $this->dateEnd) {
                    $query->whereBetween('issue_date', [$this->dateStart, $this->dateEnd]);
                }
            }])
            ->with(['category'])
            ->having('loans_count', '>', 0)
            ->when($this->search, function($query) {
                return $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn13', 'like', '%' . $this->search . '%')
                      ->orWhere('publisher', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('loans_count', 'desc')
            ->paginate($this->perPage);
            
        return $books;
    }
    
    public function getBorrowerReport()
    {
        if (!$this->borrowerId) {
            return collect();
        }
        
        return BookLoan::with(['bookCopy.book'])
            ->where('user_id', $this->borrowerId)
            ->when($this->dateStart && $this->dateEnd, function($query) {
                return $query->whereBetween('issue_date', [$this->dateStart, $this->dateEnd]);
            })
            ->when($this->search, function($query) {
                return $query->whereHas('bookCopy.book', function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('issue_date', 'desc')
            ->paginate($this->perPage);
    }
    
    public function getFinesReport()
    {
        return BookLoan::with(['bookCopy.book', 'borrower'])
            ->where('fine_amount', '>', 0)
            ->when($this->search, function($query) {
                return $query->whereHas('borrower', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('bookCopy.book', function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('is_fine_paid')
            ->orderBy('fine_amount', 'desc')
            ->paginate($this->perPage);
    }
    
    public function exportReport()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator() && !Qs::isAccountant()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to export reports']);
            return;
        }
        
        // Dispatch event to generate report in the selected format
        $this->dispatchBrowserEvent('export-report', [
            'reportType' => $this->reportType,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
            'categoryId' => $this->categoryId,
            'borrowerId' => $this->borrowerId,
            'exportFormat' => $this->exportFormat,
            'search' => $this->search
        ]);
    }
    
    public function printReport()
    {
        if (!Qs::isLibrarian() && !Qs::isAdministrator() && !Qs::isAccountant()) {
            $this->dispatchBrowserEvent('toast-error', ['message' => 'You do not have permission to print reports']);
            return;
        }
        
        $this->dispatchBrowserEvent('print-report');
    }
} 