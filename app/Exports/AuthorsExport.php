<?php

namespace App\Exports;

use App\Models\BookAuthor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuthorsExport implements FromView, ShouldAutoSize, WithStyles, WithEvents, WithTitle
{
    protected $filters;
    
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }
    
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Authors List';
    }
    
    /**
     * @return View
     */
    public function view(): View
    {
        // Apply filters
        $authorsQuery = BookAuthor::query()
            ->when(isset($this->filters['search']) && $this->filters['search'], function ($query) {
                return $query->where('name', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhere('nationality', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhere('biography', 'like', '%' . $this->filters['search'] . '%');
            })
            ->when(isset($this->filters['featuredOnly']) && $this->filters['featuredOnly'], function ($query) {
                return $query->featured();
            })
            ->when(isset($this->filters['activeOnly']) && $this->filters['activeOnly'], function ($query) {
                return $query->active();
            })
            ->when(isset($this->filters['nationalities']) && !empty($this->filters['nationalities']), function ($query) {
                return $query->whereIn('nationality', $this->filters['nationalities']);
            })
            ->when(isset($this->filters['withBooksOnly']) && $this->filters['withBooksOnly'], function ($query) {
                return $query->whereHas('books');
            });
            
        // Apply sorting
        $sortField = $this->filters['sortField'] ?? 'name';
        $sortDirection = $this->filters['sortDirection'] ?? 'asc';
        
        $authors = $authorsQuery->orderBy($sortField, $sortDirection)
            ->withCount('books')
            ->get();
            
        return view('exports.library.authors', [
            'authors' => $authors,
            'filters' => $this->filters,
            'date' => now()->format('F d, Y'),
        ]);
    }
    
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style for header row
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F7942'], // Forest green
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Style for subheader row
            2 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EBF1DE'], // Light green
                ],
            ],
            // Style for the entire document
            'A1:G100' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
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
                // Merge cells for the title
                $event->sheet->mergeCells('A1:G1');
                
                // Auto-filter for the header row
                $event->sheet->setAutoFilter('A2:G2');
                
                // Set column widths
                $event->sheet->getColumnDimension('A')->setWidth(30); // Name
                $event->sheet->getColumnDimension('B')->setWidth(20); // Nationality
                $event->sheet->getColumnDimension('C')->setWidth(15); // Birth Date
                $event->sheet->getColumnDimension('D')->setWidth(15); // Status
                $event->sheet->getColumnDimension('E')->setWidth(15); // Books Count
                $event->sheet->getColumnDimension('F')->setWidth(20); // Website
                $event->sheet->getColumnDimension('G')->setWidth(30); // Email
                
                // Set row height for the first row
                $event->sheet->getRowDimension(1)->setRowHeight(30);
            },
        ];
    }
} 