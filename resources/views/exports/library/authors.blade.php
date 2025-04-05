<table>
    <thead>
        <tr>
            <th colspan="7">Authors List - {{ config('app.name') }} - {{ $date }}</th>
        </tr>
        <tr>
            <th>Name</th>
            <th>Nationality</th>
            <th>Birth Date</th>
            <th>Status</th>
            <th>Books Count</th>
            <th>Website</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        @forelse($authors as $author)
            <tr>
                <td>{{ $author->name }}</td>
                <td>{{ $author->nationality ?? 'N/A' }}</td>
                <td>{{ $author->birth_date ? $author->birth_date->format('Y-m-d') : 'N/A' }}</td>
                <td>
                    @if($author->is_active && $author->is_featured)
                        Active, Featured
                    @elseif($author->is_active)
                        Active
                    @elseif($author->is_featured)
                        Featured
                    @else
                        Inactive
                    @endif
                </td>
                <td>{{ $author->books_count }}</td>
                <td>{{ $author->website ?? 'N/A' }}</td>
                <td>{{ $author->email ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No authors found</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">
                Total Authors: {{ $authors->count() }}
                @if(isset($filters['search']) && $filters['search'])
                | Search: "{{ $filters['search'] }}"
                @endif
                @if(isset($filters['featuredOnly']) && $filters['featuredOnly'])
                | Featured Only
                @endif
                @if(isset($filters['activeOnly']) && $filters['activeOnly'])
                | Active Only
                @endif
                @if(isset($filters['withBooksOnly']) && $filters['withBooksOnly'])
                | With Books Only
                @endif
            </td>
        </tr>
    </tfoot>
</table> 