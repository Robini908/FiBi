<div class="bg-gray-100 p-4 rounded-lg mb-4">
    <h3 class="text-lg font-medium mb-2">Debug Information</h3>
    <div class="overflow-x-auto">
        <pre class="text-xs bg-white p-4 rounded border">
            @if(isset($importResults) && is_array($importResults))
                Import Results Structure:
                @json($importResults, JSON_PRETTY_PRINT)
            @else
                No import results available.
            @endif
        </pre>
    </div>
</div> 