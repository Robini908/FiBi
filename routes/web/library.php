<?php

use App\Http\Controllers\Library\LibraryController;
use Illuminate\Support\Facades\Route;

// Library Routes with auth middleware
Route::middleware(['auth', 'tenant'])->prefix('library')->name('library.')->group(function () {
    // Dashboard - accessible to all authenticated users
    Route::get('/dashboard', function () {
        return view('library.dashboard');
    })->name('dashboard');
    
    // Book Catalog - accessible to all authenticated users
    Route::get('/catalog', [LibraryController::class, 'catalog'])->name('catalog');
    
    // Student Books - accessible only to students
    Route::get('/my-books', [LibraryController::class, 'myBooks'])
        ->middleware('role:student')
        ->name('my-books');
    
    // Parent Children's Books - accessible only to parents
    Route::get('/my-children-books', [LibraryController::class, 'myChildrenBooks'])
        ->middleware('role:parent')
        ->name('my-children-books');
    
    // Book Details - accessible to all authenticated users
    Route::get('/books/{id}/details', function ($id) {
        return view('library.books.show', ['bookId' => $id]);
    })->name('books.show');
    
    // Library Management routes - accessible only to librarians and administrators
    Route::middleware(['role:librarian,admin,super_admin'])->group(function () {
        // Books Management
        Route::get('/books', function () {
            return view('library.books.index');
        })->name('books.index');
        
        Route::get('/books/create', function () {
            return view('library.books.create');
        })->name('books.create');
        
        Route::get('/books/{id}/edit', function ($id) {
            return view('library.books.edit', ['bookId' => $id]);
        })->name('books.edit');
        
        // Author Management
        Route::get('/authors', [LibraryController::class, 'authors'])->name('authors');
        
        Route::get('/authors/create', function () {
            return view('library.authors.create');
        })->name('authors.create');
        
        Route::get('/authors/{id}/edit', function ($id) {
            return view('library.authors.edit', ['authorId' => $id]);
        })->name('authors.edit');
        
        // Category Management
        Route::get('/categories', function () {
            return view('library.categories.index');
        })->name('categories.index');
        
        Route::get('/categories/create', function () {
            return view('library.categories.create');
        })->name('categories.create');
        
        Route::get('/categories/{id}/edit', function ($id) {
            return view('library.categories.edit', ['categoryId' => $id]);
        })->name('categories.edit');
        
        // Circulation - Loans
        Route::get('/circulation/loans', function () {
            return view('library.circulation.loans');
        })->name('loans.index');
        
        // Circulation - Reservations
        Route::get('/circulation/reservations', function () {
            return view('library.circulation.reservations');
        })->name('reservations.index');
        
        // Circulation - Overdue Books
        Route::get('/circulation/overdue', function () {
            return view('library.circulation.overdue');
        })->name('circulation.overdue');
        
        // Inventory
        Route::get('/inventory', [LibraryController::class, 'inventory'])->name('inventory');
        
        // Library Settings
        Route::get('/settings', function () {
            return view('library.settings');
        })->name('settings');
        
        // Export
        Route::get('/export/books', [LibraryController::class, 'exportBooks'])->name('export.books');
    });
    
    // Reports - accessible to librarians, admins, and accountants
    Route::middleware(['role:librarian,admin,super_admin,accountant'])->group(function () {
        Route::get('/reports', [LibraryController::class, 'reports'])->name('reports');
        
        // Report exports
        Route::get('/reports/export/{type}', function ($type) {
            return redirect()->route('library.reports')->with('message', 'Report exported successfully');
        })->name('reports.export');
    });
}); 