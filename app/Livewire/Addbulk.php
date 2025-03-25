<?php

namespace App\Livewire;

use Throwable;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use App\Models\StudentRecord;
use Livewire\WithFileUploads;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Spatie\LivewireFilepond\WithFilePond;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;

class Addbulk extends Component
{
    use WithFileUploads, LivewireAlert;
    use WithFilePond;

    public $file;
    public $uploadProgress = 0;
    public $importResults = null;
    public $isProcessing = false;

    protected function getListeners()
    {
        return [
            'livewire-upload-progress' => 'handleUploadProgress',
            'livewire-upload-finished' => 'handleUploadFinished',
            'livewire-upload-error' => 'handleUploadError',
        ];
    }

    public function handleUploadProgress($progress)
    {
        $this->uploadProgress = $progress;
        $this->dispatch('fileUploadProgress', $progress);
    }

    public function handleUploadFinished()
    {
        $this->uploadProgress = 100;
        $this->dispatch('fileUploadProgress', 100);
    }

    public function handleUploadError()
    {
        $this->notify('Upload failed. Please try again.', 'error');
    }

    public function processImport()
    {
        if (!$this->file) {
            $this->notify('Please select a file to import.', 'error');
            return;
        }

        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $this->isProcessing = true;
        $this->importResults = null;

        try {
            // Initialize results arrays
            $successResults = [];
            $errorResults = [];
            
            // Process the Excel/CSV file
            $import = new StudentsImport;
            Excel::import($import, $this->file->path());
            
            // If we have successful imports
            if (method_exists($import, 'getSuccessful') && !empty($import->getSuccessful())) {
                foreach ($import->getSuccessful() as $rowNumber => $studentData) {
                    $successResults[$rowNumber] = $studentData;
                }
            }
            
            // If we have failures
            if (method_exists($import, 'failures') && !empty($import->failures())) {
                foreach ($import->failures() as $failure) {
                    $rowNumber = $failure->row();
                    $rowErrors = $failure->errors();
                    $rowData = $failure->values();
                    
                    $errorResults[$rowNumber] = [
                        'errors' => $rowErrors,
                        'row_data' => $rowData
                    ];
                }
            }
            
            // Set the import results
            $this->importResults = [
                'success' => $successResults,
                'errors' => $errorResults
            ];

            // If no errors, show a success message
            if (empty($errorResults)) {
                $this->notify(count($successResults) . ' students were successfully imported!', 'success');
            } else {
                // If we have errors, show a warning message
                $this->notify('Import completed with ' . count($errorResults) . ' errors. Please review the error report.', 'warning');
            }
        } catch (ExcelValidationException $e) {
            $this->handleImportException($e, 'Validation error occurred:');
        } catch (ValidationException $e) {
            $this->handleImportException($e, 'Validation error:');
        } catch (Throwable $e) {
            $this->handleImportException($e, 'An unexpected error occurred:');
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Handle exceptions from the import process
     */
    private function handleImportException($exception, $prefix)
    {
        Log::error($prefix . ' ' . $exception->getMessage(), [
            'exception' => $exception,
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ]);
        
        $this->notify("{$prefix} {$exception->getMessage()}", 'error');
    }

    /**
     * Show a notification toast
     */
    private function notify($message, $type = 'info')
    {
        $this->alert($type, $message, [
            'position' => 'top-end',
            'timer' => 5000,
            'toast' => true,
        ]);
    }

    /**
     * Clear the import results
     */
    public function clearImportResults()
    {
        $this->importResults = null;
        $this->file = null;
        $this->notify('Import results cleared', 'info');
    }

    /**
     * Download a template file for the student import
     */
    public function downloadTemplate()
    {
        // Generate and return the template file
        return Response::download(public_path('downloads/student_import_template.xlsx'));
    }

    /**
     * Download an error report for failed imports
     */
    public function downloadErrorReport()
    {
        if (!isset($this->importResults['errors']) || empty($this->importResults['errors'])) {
            $this->notify('No errors to download', 'error');
                return;
            }

        try {
            // Create a temporary file to store the error report
            $tempFile = tempnam(sys_get_temp_dir(), 'error_report_');
            
            // Create the Excel file with error details
            Excel::store(new \App\Exports\ImportErrorsExport($this->importResults['errors']), basename($tempFile), null, \Maatwebsite\Excel\Excel::XLSX);
            
            // Return the file as a download
            return response()->download($tempFile, 'import_errors_' . date('Y-m-d_H-i-s') . '.xlsx')
                ->deleteFileAfterSend(true);
        } catch (Throwable $e) {
            Log::error('Error generating error report: ' . $e->getMessage());
            $this->notify('Could not generate error report. Please try again.', 'error');
        }
    }

    public function render()
    {
        return view('livewire.addbulk');
    }
}
