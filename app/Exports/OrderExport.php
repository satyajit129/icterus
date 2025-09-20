<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Order Number',
            'Product Name',
            'Customer Name',
            'Customer Email',
            'Customer Mobile',
            'Customer WhatsApp',
            'Amount (৳)',
            'Payment Method',
            'Bkash Transaction ID',
            'Payment Status',
            'Notes',
            'Created At',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->product->name ?? 'N/A',
            $order->customer_name,
            $order->customer_email,
            $order->customer_mobile,
            $order->customer_whatsapp,
            number_format($order->amount, 2),
            ucfirst($order->payment_method),
            $order->bkash_transaction_id ?? 'N/A',
            ucfirst($order->payment_status),
            $order->notes ?? 'N/A',
            $order->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
