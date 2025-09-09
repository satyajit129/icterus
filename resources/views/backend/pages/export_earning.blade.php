<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Company</th>
            <th>Date</th>
            <th>Employee</th>
            <th>Sales Status</th>
            <th>Paid</th>
            <th>Deals</th>
            <th>Due</th>
            <th>Product Name</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        @foreach($earnings as $index => $earning)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $earning->companies->name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($earning->date)->format('d/m/Y') }}</td>
                <td>{{ $earning->employee->name ?? 'N/A' }}</td>
                <td>{{ \App\Models\SalesStatus::find($earning->sales_status)?->status ?? 'N/A' }}</td>
                <td>{{ $earning->paid_amount }}</td>
                <td>{{ $earning->deals_amount }}</td>
                <td>{{ $earning->due_amount }}</td>
                <td>{{ $earning->product_name }}</td>
                <td>{{ $earning->details }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
