<div class="card">
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="company_deal_id" value="{{ $deals_id }}">

            <div class="row">
                <div class="col-md-12">

                    {{-- Payment Date --}}
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Payment Date</label>
                        <div class="col-md-9">
                            <input type="text"
                                class="form-control fc-datepicker"
                                name="payment_date"
                                placeholder="DD/MM/YYYY"
                                value="{{ old('payment_date') }}"
                                required
                                autocomplete="off">
                            @error('payment_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Amount --}}
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Amount</label>
                        <div class="col-md-9">
                            <input type="number"
                                class="form-control"
                                name="amount"
                                placeholder="Enter amount"
                                value="{{ old('amount') }}"
                                required>
                            @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Payment Method</label>
                        <div class="col-md-9">
                            <input type="text"
                                class="form-control"
                                name="payment_method"
                                placeholder="e.g. Cash, Bank, Cheque"
                                value="{{ old('payment_method') }}">
                            @error('payment_method')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Notes</label>
                        <div class="col-md-9">
                            <textarea class="form-control"
                                name="notes"
                                rows="3"
                                placeholder="Additional notes">{{ old('notes') }}</textarea>
                            @error('notes')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-end" style="gap: 10px;">
                            <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-dark ">
                                <i class="fe fe-upload me-2"></i>
                                Save Payment
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </form>
    </div>
</div>

