<?php

namespace App\Exports;

use App\Models\Exam;
use App\Models\ExamRecord;
use App\Models\MyClass;
use App\Models\Section;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExamTimetableExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected $examId;
    protected $classId;
    protected $sectionId;
    protected $exam;
    protected $class;
    protected $section;
    protected $teachers;

    /**
     * Create a new export instance.
     *
     * @param  int  $examId
     * @param  int  $classId
     * @param  int|null  $sectionId
     * @return void
     */
    public function __construct($examId, $classId, $sectionId = null)
    {
        $this->examId = $examId;
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        
        // Load related models
        $this->exam = Exam::findOrFail($examId);
        $this->class = MyClass::findOrFail($classId);
        $this->section = $sectionId ? Section::findOrFail($sectionId) : null;
        
        // Get all teachers who are invigilators
        $examRecords = $this->collection();
        $teacherIds = $examRecords->pluck('invigilators')->flatten()->unique();
        $this->teachers = User::whereIn('id', $teacherIds)->role('teacher')->get()->keyBy('id');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ExamRecord::where('exam_id', $this->examId)
            ->where('class_id', $this->classId)
            ->when($this->sectionId, function($query) {
                return $query->where('section_id', $this->sectionId);
            })
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Subject',
            'Date',
            'Time',
            'Duration',
            'Venue',
            'Invigilators',
            'Instructions'
        ];
    }

    /**
     * @param  mixed  $row
     * @return array
     */
    public function map($row): array
    {
        // Format invigilators names
        $invigilators = collect($row->invigilators)->map(function($id) {
            return $this->teachers->get($id)->name ?? 'Unknown';
        })->implode(', ');
        
        // Calculate duration
        $duration = $row->duration_minutes . ' mins';
        
        return [
            $row->subject->subject_name ?? 'Unknown Subject',
            $row->date->format('D, d M Y'),
            $row->time_range,
            $duration,
            $row->venue,
            $invigilators,
            $row->instructions,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $title = $this->exam->name . ' - ' . $this->class->name;
        
        if ($this->section) {
            $title .= ' (' . $this->section->name . ')';
        }
        
        return $title;
    }

    /**
     * @param  \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet  $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Add title and subtitle at the top
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'Exam Timetable: ' . $this->exam->name);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Class: ' . $this->class->name . ($this->section ? ' - ' . $this->section->name : ''));
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        
        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'Academic Year: ' . $this->exam->year);
        
        // Add space and move the actual data down
        $sheet->insertNewRowBefore(4, 2);
        
        // Style the header row
        $headerStyle = $sheet->getStyle('A6:G6');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
            
        // Border for all cells with data
        $lastRow = $sheet->getHighestRow();
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A6:G'.$lastRow)->applyFromArray($borderStyle);
        
        // Text wrapping for instructions
        $sheet->getStyle('G6:G'.$lastRow)->getAlignment()->setWrapText(true);
        
        return [];
    }
} 