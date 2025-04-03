<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TeacherAssignmentsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    use Exportable;
    
    protected $query;
    
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }
    
    /**
     * @return Builder
     */
    public function query()
    {
        return $this->query->with(['teacher', 'subject', 'myClass', 'section']);
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Teacher Name',
            'Subject',
            'Class',
            'Section',
            'Academic Year',
            'Term',
            'Primary',
            'Status',
            'Notes',
            'Created Date'
        ];
    }
    
    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->teacher->name ?? 'Unknown',
            $row->subject->subject_name ?? 'Unknown',
            $row->myClass->name ?? 'Unknown',
            $row->section->name ?? 'N/A',
            $row->academic_year_id,
            $row->academic_term,
            $row->is_primary ? 'Yes' : 'No',
            $row->is_active ? 'Active' : 'Inactive',
            $row->notes,
            $row->created_at->format('Y-m-d')
        ];
    }
    
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Teacher Assignments';
    }
    
    /**
     * Apply styles to the sheet
     *
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Style for the Primary column
        $lastRow = $sheet->getHighestRow();
        $primaryColumn = 'G';
        $statusColumn = 'H';
        
        for ($row = 2; $row <= $lastRow; $row++) {
            // Style for Primary column (Yes/No)
            if ($sheet->getCell($primaryColumn . $row)->getValue() === 'Yes') {
                $sheet->getStyle($primaryColumn . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7'], // Yellow-100
                    ],
                    'font' => [
                        'color' => ['rgb' => '92400E'], // Yellow-800
                    ],
                ]);
            }
            
            // Style for Status column (Active/Inactive)
            if ($sheet->getCell($statusColumn . $row)->getValue() === 'Active') {
                $sheet->getStyle($statusColumn . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DCFCE7'], // Green-100
                    ],
                    'font' => [
                        'color' => ['rgb' => '166534'], // Green-800
                    ],
                ]);
            } else {
                $sheet->getStyle($statusColumn . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'], // Gray-100
                    ],
                    'font' => [
                        'color' => ['rgb' => '4B5563'], // Gray-600
                    ],
                ]);
            }
        }
        
        // Set all cells to wrap text
        $sheet->getStyle('A1:J' . $lastRow)->getAlignment()->setWrapText(true);
        
        // Add zebra striping
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9FAFB'], // Gray-50
                    ],
                ]);
            }
        }
    }
} 