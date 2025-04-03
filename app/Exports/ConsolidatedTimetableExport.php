<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class ConsolidatedTimetableExport implements FromView, WithTitle, WithStyles, WithColumnWidths, ShouldAutoSize, WithEvents
{
    protected $consolidatedData;
    
    /**
     * Constructor
     *
     * @param array $consolidatedData
     */
    public function __construct($consolidatedData)
    {
        $this->consolidatedData = $consolidatedData;
    }
    
    /**
     * @return View
     */
    public function view(): View
    {
        return view('livewire.timetable.exports.consolidated-excel', [
            'consolidatedData' => $this->consolidatedData
        ]);
    }
    
    /**
     * @return string
     */
    public function title(): string
    {
        $groupBy = ucfirst($this->consolidatedData['group_by'] ?? 'Consolidated');
        return "Consolidated Timetable ({$groupBy})";
    }
    
    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25,  // Entity column (Teacher/Class/Subject)
            'B' => 15,  // Period column
        ];
    }
    
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row styles
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            
            // Header cells
            'A1:Z1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F0F0F0']
                ]
            ],
            
            // All cells
            'A1:Z100' => [
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
                ]
            ],
        ];
    }
    
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Auto-size all columns for better readability
                foreach (range('A', 'Z') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                
                // Apply borders to all cells with content
                $highestRow = $sheet->getHighestDataRow();
                $highestCol = $sheet->getHighestDataColumn();
                
                $range = 'A1:' . $highestCol . $highestRow;
                $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                );
                
                // Conflict highlighting - find and color conflict cells
                if (isset($this->consolidatedData['highlight_conflicts']) && 
                    $this->consolidatedData['highlight_conflicts']) {
                        
                    // We'll add this logic if needed, as it's harder to implement in a view-based export
                    // Custom logic would need to be implemented here to identify conflict cells
                }
            }
        ];
    }
} 