<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Courses</th>
            <th>Amount</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Status</th>
            <th>Enroll Date</th>
            <th>Details</th>
            <th>Payment Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $index => $student)
            @php
                $paid = $student->payments->sum('amount');
                $remaining = $student->amount - $paid;
                $statusText = '';
                if ($student->status == 1) {
                    $statusText = 'Due';
                } elseif ($student->status == 2) {
                    $statusText = 'Paid';
                } else {
                    $statusText = 'Unknown';
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ $student->courses }}</td>
                <td>৳{{ number_format($student->amount, 2) }}</td>
                <td>৳{{ number_format($paid, 2) }}</td>
                <td>৳{{ number_format($remaining, 2) }}</td>
                <td>{{ $statusText }}</td>
                <td>{{ \Carbon\Carbon::parse($student->enroll_date)->format('d/m/Y') }}</td>
                <td>{{ $student->details }}</td>
                <td>{{ $student->payments->count() }}</td>
            </tr>
        @endforeach

        <!-- Summary Row -->
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="5" style="text-align: right;">TOTAL SUMMARY:</td>
            <td>৳{{ number_format($summary['total_amount'], 2) }}</td>
            <td>৳{{ number_format($summary['total_paid'], 2) }}</td>
            <td>৳{{ number_format($summary['total_due'], 2) }}</td>
            <td colspan="4"></td>
        </tr>
    </tbody>
</table>
