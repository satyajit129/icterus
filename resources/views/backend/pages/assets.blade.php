@extends('backend.layouts.master')

@section('title', 'Assets')

@section('custom_css')
@endsection


@section('content')
    <div class="page-header">
        <h1 class="page-title">Assets</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Assets</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Assets</h3>
                    <a href="{{ route('adminAssetCreateOrEdit') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add
                            Asset</button>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row row-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="wd-5p border-bottom-0">#</th>
                                            <th class="wd-15p border-bottom-0">Asset</th>
                                            <th class="wd-25p border-bottom-0">Description</th>
                                            <th class="wd-15p border-bottom-0">Category</th>
                                            <th class="wd-10p border-bottom-0">Cost</th>
                                            <th class="wd-10p border-bottom-0">Code</th>
                                            <th class="wd-15p border-bottom-0">Purchase Date</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assets as $key => $asset)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $asset->name }}</td>
                                                <td>{{ $asset->description ?? '-' }}</td>
                                                <td>{{ $asset->category->name ?? '-' }}</td>
                                                <td>{{ number_format($asset->cost, 2) }}</td>
                                                <td>{{ $asset->code }}</td>
                                                <td>{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d-m-Y') }}</td>
                                                <td>
                                                    <a href="{{ route('adminAssetCreateOrEdit', $asset->id) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>

                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                        data-url="{{ route('adminAssetDelete', ['id' => $asset->id]) }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete">
                                                        <i class="fe fe-trash-2"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Asset Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal effect-scale" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title" id="deleteModalLabel">Delete Confirmation</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Asset?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                var deleteUrl = $(this).data('url');
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>
@endsection
