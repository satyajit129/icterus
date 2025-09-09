<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        text-align: left !important;  /* force left alignment */
        padding: 5px;
        border: 1px solid #ddd;
        mso-number-format: "\@";
    }
</style>


<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Category</th>
            <th>Date</th>
            <th>Purpose</th>
            <th>Quantity</th>
            <th>Details</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($expenses as $index => $expense)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $expense->category->name ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                <td>{{ $expense->purpose }}</td>
                <td>{{ $expense->quantity }}</td>
                <td>{{ $expense->details }}</td>
                <td>{{ number_format($expense->amount, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
