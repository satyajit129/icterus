<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Amount</th>
            <th>Paid Amount</th>
            <th>Due Amount</th>
            <th>Loan Date</th>
            <th>Status</th>
            <th>Payment Count</th>
            <th>Last Payment Date</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loans as $index => $loan)
            @php
                $paid = $loan->loanPayment->sum('amount');
                $remaining = $loan->amount - $paid;
                $lastPayment = $loan->loanPayment->sortByDesc('payment_date')->first();
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $loan->employee->id_number ?? 'N/A' }}</td>
                <td>{{ $loan->employee->name ?? 'N/A' }}</td>
                <td>{{ number_format($loan->amount, 2) }}</td>
                <td>{{ number_format($paid, 2) }}</td>
                <td>{{ number_format($remaining, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->date)->format('d/m/Y') }}</td>
                <td>
                    @if ($loan->status == 1)
                        Due
                    @elseif($loan->status == 2)
                        Paid
                    @else
                        Unknown
                    @endif
                </td>
                <td>{{ $loan->loanPayment->count() }}</td>
                <td>{{ $lastPayment ? \Carbon\Carbon::parse($lastPayment->payment_date)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach

        {{-- Summary Row --}}
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="3">TOTAL SUMMARY</td>
            <td>{{ number_format($summary['total_amount'], 2) }}</td>
            <td>{{ number_format($summary['total_paid'], 2) }}</td>
            <td>{{ number_format($summary['total_due'], 2) }}</td>
            <td colspan="5"></td>
        </tr>
    </tbody>
</table>
