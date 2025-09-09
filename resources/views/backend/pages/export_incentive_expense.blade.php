<table>
    <thead>
        <tr>
            <th>#</th>
            <th>ID Number</th>
            <th>Name</th>
            <th>Month</th>
            <th>Year</th>
            <th>Sales Count</th>
            <th>Sales Amount</th>
            <th>Incentive Amount</th>
            <th>Payable Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($incentive_expenses as $index => $expense)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $expense->employee->id_number }}</td>
                <td>{{ $expense->employee->name }}</td>
                <td>{{ \Carbon\Carbon::parse($expense->payable_month)->format('M') }}
                </td>
                <td>{{ \Carbon\Carbon::parse($expense->payable_month)->format('Y') }}
                </td>
                <td>{{ $expense->sales_count }}</td>
                <td>{{ $expense->sales_amount }}</td>
                <td>{{ $expense->incentive_amount }}</td>
                <td style="text-align: left">{{ $expense->payable_amount }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
