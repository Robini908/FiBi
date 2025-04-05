<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exam->name }} - Exam Timetable</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style>
        @media print {
            body {
                padding: 20px;
                font-family: 'Arial', sans-serif;
            }
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            table {
                width: 100% !important;
            }
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 100px;
            height: auto;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }
        .address {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .print-only {
            display: none;
        }
        .btn-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            right: 20px;
            z-index: 1000;
        }
        .btn-print {
            bottom: 20px;
            background-color: #28a745;
            color: white;
        }
        .btn-back {
            bottom: 70px;
            background-color: #007bff;
            color: white;
        }
        .instructions {
            font-size: 14px;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Floating action buttons -->
    <a href="javascript:window.print();" class="btn btn-circle btn-print no-print" title="Print">
        <i class="fas fa-print"></i>
    </a>
    <a href="{{ route('exam.timetable') }}" class="btn btn-circle btn-back no-print" title="Back">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="header">
                    <div class="print-only">
                        @if(isset($settings['system_logo']))
                        <img src="{{ $settings['system_logo'] }}" alt="School Logo" class="logo">
                        @endif
                        <div class="school-name">{{ $settings['system_name'] ?? 'School Management System' }}</div>
                        <div class="address">{{ $settings['address'] ?? '' }}</div>
                        <div>{{ $settings['phone'] ?? '' }}</div>
                    </div>

                    <h1 class="text-center text-uppercase">{{ $exam->name }} Timetable</h1>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Class:</strong> {{ $class->name }}{{ $section ? ' - ' . $section->name : '' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Academic Year:</strong> {{ $exam->year }}
                        </div>
                        <div class="col-md-4">
                            <strong>Term:</strong> {{ $exam->term }}
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Duration</th>
                                <th>Venue</th>
                                <th>Invigilators</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examRecords as $record)
                            <tr>
                                <td>{{ $record->subject->subject_name ?? 'Unknown Subject' }}</td>
                                <td>{{ $record->date->format('D, d M Y') }}</td>
                                <td>{{ $record->time_range }}</td>
                                <td>{{ $record->duration_minutes }} mins</td>
                                <td>{{ $record->venue }}</td>
                                <td>
                                    @if(is_array($record->invigilators))
                                        @foreach($record->invigilators as $teacherId)
                                            {{ $teachers[$teacherId]->name ?? 'Unknown' }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                            @if($record->instructions)
                            <tr>
                                <td colspan="6" class="instructions">
                                    <strong>Instructions for {{ $record->subject->subject_name ?? 'this exam' }}:</strong><br>
                                    {!! nl2br(e($record->instructions)) !!}
                                </td>
                            </tr>
                            @endif
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No exam records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="instructions mt-4">
                    <strong>General Instructions:</strong>
                    <ol>
                        <li>All students must be present at least 15 minutes before the exam starts.</li>
                        <li>Students must bring their own stationery and materials as required.</li>
                        <li>No electronic devices are allowed in the examination room unless specified.</li>
                        <li>Any form of malpractice will result in serious disciplinary action.</li>
                    </ol>
                </div>

                <div class="text-center mt-4 print-only">
                    <p><small>Generated on {{ date('Y-m-d H:i:s') }} | {{ $settings['system_name'] ?? 'School Management System' }}</small></p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/fontawesome/js/all.min.js') }}"></script>
</body>
</html> 