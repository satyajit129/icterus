@php
    use Carbon\Carbon;
    $formattedDate = $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d/m/Y') : '';
@endphp

<form action="{{ route('adminLoanPaymentUpdate') }}" method="post">
    @csrf
    <input type="hidden" name="payment_id" value="{{ $payment->id }}">

    <div class="mb-3">
        <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
        <input type="number" name="amount" class="form-control" required value="{{ $payment->amount }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
        <input type="text" name="payment_date" class="form-control fc-datepicker" placeholder="DD/MM/YYYY" required value="{{ $formattedDate }}">
    </div>

    <div class="mb-3 text-end">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fe fe-save me-1"></i> Submit Payment
        </button>
    </div>
</form>

