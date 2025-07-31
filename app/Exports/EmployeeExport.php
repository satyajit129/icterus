<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class EmployeeExport implements FromView, ShouldAutoSize, WithEvents, WithDrawings
{
    protected $employees;

    public function __construct()
    {
        $this->employees = Employee::with(['designation', 'department'])
            ->where('status', 1)
            ->get();
    }

    public function view(): View
    {
        return view('backend.pages.export_employee', ['employees' => $this->employees]);
    }

public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();
            $lastRow = $sheet->getHighestRow();

            // Set fixed width for Picture column B
            $sheet->getColumnDimension('B')->setWidth(15);

            // Columns A to H (adjust as needed)
            $columns = range('A', 'H');

            foreach ($columns as $column) {
                $sheet->getStyle($column . '1:' . $column . $lastRow)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            }

            // Set row height (~80px) for data rows (starting from row 2)
            for ($row = 2; $row <= $lastRow; $row++) {
                $sheet->getRowDimension($row)->setRowHeight(60);
            }
        },
    ];
}


    public function drawings()
    {
        $drawings = [];

        $row = 2; // Data starts from row 2 (assuming row 1 is header)
        foreach ($this->employees as $employee) {
            if ($employee->picture && file_exists(public_path('uploads/' . $employee->picture))) {
                $drawing = new Drawing();
                $drawing->setName('Employee Picture');
                $drawing->setDescription($employee->name);
                $drawing->setPath(public_path('uploads/' . $employee->picture));
                $drawing->setCoordinates('B' . $row);
                $drawing->setHeight(55); // Slightly less than row height for padding
                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }
}
