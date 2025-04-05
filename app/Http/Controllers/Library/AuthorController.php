<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\BookAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AuthorController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:librarian,admin,super_admin']);
    }

    /**
     * Display a listing of authors
     */
    public function index()
    {
        return view('library.authors.index');
    }

    /**
     * Show the form for creating a new author
     */
    public function create()
    {
        return view('library.authors.create');
    }

    /**
     * Show the form for editing an author
     */
    public function edit($id)
    {
        return view('library.authors.edit', ['authorId' => $id]);
    }

    /**
     * Display author details
     */
    public function show($id)
    {
        return view('library.authors.show', ['authorId' => $id]);
    }

    /**
     * Export authors list to Excel
     */
    public function exportAuthors(Request $request)
    {
        return Excel::download(new \App\Exports\AuthorsExport($request->all()), 'authors.xlsx');
    }

    /**
     * Generate author profile PDF
     */
    public function generateAuthorProfile($id)
    {
        $author = BookAuthor::with('books')->findOrFail($id);
        
        // Generate PDF using a view
        $pdf = Pdf::loadView('exports.library.author-profile', [
            'author' => $author
        ]);
        
        return $pdf->download('author_profile_' . $author->slug . '.pdf');
    }
} 