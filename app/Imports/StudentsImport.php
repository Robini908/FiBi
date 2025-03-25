<?php

namespace App\Imports;

use Hash;
use App\Models\MyClass;
use App\Models\Section;
use Maatwebsite\Excel\Validators\Failure;
use App\Models\ParentDetail;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Illuminate\Database\QueryException;

class StudentsImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation, WithBatchInserts
{
    use SkipsFailures, Importable, RegistersEventListeners;

    /**
     * @var array Track successful imports 
     */
    protected $successful = [];

    /**
     * @var int Current row being processed
     */
    protected $currentRow = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->currentRow++;
        
        try {
            // Validate mandatory fields
            $this->validateRow($row);

            // Retrieve the class ID based on the class name
            $class = MyClass::where('name', $row['class_name'])->first();
            $class_id = $class ? $class->id : null;

            if (!$class_id) {
                throw new \Exception("The class name '{$row['class_name']}' does not exist. Please enter a valid class name.");
            }

            // Retrieve the section ID based on the section name
            $section = Section::where('name', $row['section_name'])->where('my_class_id', $class_id)->first();
            $section_id = $section ? $section->id : null;

            if (!$section_id) {
                throw new \Exception("The section name '{$row['section_name']}' does not exist for the class '{$row['class_name']}'. Please enter a valid section name.");
            }

            // Validate and format the 'dob' field
            $dob = $this->formatDateOfBirth($row['dob']);

            // Add the kcpe field (ensure it exists in the Excel row)
            $kcpe = $row['kcpe'] ?? null;

            // Create or retrieve parent details
            $parentDetail = null;
            if (!empty($row['parent_id_no'])) {
                $parentDetail = ParentDetail::updateOrCreate(
                    ['parent_id_no' => $row['parent_id_no']],
                    [
                        'parent_first_name' => $row['parent_first_name'] ?? '',
                        'parent_middle_name' => $row['parent_middle_name'] ?? '',
                        'parent_last_name' => $row['parent_last_name'] ?? '',
                        'parent_phone_number' => $this->formatPhoneNumber($row['parent_phone_number'] ?? ''),
                        'parent_email' => $row['parent_email'] ?? '',
                        'parent_password' => !empty($row['parent_password'])
                            ? encrypt($row['parent_password'])
                            : encrypt('default_password') // Default password if none provided
                    ]
                );
            }

            // Create the student record
            $student = new StudentRecord([
                'adm_no' => $row['adm_no'],
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'] ?? null,
                'last_name' => $row['last_name'],
                'gender' => $row['gender'],
                'dob' => $dob, // Parsed and formatted date
                'my_class_id' => $class_id,
                'section_id' => $section_id,
                'year_admitted' => $row['year_admitted'],
                'email' => $row['email'] ?? null,
                'phone' => $this->formatPhoneNumber($row['phone'] ?? null),
                'kcpe' => $kcpe,
                'parent_id_no' => $parentDetail ? $parentDetail->parent_id_no : null // Link the student record to the parent
            ]);

            // Store the successful import
            $this->successful[$this->currentRow] = [
                'name' => $row['first_name'] . ' ' . $row['last_name'],
                'admission_number' => $row['adm_no'],
                'class' => $row['class_name'] . ' - ' . $row['section_name'],
                'gender' => $row['gender'],
                'dob' => $dob,
                'email' => $row['email'] ?? 'N/A',
                'phone' => $row['phone'] ?? 'N/A'
            ];

            return $student;
        } catch (QueryException $e) {
            $this->handleQueryException($e, $row);
            return null;
        } catch (\Exception $e) {
            $this->handleGeneralException($e, $row);
            return null;
        }
    }

    /**
     * Get all the successful imports
     * 
     * @return array
     */
    public function getSuccessful(): array
    {
        return $this->successful;
    }

    /**
     * Define validation rules
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'adm_no' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|in:male,female,Male,Female',
            'dob' => 'required',
            'class_name' => 'required|string',
            'section_name' => 'required|string',
            'year_admitted' => 'required|integer',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'kcpe' => 'nullable',
        ];
    }

    /**
     * Define batch size
     * 
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    private function validateRow(array $row)
    {
        $requiredFields = ['class_name', 'section_name', 'adm_no', 'first_name', 'last_name', 'gender', 'dob', 'year_admitted'];

        foreach ($requiredFields as $field) {
            if (empty($row[$field])) {
                throw new \Exception("The field '{$field}' is required and cannot be empty. Please ensure all mandatory fields are filled in.");
            }
        }

        if (isset($row['email']) && !filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("The email address '{$row['email']}' is not valid. Please enter a valid email address.");
        }
    }

    private function formatDateOfBirth($dob)
    {
        if (empty($dob)) {
            return null;
        }

        try {
            if (preg_match('/=DATE\((\d+),(\d+),(\d+)\)/i', $dob, $matches)) {
                // Convert Excel formula "=DATE(year, month, day)" to a valid date
                $year = $matches[1];
                $month = $matches[2];
                $day = $matches[3];
                return \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
            } else {
                // Attempt to parse as a standard date format
                return \Carbon\Carbon::parse($dob)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            throw new \Exception("The date format for 'dob' is invalid: {$dob}. Please use a valid date format.");
        }
    }

    private function formatPhoneNumber($phone)
    {
        // Check if the phone number has been altered by Excel and append a leading zero if necessary
        if (preg_match('/^1\d{8,14}$/', $phone)) {
            // Correct the format by adding a leading zero
            return '0' . substr($phone, 1);
        }

        return $phone;
    }

    private function handleQueryException(QueryException $e, array $row)
    {
        $errorMessage = "An unexpected error occurred: " . $e->getMessage();

        // Log the error for further analysis
        Log::error($errorMessage);

        // Extract useful information from the QueryException
        $userFriendlyMessage = "Oops! Something went wrong with the data import.";
        if ($e->errorInfo[1] == 1062) {
            $userFriendlyMessage .= " It appears there is a duplicate entry. Please ensure that each admission number is unique.";
        }

        // Create a Failure object
        $failure = new Failure(
            $this->currentRow, // Current row number
            'Database Error',
            [$userFriendlyMessage],
            $row
        );

        // Add the Failure object to failures
        $this->failures[] = $failure;
    }

    private function handleGeneralException(\Exception $e, array $row)
    {
        $errorMessage = "There was an error processing the row: " . $e->getMessage();

        // Log the error for further analysis
        Log::error($errorMessage);

        // Create a Failure object
        $failure = new Failure(
            $this->currentRow, // Current row number
            'General Error',
            [$e->getMessage()],
            $row
        );

        // Add the Failure object to failures
        $this->failures[] = $failure;
    }
}
