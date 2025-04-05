<?php

namespace App\Http\Livewire\Library\Dashboard;

use App\Helpers\Qs;
use App\Models\BookCopy;
use App\Models\BookLoan;
use App\Models\BookReservation;
use App\Models\LibraryBook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LibraryDashboard extends Component
{
    public $totalBooks;
    public $totalCopies;
    public $availableCopies;
    public $activeLoans;
    public $overdueLoans;
    public $pendingReservations;
    public $totalAuthors;
    public $totalCategories;
    
    // For students/parents
    public $myActiveLoans;
    public $myOverdueLoans;
    public $myPendingReservations;
    
    // For featured books
    public $featuredBooks;
    
    // Charts data
    public $loansChartData;
    public $categoriesChartData;
    
    protected $listeners = [
        'refreshDashboard' => '$refresh'
    ];

    public function mount()
    {
        $this->loadData();
        $this->loadFeaturedBooks();
        
        if (Qs::isLibrarian() || Qs::isAdministrator()) {
            $this->loadChartData();
        }
    }
    
    private function loadData()
    {
        // General library statistics visible to all roles
        $this->totalBooks = LibraryBook::count();
        $this->totalCopies = BookCopy::count();
        $this->availableCopies = BookCopy::where('status', 'Available')->count();
        
        // Statistics visible only to library staff
        if (Qs::isLibrarian() || Qs::isAdministrator()) {
            $this->activeLoans = BookLoan::where('status', 'Active')->count();
            $this->overdueLoans = BookLoan::where('status', 'Active')
                ->where('due_date', '<', Carbon::now())
                ->count();
            $this->pendingReservations = BookReservation::whereIn('status', ['Pending', 'Approved'])->count();
            $this->totalAuthors = \App\Models\BookAuthor::count();
            $this->totalCategories = \App\Models\BookCategory::count();
        }
        
        // User-specific statistics
        if (Qs::isStudent() || Qs::isTeacher()) {
            $this->loadUserSpecificData(Auth::id());
        }
    }
    
    private function loadUserSpecificData($userId)
    {
        $this->myActiveLoans = BookLoan::where('user_id', $userId)
            ->where('status', 'Active')
            ->count();
            
        $this->myOverdueLoans = BookLoan::where('user_id', $userId)
            ->where('status', 'Active')
            ->where('due_date', '<', Carbon::now())
            ->count();
            
        $this->myPendingReservations = BookReservation::where('user_id', $userId)
            ->whereIn('status', ['Pending', 'Approved'])
            ->count();
    }
    
    private function loadFeaturedBooks()
    {
        $this->featuredBooks = LibraryBook::with(['category', 'authors'])
            ->featured()
            ->active()
            ->take(5)
            ->get();
    }
    
    private function loadChartData()
    {
        // Loans per month for the current year
        $loansData = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = BookLoan::whereYear('issue_date', Carbon::now()->year)
                ->whereMonth('issue_date', $month)
                ->count();
                
            $loansData[] = [
                'month' => Carbon::create()->month($month)->format('M'),
                'count' => $count
            ];
        }
        $this->loansChartData = json_encode($loansData);
        
        // Books by category
        $categories = \App\Models\BookCategory::withCount('books')->get();
        $catData = [];
        foreach ($categories as $category) {
            $catData[] = [
                'name' => $category->name,
                'count' => $category->books_count
            ];
        }
        $this->categoriesChartData = json_encode($catData);
    }

    public function render()
    {
        // Determine which dashboard view to show based on role
        $viewName = 'livewire.library.dashboard.librarian-dashboard';
        
        if (Qs::isStudent() || Qs::isTeacher()) {
            $viewName = 'livewire.library.dashboard.student-dashboard';
        } elseif (Qs::isParent()) {
            $viewName = 'livewire.library.dashboard.parent-dashboard';
            
            // Load children's data for parent dashboard
            $children = Qs::findMyChildren(Auth::id());
            return view($viewName, [
                'children' => $children
            ]);
        }
        
        return view($viewName);
    }
} 