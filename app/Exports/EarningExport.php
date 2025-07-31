<?php

namespace App\Exports;

use App\Models\Earning;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EarningExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $filter;

    public function __construct($filter = [])
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $query = Earning::query();

        if (!empty($this->filter['company_id'])) {
            $query->where('company_id', $this->filter['company_id']);
        }

        if (!empty($this->filter['name'])) {
            $query->whereHas('employee', function ($q) {
                $q->where('name', 'like', '%' . $this->filter['name'] . '%');
            });
        }

        if (!empty($this->filter['sales_status'])) {
            $query->where('sales_status', $this->filter['sales_status']);
        }

        if (!empty($this->filter['date'])) {
            $dates = explode(' - ', $this->filter['date']);
            $start = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
            $end = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
            $query->whereBetween('date', [$start, $end]);
        }

        $earnings = $query->with(['companies', 'employee'])->get();

        return view('backend.pages.export_earning', compact('earnings'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastRow = $sheet->getHighestRow();

                // Columns A through J
                $columns = range('A', 'J');

                foreach ($columns as $column) {
                    $sheet->getStyle($column . '1:' . $column . $lastRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            },
        ];
    }
}
