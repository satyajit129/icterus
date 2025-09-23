<?php

namespace App\Exports;

use App\Models\Loan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class LoanExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = Loan::with('employee', 'loanPayment');

        // Apply filters if present
        if (!empty($this->filters['employee_id'])) {
            $query->where('employee_id', $this->filters['employee_id']);
        }

        if (!empty($this->filters['date_from'])) {
            $dateFrom = Carbon::createFromFormat('d-m-Y', $this->filters['date_from'])->format('Y-m-d');
            $query->whereDate('date', '>=', $dateFrom);
        }

        if (!empty($this->filters['date_to'])) {
            $dateTo = Carbon::createFromFormat('d-m-Y', $this->filters['date_to'])->format('Y-m-d');
            $query->whereDate('date', '<=', $dateTo);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        $loans = $query->latest()->get();

        // Calculate summary data
        $totalAmount = $loans->sum('amount');
        $totalPaid = $loans->sum(function($loan) {
            return $loan->loanPayment->sum('amount');
        });
        $totalDue = $totalAmount - $totalPaid;

        $summary = [
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue
        ];

        return view('backend.pages.export_loan', compact('loans', 'summary'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Set header row styling
                $sheet->getStyle('A1:K1')->applyFromArray([
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
                $sheet->getStyle('A1:K' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(8);   // #
                $sheet->getColumnDimension('B')->setWidth(15);  // Employee ID
                $sheet->getColumnDimension('C')->setWidth(25);  // Employee Name
                $sheet->getColumnDimension('D')->setWidth(15);  // Amount
                $sheet->getColumnDimension('E')->setWidth(15);  // Paid Amount
                $sheet->getColumnDimension('F')->setWidth(15);  // Due Amount
                $sheet->getColumnDimension('G')->setWidth(12);  // Loan Date
                $sheet->getColumnDimension('H')->setWidth(15);  // Status
                $sheet->getColumnDimension('I')->setWidth(15);  // Payment Count
                $sheet->getColumnDimension('J')->setWidth(20);  // Last Payment Date
                $sheet->getColumnDimension('K')->setWidth(20);  // Created At

                // Set row height for header
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Center align all data
                $sheet->getStyle('A1:K' . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        ];
    }
}
