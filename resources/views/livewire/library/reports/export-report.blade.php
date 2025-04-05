<div>
    <div class="form-group">
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <h6 class="card-title">Export Options</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Export Format:</label>
                                <select wire:model="exportFormat" class="form-control">
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Options:</label>
                                <div class="d-flex mt-2">
                                    <div class="form-check mr-3">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" wire:model="includeHeaderFooter">
                                            Include header/footer
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" wire:model="includeSummary">
                                            Include summary
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Report Title (optional):</label>
                                <input type="text" class="form-control" wire:model="reportTitle" placeholder="Custom report title...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-light" wire:click="printReport">
                            <i class="icon-printer mr-2"></i> Print
                        </button>
                        
                        <button type="button" class="btn btn-primary" wire:click="exportReport">
                            <i class="icon-file-download mr-2"></i> Export
                            <span wire:loading wire:target="exportReport" class="spinner-border spinner-border-sm ml-2"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Preview -->
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h6 class="card-title">Export Preview</h6>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="embed-responsive embed-responsive-16by9" style="height: 600px;">
                <iframe class="embed-responsive-item" id="reportPreview" src="{{ route('library.reports.preview', ['type' => $reportType]) }}?{{ http_build_query([
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'categoryId' => $categoryId,
                    'borrowerId' => $borrowerId,
                    'search' => $search
                ]) }}"></iframe>
            </div>
        </div>
    </div>

    @push('page_scripts')
    <script>
        // Update the preview when report params change
        document.addEventListener('livewire:load', function () {
            @this.on('refreshPreview', function () {
                const iframe = document.getElementById('reportPreview');
                if (iframe) {
                    const params = new URLSearchParams({
                        dateStart: @this.dateStart,
                        dateEnd: @this.dateEnd, 
                        categoryId: @this.categoryId || '',
                        borrowerId: @this.borrowerId || '',
                        search: @this.search || ''
                    });
                    
                    iframe.src = '{{ route('library.reports.preview') }}/' + @this.reportType + '?' + params.toString();
                }
            });
        });
        
        // Handle print action
        window.addEventListener('print-report', event => {
            const iframe = document.getElementById('reportPreview');
            if (iframe) {
                iframe.contentWindow.print();
            }
        });
        
        // Handle export completion
        window.addEventListener('report-exported', event => {
            Swal.fire({
                icon: 'success',
                title: 'Report Exported',
                text: event.detail.message,
                timer: 3000,
                timerProgressBar: true
            });
            
            // Download the file if available
            if (event.detail.downloadUrl) {
                window.location.href = event.detail.downloadUrl;
            }
        });
    </script>
    @endpush
</div> 