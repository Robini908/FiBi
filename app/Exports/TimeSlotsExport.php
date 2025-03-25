<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TimeSlotsExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    protected $rows;
    protected $timetableInfo;

    public function __construct(array $rows, array $timetableInfo)
    {
        $this->rows = $rows;
        $this->timetableInfo = $timetableInfo;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        // Remove header row since it's handled by WithHeadings
        return array_slice($this->rows, 1);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Return the first row as headings
        return $this->rows[0] ?? ['Category', 'Period Name', 'Start Time', 'End Time', 'Duration (mins)', 'Type'];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = count($this->rows);
                
                // Add timetable info at the top
                $sheet->insertNewRowBefore(1, 5); // Adding one more row for logo
                
                // Try to add school logo if it exists
                $logoPath = public_path('images/school-logo.png');
                if (file_exists($logoPath)) {
                    $drawing = new Drawing();
                    $drawing->setName('School Logo');
                    $drawing->setDescription('School Logo');
                    $drawing->setPath($logoPath);
                    $drawing->setCoordinates('A1');
                    $drawing->setWidth(80);
                    $drawing->setHeight(80);
                    $drawing->setWorksheet($sheet);
                    
                    // Adjust row height for logo
                    $sheet->getRowDimension(1)->setRowHeight(60);
                }
                
                // Title with gradient styling
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'SCHOOL TIMETABLE');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 22,
                        'color' => ['rgb' => '006400'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'FFE7F5DF',
                        ],
                        'endColor' => [
                            'argb' => 'FFF8FFF8',
                        ],
                    ],
                ]);
                
                // Subtitle with year/term
                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', 'TIME SLOTS REPORT');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => '2D6A4F'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                // Timetable Details
                $sheet->mergeCells('A3:F3');
                $sheet->setCellValue('A3', 'Timetable: ' . ($this->timetableInfo['name'] ?? 'N/A'));
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '40916c'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                // Academic Year/Term
                $sheet->mergeCells('A4:F4');
                $academicInfo = 'Academic Year: ' . ($this->timetableInfo['academic_year'] ?? 'N/A');
                if (!empty($this->timetableInfo['term'])) {
                    $academicInfo .= ' | Term: ' . $this->timetableInfo['term'];
                }
                $sheet->setCellValue('A4', $academicInfo);
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => [
                        'size' => 11,
                        'color' => ['rgb' => '40916c'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                // Generation timestamp
                $sheet->mergeCells('A5:F5');
                $sheet->setCellValue('A5', 'Generated on: ' . date('F j, Y, g:i a'));
                $sheet->getStyle('A5')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                        'color' => ['rgb' => '666666'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                // Apply border and styling to the title section
                $sheet->getStyle('A1:F5')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '2D6A4F'],
                        ],
                    ],
                ]);
                
                // Adjust the main data styling to account for the new header rows
                $dataStartRow = 7; // Headers now at row 6, data at row 7
                $dataEndRow = $lastRow + 5; // Adjust for the 5 new rows
                
                if ($lastRow > 1) {
                    // Add column formatting
                    // Time formatting for start and end times
                    $sheet->getStyle('C' . $dataStartRow . ':D' . $dataEndRow)
                          ->getNumberFormat()
                          ->setFormatCode(NumberFormat::FORMAT_DATE_TIME3); // h:mm AM/PM
                    
                    // Main table styling      
                    $sheet->getStyle('A' . $dataStartRow . ':F' . $dataEndRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'DDDDDD'],
                            ],
                        ],
                    ]);
                    
                    // Group similar categories with subtle background colors
                    $currentCategory = '';
                    $categoryColor = '';
                    
                    for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                        $rowCategory = $sheet->getCell('A' . $i)->getValue();
                        
                        // If category changes, update color
                        if ($rowCategory !== $currentCategory) {
                            $currentCategory = $rowCategory;
                            
                            if ($rowCategory === 'Weekday') {
                                $categoryColor = 'F8FFF8'; // Very light green
                            } elseif ($rowCategory === 'Saturday' || $rowCategory === 'Sunday' || $rowCategory === 'Weekend') {
                                $categoryColor = 'F0F0FF'; // Very light indigo
                            } elseif ($rowCategory === 'Prep') {
                                $categoryColor = 'FFFBEB'; // Very light amber
                            } else {
                                $categoryColor = 'FFFFFF'; // White default
                            }
                        }
                        
                        // Apply category color
                        $sheet->getStyle('A' . $i . ':F' . $i)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $categoryColor],
                            ],
                        ]);
                        
                        // Zebra striping within categories (lighter version of category color)
                        if ($i % 2 === 0) {
                            // Add a very slight darkness to even rows for zebra effect
                            $sheet->getStyle('A' . $i . ':F' . $i)->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => $this->darkenColor($categoryColor, 5)],
                                ],
                            ]);
                        }
                    }
                    
                    // Add type indicators with colored circles (using custom cell formatting)
                    for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                        $type = $sheet->getCell('F' . $i)->getValue();
                        
                        // Color code for type column
                        if ($type == 'Lesson' || $type == 'Class') {
                            $color = '2D6A4F'; // Green
                        } elseif ($type == 'Break') {
                            $color = '4361EE'; // Blue
                        } elseif ($type == 'Lunch') {
                            $color = 'F59E0B'; // Yellow
                        } elseif ($type == 'Morning Prep' || $type == 'Evening Prep' || $type == 'Prep') {
                            $color = 'D97706'; // Amber
                        } elseif ($type == 'Games' || $type == 'Activities') {
                            $color = '8B5CF6'; // Purple
                        } elseif ($type == 'Weekend Class') {
                            $color = '4F46E5'; // Indigo
                        } else {
                            $color = '6B7280'; // Gray
                        }
                        
                        $sheet->getStyle('F' . $i)->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => $color],
                                'bold' => true,
                            ]
                        ]);
                    }
                    
                    // Add mini-legend at the bottom
                    $legendRow = $dataEndRow + 2;
                    $sheet->mergeCells('A' . $legendRow . ':F' . $legendRow);
                    $sheet->setCellValue('A' . $legendRow, 'Color Legend: Green = Lesson | Blue = Break | Yellow = Lunch | Purple = Activities | Indigo = Weekend | Amber = Prep');
                    $sheet->getStyle('A' . $legendRow)->applyFromArray([
                        'font' => [
                            'size' => 10,
                            'color' => ['rgb' => '666666'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    
                    // Footer
                    $footerRow = $dataEndRow + 4;
                    $sheet->mergeCells('A' . $footerRow . ':F' . $footerRow);
                    $sheet->setCellValue('A' . $footerRow, 'For more information, please contact the school administration');
                    $sheet->getStyle('A' . $footerRow)->applyFromArray([
                        'font' => [
                            'italic' => true,
                            'size' => 10,
                            'color' => ['rgb' => '666666'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }
            },
        ];
    }

    /**
     * Darken a hex color by a percentage
     * 
     * @param string $hex Color in RGB hex format (without #)
     * @param int $percent Percentage to darken (0-100)
     * @return string Resulting darkened color
     */
    private function darkenColor($hex, $percent) {
        // Convert to RGB values
        $rgb = str_split($hex, 2);
        $r = hexdec($rgb[0]);
        $g = hexdec($rgb[1]);
        $b = hexdec($rgb[2]);
        
        // Darken each component
        $r = max(0, $r - ($r * ($percent / 100)));
        $g = max(0, $g - ($g * ($percent / 100)));
        $b = max(0, $b - ($b * ($percent / 100)));
        
        // Convert back to hex
        return sprintf('%02X%02X%02X', $r, $g, $b);
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row (now at row 6 because of the timetable info rows)
        $sheet->getStyle('A6:F6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '40916c'], // Green header
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '2D6A4F'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Make the header row taller
        $sheet->getRowDimension(6)->setRowHeight(25);

        // Set column alignment and formatting
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Category
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Period Name
        $sheet->getStyle('C:D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Times
        $sheet->getStyle('E:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Duration
        $sheet->getStyle('F:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Type
        
        // Highlighting based on contents rather than using conditional formatting
        // This approach works better with dynamic data from arrays
        $lastRow = count($this->rows) + 6; // Accounting for header rows
        
        for ($i = 7; $i <= $lastRow; $i++) {
            $durationCell = $sheet->getCell('E' . $i);
            $durationValue = $durationCell->getValue();
            
            // Extract just the number from "XX mins" format if needed
            if (is_string($durationValue) && strpos($durationValue, 'mins') !== false) {
                $durationValue = (int) trim(str_replace('mins', '', $durationValue));
            }
            
            // Apply conditional highlighting based on duration
            if ($durationValue >= 60) {
                // Long duration - light green
                $sheet->getStyle('E' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('DDFFDD');
            } elseif ($durationValue < 15) {
                // Very short duration - light yellow
                $sheet->getStyle('E' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFFFDD');
            }
        }
        
        return $sheet;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Time Slots';
    }
} 