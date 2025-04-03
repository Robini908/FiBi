<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class TimetableExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    protected $data;
    
    /**
     * Create a new export instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $timetable = $this->data['timetable'];
        $periods = $this->data['periods'];
        $visibleDays = $this->data['visibleDays'];
        $timetableMatrix = $this->data['timetableMatrix'];
        
        $rows = new Collection();
        
        foreach ($periods as $period) {
            $row = [
                $period->period_name . "\n" . date('h:i A', strtotime($period->start_time)) . ' - ' . date('h:i A', strtotime($period->end_time))
            ];
            
            foreach ($visibleDays as $day) {
                $entry = $timetableMatrix[$day][$period->id] ?? null;
                
                if ($entry) {
                    $cellContent = $entry->subject->subject_name ?? 'No Subject';
                    
                    if (isset($entry->teacher)) {
                        $cellContent .= "\n" . $entry->teacher->name;
                    }
                    
                    if ($entry->classroom) {
                        $cellContent .= "\n" . $entry->classroom;
                    }
                    
                    $row[] = $cellContent;
                } else {
                    $row[] = 'No class scheduled';
                }
            }
            
            $rows->push($row);
        }
        
        return $rows;
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        $visibleDays = $this->data['visibleDays'];
        $headings = ['Time Slot'];
        
        foreach ($visibleDays as $day) {
            $headings[] = ucfirst($day);
        }
        
        return $headings;
    }
    
    /**
     * @param  \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet  $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $lastColumn = chr(65 + count($this->data['visibleDays']));
        $lastRow = count($this->data['periods']) + 1;
        
        // Title styling
        $sheet->mergeCells('A1:' . $lastColumn . '1');
        $sheet->setCellValue('A1', $this->getTitle());
        
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '2D6A4F']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F8FFF8']
            ]
        ]);
        
        // Subtitles with timetable info
        $sheet->mergeCells('A2:' . $lastColumn . '2');
        
        $timetable = $this->data['timetable'];
        $section = $this->data['section'];
        
        $subtitleText = 'Class: ' . optional($timetable->myClass)->name;
        if ($section) {
            $subtitleText .= ' | Section: ' . $section->name;
        }
        $subtitleText .= ' | Academic Year: ' . $timetable->academic_session;
        $subtitleText .= ' | Term: ' . $timetable->academic_term;
        
        $sheet->setCellValue('A2', $subtitleText);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'size' => 12,
                'color' => ['rgb' => '40916C']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);
        
        // Header row styling
        $sheet->getStyle('A3:' . $lastColumn . '3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2D6A4F']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '1B4332']
                ]
            ]
        ]);
        
        // Time column styling
        $sheet->getStyle('A4:A' . $lastRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '2D6A4F']
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F8FFF8']
            ],
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => 'D8F3DC']
                ]
            ]
        ]);
        
        // All cells styling
        $sheet->getStyle('A3:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E5E7EB']
                ]
            ]
        ]);
        
        // Content cells alignment
        $sheet->getStyle('B4:' . $lastColumn . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ]);
        
        return [];
    }
    
    /**
     * @return string
     */
    public function title(): string
    {
        return $this->getTitle();
    }
    
    /**
     * Generate the title for the spreadsheet.
     *
     * @return string
     */
    private function getTitle(): string
    {
        $timetable = $this->data['timetable'];
        $className = optional($timetable->myClass)->name ?? 'Class';
        $section = $this->data['section'];
        $sectionName = $section ? " - {$section->name}" : "";
        
        return "Timetable: {$className}{$sectionName} ({$timetable->academic_session}, {$timetable->academic_term})";
    }
    
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Set row height
                $lastRow = count($this->data['periods']) + 3; // +3 for header and title rows
                
                // Title and subtitle rows
                $event->sheet->getRowDimension(1)->setRowHeight(30);
                $event->sheet->getRowDimension(2)->setRowHeight(25);
                $event->sheet->getRowDimension(3)->setRowHeight(25);
                
                // Content rows
                for ($i = 4; $i <= $lastRow; $i++) {
                    $event->sheet->getRowDimension($i)->setRowHeight(50);
                }
                
                // Auto-width columns - sometimes needs manual adjustment
                $event->sheet->getColumnDimension('A')->setWidth(25);
                
                // Freeze the top row and first column
                $event->sheet->freezePane('B4');
                
                // Set print area
                $lastColumn = chr(65 + count($this->data['visibleDays']));
                $event->sheet->getPageSetup()->setPrintArea('A1:' . $lastColumn . $lastRow);
                
                // Landscape orientation
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                
                // Fit to page
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
} 