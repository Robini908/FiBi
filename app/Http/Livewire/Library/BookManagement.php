    // UI State
    public $activeTab = 'basic';
    public $book = null;
    public $canCreate = false;
    public $canExport = false;
    public $formModalOpen = false;
    public $deleteModalOpen = false;
    
    // Validation rules
    protected $rules = [
        // Basic tab
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'isbn' => 'nullable|string|max:20',
        'isbn13' => 'nullable|string|max:20',
        'category_id' => 'required|exists:book_categories,id',
        'language' => 'nullable|string|max:50',
        'description' => 'nullable|string',
        'newCoverImage' => 'nullable|image|max:2048',
            
        // Details tab
        'publisher' => 'nullable|string|max:255',
        'published_date' => 'nullable|date',
        'edition' => 'nullable|string|max:50',
        'pages' => 'nullable|integer|min:1',
        'dewey_decimal' => 'nullable|string|max:20',
        'call_number' => 'nullable|string|max:50',
        'replacement_cost' => 'nullable|numeric|min:0',
        'table_of_contents' => 'nullable|string',
        'is_reference_only' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'copies_available' => 'required|integer|min:0',
        'total_copies' => 'required|integer|min:0|gte:copies_available',
            
        // Authors tab
    ]; 