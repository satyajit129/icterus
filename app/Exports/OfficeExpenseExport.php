<?php 

namespace App\Exports;

use App\Models\OfficeExpense;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class OfficeExpenseExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = OfficeExpense::with('category');

        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (!empty($this->filters['purpose'])) {
            $query->where('purpose', 'like', '%' . $this->filters['purpose'] . '%');
        }

        if (!empty($this->filters['date'])) {
            $dates = explode(' - ', $this->filters['date']);
            if (count($dates) === 2) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        }

        $expenses = $query->get();

        return view('backend.pages.export_office_expense', compact('expenses'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Get total rows count (including header)
                $rows = $event->sheet->getHighestRow();

                // Get total columns count, here 7 columns: A to G
                $columns = range('A', 'G');

                // Loop through each column and row and set alignment to left
                foreach ($columns as $column) {
                    // Apply left alignment for all cells in this column
                    $event->sheet->getDelegate()->getStyle($column . '1:' . $column . $rows)
                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            },
        ];
    }
}
