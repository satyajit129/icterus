@extends('backend.layouts.master')
@section('title', 'Facebook Ad Accounts')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Facebook Ad Accounts</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Ad Accounts</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fe fe-briefcase"></i> Ad Accounts</h3>
                    <form action="{{ route('adminFacebookAdAccountsSync') }}" method="POST">
                        @csrf
                        <button class="btn btn-primary"><i class="fe fe-refresh-cw"></i> Sync from Facebook</button>
                    </form>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('adminFacebookAdAccounts') }}" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search by name, id or business" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="active" class="form-control">
                                    <option value="">All</option>
                                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-secondary w-100"><i class="fe fe-search"></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>GID</th>
                                    <th>Account ID</th>
                                    <th>Name</th>
                                    <th>Business</th>
                                    <th>Currency</th>
                                    <th>Timezone</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adAccounts as $acc)
                                    <tr>
                                        <td><code>{{ $acc->ad_account_gid }}</code></td>
                                        <td>{{ $acc->ad_account_id }}</td>
                                        <td>{{ $acc->name }}</td>
                                        <td>{{ $acc->business_name ? $acc->business_name . ' (' . $acc->business_id . ')' : '—' }}
                                        </td>
                                        <td>{{ $acc->currency }}</td>
                                        <td>{{ $acc->timezone_name }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $acc->is_active ? 'success' : 'secondary' }}">{{ $acc->is_active ? 'Yes' : 'No' }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('adminFacebookAdAccountDelete', $acc->id) }}"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this ad account record?')">
                                                <i class="fe fe-trash-2"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fe fe-briefcase" style="font-size:2rem"></i>
                                            <div class="mt-2">No ad accounts found. Click "Sync from Facebook" to import.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>Showing {{ $adAccounts->firstItem() ?? 0 }} to {{ $adAccounts->lastItem() ?? 0 }} of
                            {{ $adAccounts->total() }} accounts</div>
                        <div>{{ $adAccounts->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
