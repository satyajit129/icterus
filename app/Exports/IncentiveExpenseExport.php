<?php

namespace App\Exports;

use App\Models\IncentiveExpense;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class IncentiveExpenseExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = IncentiveExpense::with('employee.designation', 'employee.department');

        if (!empty($this->filters['payable_month'])) {
            $query->whereMonth('payable_month', $this->filters['payable_month']);
        }

        if (!empty($this->filters['payable_year'])) {
            $query->whereYear('payable_month', $this->filters['payable_year']);
        }

        if (!empty($this->filters['name'])) {
            $query->whereHas('employee', function ($q) {
                $q->where('name', 'like', '%' . $this->filters['name'] . '%');
            });
        }

        if (!empty($this->filters['employee_id'])) {
            $query->whereHas('employee', function ($q) {
                $q->where('id_number', 'like', '%' . $this->filters['employee_id'] . '%');
            });
        }

        $incentive_expenses = $query->get();

        return view('backend.pages.export_incentive_expense', compact('incentive_expenses'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $columns = range('A', 'I');

                // Apply left alignment on all columns for all rows
                foreach ($columns as $column) {
                    $sheet->getStyle($column . '1:' . $column . $lastRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            },
        ];
    }
}
