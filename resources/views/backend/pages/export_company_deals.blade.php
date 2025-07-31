<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Company Name</th>
            <th>Deals</th>
            <th>Deals Amount</th>
            <th>Total Paid</th>
            <th>Remaining Balance</th>
            <th>Contract Duration</th>
            <th>Payment Frequency</th>
        </tr>
    </thead>
    <tbody>
        @foreach($company_deals as $index => $deal)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($deal->date)->format('d F Y') }}</td>
                <td>{{ $deal->companies->name ?? 'N/A' }}</td>
                <td>{{ $deal->deals }}</td>
                <td>{{ $deal->deals_amount }}</td>
                <td>{{ number_format($deal->dealPayments->sum('amount'), 2) }}</td>
                <td>{{ number_format($deal->remaining_balance, 2) }}</td>
                <td>{{ $deal->contract_duration }} M</td>
                <td>{{ $deal->payment_frequency }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
