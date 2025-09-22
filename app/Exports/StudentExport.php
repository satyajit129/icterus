<?php

namespace App\Exports;

use App\Models\Student;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class StudentExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = Student::with('payments');

        // Apply filters if present
        if (!empty($this->filters['date_from'])) {
            $dateFrom = Carbon::createFromFormat('d-m-Y', $this->filters['date_from'])->format('Y-m-d');
            $query->whereDate('enroll_date', '>=', $dateFrom);
        }

        if (!empty($this->filters['date_to'])) {
            $dateTo = Carbon::createFromFormat('d-m-Y', $this->filters['date_to'])->format('Y-m-d');
            $query->whereDate('enroll_date', '<=', $dateTo);
        }

        if (!empty($this->filters['course'])) {
            $query->where('courses', 'like', '%' . $this->filters['course'] . '%');
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['phone'])) {
            $query->where('phone', 'like', '%' . $this->filters['phone'] . '%');
        }

        $students = $query->latest()->get();

        // Calculate summary data
        $totalAmount = $students->sum('amount');
        $totalPaid = $students->sum(function($student) {
            return $student->payments->sum('amount');
        });
        $totalDue = $totalAmount - $totalPaid;

        $summary = [
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue
        ];

        return view('backend.pages.export_student', compact('students', 'summary'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Set header row styling
                $sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Set borders for all data
                $sheet->getStyle('A1:L' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(8);   // #
                $sheet->getColumnDimension('B')->setWidth(20);  // Name
                $sheet->getColumnDimension('C')->setWidth(25);  // Email
                $sheet->getColumnDimension('D')->setWidth(15);  // Phone
                $sheet->getColumnDimension('E')->setWidth(25);  // Courses
                $sheet->getColumnDimension('F')->setWidth(15);  // Amount
                $sheet->getColumnDimension('G')->setWidth(15);  // Paid
                $sheet->getColumnDimension('H')->setWidth(15);  // Due
                $sheet->getColumnDimension('I')->setWidth(15);  // Status
                $sheet->getColumnDimension('J')->setWidth(12);  // Enroll Date
                $sheet->getColumnDimension('K')->setWidth(30);  // Details
                $sheet->getColumnDimension('L')->setWidth(20);  // Payment Count

                // Set row height for header
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Center align all data
                $sheet->getStyle('A1:L' . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        ];
    }
}
