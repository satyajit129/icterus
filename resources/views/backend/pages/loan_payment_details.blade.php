<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Payment Date</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($loan_payments as $index => $payment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                <td>৳{{ number_format($payment->amount, 2) }}</td>
                <td>
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary edit-payment-btn"
                        data-id="{{ $payment->id }}">
                        <i class="fe fe-edit"></i>
                    </a>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-danger">No payment records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
