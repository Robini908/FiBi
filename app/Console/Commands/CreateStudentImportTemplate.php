<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CreateStudentImportTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:student-import-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an Excel template for importing students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating student import template...');

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Student Import Template');

        // Define the headers
        $headers = [
            'First Name',
            'Last Name',
            'Admission Number',
            'Class',
            'Section',
            'Gender',
            'Date of Birth',
            'Email',
            'Phone',
            'Address',
            'Parent Name',
            'Parent Email',
            'Parent Phone',
            'Parent Occupation',
            'Year Admitted',
            'KCPE Score'
        ];

        // Add headers to the first row
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index); // A, B, C, etc.
            $sheet->setCellValue($column . '1', $header);
        }

        // Add sample data in the second row
        $sampleData = [
            'John',
            'Doe',
            'ADM2023001',
            'Grade 10',
            'A',
            'Male',
            '2005-01-15',
            'student@example.com',
            '+2541234567',
            '123 Main St, Nairobi',
            'Jane Doe',
            'parent@example.com',
            '+2547890123',
            'Doctor',
            '2023',
            '350'
        ];

        // Add sample data
        foreach ($sampleData as $index => $value) {
            $column = chr(65 + $index); // A, B, C, etc.
            $sheet->setCellValue($column . '2', $value);
        }

        // Styling the header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'], // Green color
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Apply header style
        $lastColumn = chr(65 + count($headers) - 1);
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);

        // Add a comment to the first header cell to explain how to use the template
        $comment = $sheet->getComment('A1');
        $comment->getText()->createTextRun('Instructions:');
        $comment->getText()->createTextRun("\r\n");
        $comment->getText()->createTextRun('1. Do not remove the header row');
        $comment->getText()->createTextRun("\r\n");
        $comment->getText()->createTextRun('2. Enter one student per row');
        $comment->getText()->createTextRun("\r\n");
        $comment->getText()->createTextRun('3. Follow the format shown in the example row');
        $comment->setHeight('150pt');
        $comment->setWidth('200pt');

        // Auto size columns
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Ensure the downloads directory exists
        $downloadsPath = public_path('downloads');
        if (!file_exists($downloadsPath)) {
            mkdir($downloadsPath, 0755, true);
        }

        // Save the spreadsheet to a file
        $writer = new Xlsx($spreadsheet);
        $filename = $downloadsPath . '/student_import_template.xlsx';
        $writer->save($filename);

        $this->info('Student import template created successfully at: ' . $filename);

        return Command::SUCCESS;
    }
} 