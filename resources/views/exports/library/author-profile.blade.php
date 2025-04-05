<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Author Profile - {{ $author->name }}</title>
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
            margin-bottom: 5px;
        }
        .author-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-subtitle {
            font-size: 11pt;
            margin-bottom: 20px;
        }
        .author-image {
            float: left;
            margin-right: 15px;
            margin-bottom: 10px;
            max-width: 150px;
            border: 1px solid #ddd;
        }
        .author-info {
            margin-bottom: 15px;
        }
        .author-details {
            margin-left: 165px; /* To account for the image width + margin */
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #4F7942; /* Forest green */
            border-bottom: 1px solid #4F7942;
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
        .primary-book {
            font-weight: bold;
            color: #4F7942;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ config('app.name') }}</div>
        <div class="report-title">Author Profile</div>
        <div class="report-subtitle">
            Generated on: {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}
        </div>
    </div>

    <div class="author-info clearfix">
        @if($author->image_path)
            <img src="{{ Storage::url($author->image_path) }}" alt="{{ $author->name }}" class="author-image">
        @endif
        
        <div class="author-details">
            <h1 class="author-name">{{ $author->name }}</h1>
            
            <p><strong>Nationality:</strong> {{ $author->nationality ?? 'Not specified' }}</p>
            
            @if($author->birth_date)
                <p>
                    <strong>Born:</strong> {{ $author->birth_date->format('F d, Y') }}
                    @if($author->death_date)
                        <br><strong>Died:</strong> {{ $author->death_date->format('F d, Y') }}
                        ({{ $author->birth_date->diffInYears($author->death_date) }} years old)
                    @else
                        ({{ $author->birth_date->age }} years old)
                    @endif
                </p>
            @endif
            
            @if($author->website || $author->email)
                <p>
                    @if($author->website)
                        <strong>Website:</strong> {{ $author->website }}<br>
                    @endif
                    
                    @if($author->email)
                        <strong>Email:</strong> {{ $author->email }}
                    @endif
                </p>
            @endif
            
            <p>
                <strong>Status:</strong> 
                @if($author->is_active && $author->is_featured)
                    Active, Featured
                @elseif($author->is_active)
                    Active
                @elseif($author->is_featured)
                    Featured
                @else
                    Inactive
                @endif
            </p>
            
            <p><strong>Total Books:</strong> {{ $author->books->count() }}</p>
        </div>
    </div>

    @if($author->biography)
        <div class="section-title">Biography</div>
        <div>
            {{ $author->biography }}
        </div>
    @endif

    @if($author->books->count() > 0)
        <div class="section-title">Books</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 45%">Title</th>
                    <th style="width: 15%">Publication Date</th>
                    <th style="width: 15%">ISBN</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 10%">Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($author->books as $index => $book)
                    <tr @if($book->pivot->is_primary) class="primary-book" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $book->title }}
                            @if($book->subtitle)
                                <br><span style="font-size: 0.9em; color: #666;">{{ $book->subtitle }}</span>
                            @endif
                        </td>
                        <td>{{ $book->publication_date ? $book->publication_date->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $book->isbn ?? 'N/A' }}</td>
                        <td>{{ $book->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>{{ $book->pivot->is_primary ? 'Primary Author' : 'Co-Author' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="section-title">Books</div>
        <p>No books associated with this author.</p>
    @endif

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