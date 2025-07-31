<?php

namespace App\Exports;

use App\Models\SalaryExpense;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class SalaryExpenseExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = SalaryExpense::with('employee.designation', 'employee.department');

        if (!empty($this->filters['payable_month'])) {
            $query->where('payable_month', $this->filters['payable_month']);
        }

        if (!empty($this->filters['payable_year'])) {
            $query->where('payable_year', $this->filters['payable_year']);
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

        $salary_expenses = $query->get();

        return view('backend.pages.export_salary_expense', compact('salary_expenses'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();
                $columns = range('A', 'K');

                foreach ($columns as $column) {
                    $sheet->getStyle($column . '1:' . $column . $highestRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            },
        ];
    }
}
