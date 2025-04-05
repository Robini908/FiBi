<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\LibraryBook;
use App\Models\BookCategory;
use App\Models\BookAuthor;
use App\Models\BookCopy;
use App\Models\BookLoan;
use App\Models\BookReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LibraryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display books management page for librarians and admin
     *
     * @return \Illuminate\View\View
     */
    public function books()
    {
        // Role-based access is handled in the route middleware
        return view('pages.library.books');
    }

    /**
     * Export books to Excel
     *
     * @return StreamedResponse
     */
    public function exportBooks()
    {
        // Role-based access is handled in the route middleware
        $books = LibraryBook::with(['category', 'authors'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="books-' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($books) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Title', 'Subtitle', 'ISBN', 'ISBN-13', 'Category', 'Authors', 'Publisher', 
                'Publication Date', 'Edition', 'Pages', 'Language', 'Dewey Decimal', 'Call Number',
                'Replacement Cost', 'Status', 'Featured', 'Reference Only'
            ]);
            
            // Add book data
            foreach ($books as $book) {
                $authors = $book->authors->pluck('full_name')->implode(', ');
                
                fputcsv($file, [
                    $book->id,
                    $book->title,
                    $book->subtitle,
                    $book->isbn,
                    $book->isbn13,
                    $book->category ? $book->category->name : 'Uncategorized',
                    $authors,
                    $book->publisher,
                    $book->publication_date,
                    $book->edition,
                    $book->pages,
                    $book->language,
                    $book->dewey_decimal,
                    $book->call_number,
                    $book->replacement_cost,
                    $book->is_active ? 'Active' : 'Inactive',
                    $book->is_featured ? 'Yes' : 'No',
                    $book->is_reference_only ? 'Yes' : 'No'
                ]);
            }
            
            fclose($file);
        };
        
        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Display categories management page for librarians and admin
     *
     * @return \Illuminate\View\View
     */
    public function categories()
    {
        // Check if user is librarian or admin
        if (!Auth::user()->hasAnyRole(['librarian', 'admin', 'super_admin'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page');
        }

        return view('pages.library.categories');
    }

    /**
     * Display authors management page for librarians and admin
     *
     * @return \Illuminate\View\View
     */
    public function authors()
    {
        // Check if user is librarian or admin
        if (!Auth::user()->hasAnyRole(['librarian', 'admin', 'super_admin'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page');
        }

        return view('pages.library.authors');
    }

    /**
     * Display inventory management page for librarians and admin
     *
     * @return \Illuminate\View\View
     */
    public function inventory()
    {
        // Check if user is librarian or admin
        if (!Auth::user()->hasAnyRole(['librarian', 'admin', 'super_admin'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page');
        }

        return view('pages.library.inventory');
    }

    /**
     * Display book loans management page for librarians and admin
     *
     * @return \Illuminate\View\View
     */
    public function loans()
    {
        // Check if user is librarian or admin
        if (!Auth::user()->hasAnyRole(['librarian', 'admin', 'super_admin'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page');
        }

        return view('pages.library.loans');
    }

    /**
     * Display book reservations management page for librarians and admin
     *
     * @return \Illuminate\View\View
     */
    public function reservations()
    {
        // Check if user is librarian or admin
        if (!Auth::user()->hasAnyRole(['librarian', 'admin', 'super_admin'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page');
        }

        return view('pages.library.reservations');
    }

    /**
     * Display book requests management page for librarians and admin
     *
     * @return \Illuminate\View\View
     */
    public function bookRequests()
    {
        // Role-based access is handled in the route middleware
        return view('pages.library.book-requests');
    }

    /**
     * Display library reports page for librarians and admin
     *
     * @return \Illuminate\View\View
     */
    public function reports()
    {
        // Check if user is librarian or admin
        if (!Auth::user()->hasAnyRole(['librarian', 'admin', 'super_admin'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page');
        }

        return view('pages.library.reports');
    }

    /**
     * Display book catalog for all authenticated users
     *
     * @return \Illuminate\View\View
     */
    public function catalog()
    {
        return view('pages.library.catalog');
    }

    /**
     * Display books borrowed by the student
     *
     * @return \Illuminate\View\View
     */
    public function myBooks()
    {
        // Check if user is student
        if (!Auth::user()->hasRole('student')) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page');
        }

        return view('pages.library.my-books');
    }

    /**
     * Display books borrowed by the parent's children
     *
     * @return \Illuminate\View\View
     */
    public function myChildrenBooks()
    {
        // Check if user is parent
        if (!Auth::user()->hasRole('parent')) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page');
        }

        return view('pages.library.my-children-books');
    }
} 