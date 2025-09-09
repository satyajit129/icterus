<?php

namespace App\Exports;

use App\Models\CompanyDeal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CompanyDealsExport implements FromView, ShouldAutoSize, WithEvents
{
    public function view(): View
    {
        $company_deals = CompanyDeal::with(['companies', 'dealPayments'])->get();

        return view('backend.pages.export_company_deals', compact('company_deals'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastRow = $sheet->getHighestRow();
                $columns = range('A', 'I');

                foreach ($columns as $column) {
                    $sheet->getStyle($column . '1:' . $column . $lastRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            },
        ];
    }
}
