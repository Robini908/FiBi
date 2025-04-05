<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Library Inventory Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .report-subtitle {
            font-size: 11pt;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            color: #777;
        }
        .page-number {
            text-align: right;
            font-size: 9pt;
        }
        .summary {
            margin: 20px 0;
        }
        .summary-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .summary-table {
            width: 50%;
            margin-bottom: 25px;
        }
        .category-header {
            background-color: #e9ecef;
            font-weight: bold;
            padding: 8px;
            margin-top: 10px;
            border: 1px solid #ddd;
        }
        .status-available {
            color: #28a745;
        }
        .status-unavailable {
            color: #dc3545;
        }
        .status-reference {
            color: #17a2b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ config('app.name') }}</div>
        <div class="report-title">Library Inventory Report</div>
        <div class="report-subtitle">
            Generated on: {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}
            @if(isset($category) && $category)
                <br>Category: {{ $category->name }}
            @endif
        </div>
    </div>

    <div class="summary">
        <div class="summary-title">Inventory Summary</div>
        <table class="summary-table">
            <tr>
                <td>Total Books (Titles):</td>
                <td><strong>{{ $totalBooks }}</strong></td>
            </tr>
            <tr>
                <td>Total Book Copies:</td>
                <td><strong>{{ $totalCopies }}</strong></td>
            </tr>
            <tr>
                <td>Available Copies:</td>
                <td><strong>{{ $availableCopies }}</strong></td>
            </tr>
            <tr>
                <td>Borrowed Copies:</td>
                <td><strong>{{ $borrowedCopies }}</strong></td>
            </tr>
            <tr>
                <td>Reference Only Books:</td>
                <td><strong>{{ $referenceBooks }}</strong></td>
            </tr>
            <tr>
                <td>Categories:</td>
                <td><strong>{{ $totalCategories }}</strong></td>
            </tr>
            <tr>
                <td>Estimated Collection Value:</td>
                <td><strong>{{ $currencySymbol }}{{ number_format($totalValue, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    @if(isset($booksByCategory) && count($booksByCategory) > 0)
    <div class="category-distribution">
        <div class="summary-title">Books by Category</div>
        <table class="summary-table">
            @foreach($booksByCategory as $categoryName => $count)
            <tr>
                <td>{{ $categoryName }}</td>
                <td><strong>{{ $count }}</strong></td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Book Title</th>
                <th>ISBN</th>
                <th>Category</th>
                <th>Authors</th>
                <th>Publisher</th>
                <th>Publication Date</th>
                <th>Copies</th>
                <th>Available</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $index => $book)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $book->title }}
                        @if($book->subtitle)
                            <br><small>{{ $book->subtitle }}</small>
                        @endif
                    </td>
                    <td>{{ $book->isbn ?? 'N/A' }}</td>
                    <td>{{ $book->category->name ?? 'Uncategorized' }}</td>
                    <td>
                        @if($book->authors && $book->authors->count() > 0)
                            {{ $book->authors->pluck('name')->join(', ') }}
                        @else
                            Unknown
                        @endif
                    </td>
                    <td>{{ $book->publisher ?? 'N/A' }}</td>
                    <td>{{ $book->publication_date ? $book->publication_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $book->copies_count }}</td>
                    <td>{{ $book->copies_available }}</td>
                    <td>
                        @if($book->is_reference)
                            <span class="status-reference">Reference Only</span>
                        @elseif($book->copies_available > 0)
                            <span class="status-available">Available</span>
                        @else
                            <span class="status-unavailable">Unavailable</span>
                        @endif
                        @if(!$book->is_active)
                            <br><small>(Inactive)</small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No books found in the inventory.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>
            This report contains confidential information and is intended only for authorized personnel.
            <br>
            &copy; {{ date('Y') }} {{ config('app.name') }} - Library Management System
        </p>
    </div>

    <div class="page-number">
        Page 1 of 1
    </div>
</body>
</html> 