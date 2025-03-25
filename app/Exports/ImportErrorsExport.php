<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ImportErrorsExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $rows = [];

        foreach ($this->errors as $rowNum => $error) {
            // Handle different error structures
            if (isset($error['errors']) && is_array($error['errors'])) {
                $errorMessages = implode(', ', $error['errors']);
            } elseif (isset($error['errors']) && is_string($error['errors'])) {
                $errorMessages = $error['errors'];
            } elseif (is_string($error)) {
                $errorMessages = $error;
            } else {
                // If it's an array but doesn't have 'errors' key, collect all string values as errors
                $errorArr = [];
                foreach ($error as $key => $value) {
                    if (is_string($value) && $key !== 'row_data') {
                        $errorArr[] = $value;
                    }
                }
                $errorMessages = implode(', ', $errorArr);
            }
            
            $row = [
                'Row Number' => $rowNum,
                'Error(s)' => $errorMessages,
            ];
            
            // Add row data if available
            if (isset($error['row_data']) && is_array($error['row_data'])) {
                foreach ($error['row_data'] as $field => $value) {
                    if (is_string($value) || is_numeric($value) || is_null($value)) {
                        $row[$field] = $value;
                    } else {
                        $row[$field] = '[Complex data]';
                    }
                }
            }
            
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Get all possible headers from the data
        $headers = ['Row Number', 'Error(s)'];
        
        // Add column headers from data fields
        foreach ($this->errors as $error) {
            if (isset($error['row_data']) && is_array($error['row_data'])) {
                foreach (array_keys($error['row_data']) as $field) {
                    if (!in_array($field, $headers)) {
                        $headers[] = $field;
                    }
                }
            }
        }
        
        return $headers;
    }

    /**
     * Apply styles to the spreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headers)
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4CAF50']],
            ],
            
            // Style for all cells
            'A' => ['font' => ['bold' => true]],
            'B' => ['font' => ['color' => ['rgb' => 'FF0000']]],
        ];
    }
} 