<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="index.html">
                <img src="{{ asset('uploads/'. $settings->logo) }}" class="header-brand-img desktop-logo" alt="logo" style="width: 20%; margin-left: 20px;">
                <img src="{{ asset('uploads/'. $settings->logo) }}" class="header-brand-img toggle-logo" alt="logo" style="width: 20%; margin-left: 20px;">
                <img src="{{ asset('uploads/'. $settings->logo) }}" class="header-brand-img light-logo" alt="logo" style="width: 20%; margin-left: 20px;">
                <img src="{{ asset('uploads/'. $settings->logo) }}" class="header-brand-img light-logo1" alt="logo" style="width: 20%; margin-left: 20px;">
            </a>
            {{-- <img src="{{ asset('uploads/'. $settings->logo) }}" class="header-brand-img" alt="logo" style="width: 20%">
                <h4>{{ $settings->website_name }}</h4> --}}
        </div>

        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>

            @php
                $company_deals_routes = ['adminCompanyDealsList','adminCompanyDealsView','adminCompanyDealsCreateOrEdit','adminDealsPayment', 'adminDealsPaymentCreateOrEdit'];
                $earning_routes = ['adminEarningList', 'adminEarningCreateOrEdit','adminEarningView'];
                $salary_routes = ['adminSalaryExpense', 'adminSalaryExpenseCreateOrEdit','adminSalaryExpenseView'];
                $incentive_routes = ['adminIncentiveExpense', 'adminIncentiveExpenseCreateOrEdit','adminIncentiveExpenseView'];
                $office_expense_routes = ['adminOfficeExpense', 'adminOfficeExpenseCreateOrEdit','adminOfficeExpenseView'];

                $is_company_deals_active = Route::is($company_deals_routes);
                $is_earning_route_active = Route::is($earning_routes);
                $is_earning_active = $is_company_deals_active || $is_earning_route_active;

                $is_salary_active = Route::is($salary_routes);
                $is_incentive_active = Route::is($incentive_routes);
                $is_office_expense_active = Route::is($office_expense_routes);
                $is_expense_active = $is_salary_active || $is_incentive_active || $is_office_expense_active;
            @endphp


            {{-- Permission --}}
            @php
                
                $user = auth()->user();
                $canManageDashboard   = $user->hasPermission('manage_dashboard');
                $canManageSettings    = $user->hasPermission('manage_settings');
                $canManageCompanies   = $user->hasPermission('manage_companies');
                $canManageEarnings    = $user->hasPermission('manage_earnings');
                $canManageDepartment  = $user->hasPermission('manage_department');
                $canManageDesignation = $user->hasPermission('manage_designation');
                $canManageEmployee    = $user->hasPermission('manage_employee');
                $canManageExpense     = $user->hasPermission('manage_expense');
                $canManageAdmin       = $user->hasPermission('manage_admin');
                $canManagePermission  = $user->hasPermission('manage_permission');
                $canManageRoleAccess  = $user->hasPermission('manage_role_access');
            @endphp
            <ul class="side-menu">

                {{-- DASHBOARD & SETTINGS --}}
                @if($canManageDashboard || $canManageSettings)
                    <li class="sub-category">
                        <h3>Dashboard & Settings</h3>
                    </li>
                    @if($canManageDashboard)
                    <li class="slide">
                        <a class="side-menu__item has-link {{ Route::is('adminDashboard') ? 'active' : '' }}" href="{{ route('adminDashboard') }}">
                            <i class="side-menu__icon fe fe-home"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                    @endif
                    @if($canManageSettings)
                    <li class="slide">
                        <a class="side-menu__item has-link {{ Route::is('adminSettings') ? 'active' : '' }}" href="{{ route('adminSettings') }}">
                            <i class="side-menu__icon fe fe-sliders"></i>
                            <span class="side-menu__label">Settings</span>
                        </a>
                    </li>
                    @endif
                @endif

                @if($canManageCompanies)
                    {{-- COMPANY & PARTNERS --}}
                    <li class="sub-category">
                        <h3>Company & Partners</h3>
                    </li>
                    @if($canManageCompanies)
                    <li class="slide">
                        <a class="side-menu__item has-link {{ Route::is('adminCompanyList', 'adminCompanyCreateOrEdit', 'adminCompanyView') ? 'active' : '' }}" href="{{ route('adminCompanyList') }}">
                            <i class="side-menu__icon fe fe-layers"></i>
                            <span class="side-menu__label">Companies</span>
                        </a>
                    </li>
                    @endif
                @endif

                @if ($canManageEarnings)
                    {{-- FINANCE & EARNING --}}
                    <li class="sub-category">
                        <h3>Finance & Earning</h3>
                    </li>
                    @if ($canManageEarnings)
                    <li class="slide {{ $is_earning_active ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ $is_earning_active ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fe fe-trending-up"></i>
                            <span class="side-menu__label">Earnings</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="slide-menu">
                            <li>
                                <a href="{{ route('adminCompanyDealsList') }}" class="slide-item {{ $is_company_deals_active ? 'active' : '' }}">
                                    <i class="fe fe-briefcase me-2"></i> Company Deals
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('adminEarningList') }}" class="slide-item {{ $is_earning_route_active ? 'active' : '' }}">
                                    <i class="fe fe-dollar-sign me-2"></i> Earnings
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                @endif

                @if ($canManageDepartment || $canManageDesignation || $canManageEmployee)
                    {{-- HUMAN RESOURCES --}}
                    <li class="sub-category">
                        <h3>Human Resources</h3>
                    </li>
                    @if ($canManageDepartment)
                        <li class="slide">
                            <a class="side-menu__item has-link {{ Route::is('adminDepartment', 'adminDepartmentCreateOrEdit') ? 'active' : '' }}" href="{{ route('adminDepartment') }}">
                                <i class="side-menu__icon fe fe-grid"></i>
                                <span class="side-menu__label">Departments</span>
                            </a>
                        </li>
                    @endif
                    @if ($canManageDesignation)
                    <li class="slide">
                        <a class="side-menu__item has-link {{ Route::is('adminDesignation', 'adminDesignationCreateOrEdit') ? 'active' : '' }}" href="{{ route('adminDesignation') }}">
                            <i class="side-menu__icon fe fe-briefcase"></i>
                            <span class="side-menu__label">Designations</span>
                        </a>
                    </li>
                    @endif
                    @if ($canManageEmployee)
                    <li class="slide">
                        <a class="side-menu__item has-link {{ Route::is('adminEmployeeList', 'adminEmployeeCreateOrEdit','adminEmployeeView') ? 'active' : '' }}" href="{{ route('adminEmployeeList') }}">
                            <i class="side-menu__icon fe fe-users"></i>
                            <span class="side-menu__label">Employees</span>
                        </a>
                    </li>
                    @endif
                @endif

                
                @if ($canManageExpense)
                    {{-- EXPENSE MANAGEMENT --}}
                    <li class="sub-category">
                        <h3>Expense Management</h3>
                    </li>
                    @if ($canManageExpense)
                    <li class="slide {{ $is_expense_active ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ $is_expense_active ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fe fe-dollar-sign"></i>
                            <span class="side-menu__label">Expenses</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="slide-menu">
                            <li>
                                <a href="{{ route('adminSalaryExpense') }}" class="slide-item {{ $is_salary_active ? 'active' : '' }}">
                                    <i class="fe fe-credit-card me-2"></i> Salary
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('adminIncentiveExpense') }}" class="slide-item {{ $is_incentive_active ? 'active' : '' }}">
                                    <i class="fe fe-award me-2"></i> Incentives
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('adminOfficeExpense') }}" class="slide-item {{ $is_office_expense_active ? 'active' : '' }}">
                                    <i class="fe fe-briefcase me-2"></i> Office Expenses
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                @endif
                
                @if ($canManageAdmin || $canManagePermission || $canManageRoleAccess)
                    {{-- ADMINISTRATION & ACCESS CONTROL --}}
                    <li class="sub-category">
                        <h3>Administration & Access Control</h3>
                    </li>
                    @if ($canManageAdmin)
                    <li class="slide">
                        <a class="side-menu__item has-link {{ Route::is('adminUserList', 'adminUserCreateOrEdit') ? 'active' : '' }}" href="{{ route('adminUserList') }}">
                            <i class="side-menu__icon fe fe-user-check"></i>
                            <span class="side-menu__label">Admin Users</span>
                        </a>
                    </li>
                    @endif
                    @if ($canManagePermission)
                    <li class="slide">
                        <a class="side-menu__item has-link {{ Route::is('adminPermission','adminPermissionCreateOrEdit') ? 'active' : '' }}" href="{{ route('adminPermission') }}">
                            <i class="side-menu__icon fe fe-lock"></i>
                            <span class="side-menu__label">Permissions</span>
                        </a>
                    </li>
                    @endif
                    @if ($canManageRoleAccess)
                    <li class="slide">
                        <a class="side-menu__item has-link {{ Route::is('adminRoleAccess','adminRoleAccessCreateOrEdit') ? 'active' : '' }}" href="{{ route('adminRoleAccess') }}">
                            <i class="side-menu__icon fe fe-shield"></i>
                            <span class="side-menu__label">Role Access</span>
                        </a>
                    </li>
                     @endif
                @endif
                

            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </div>
</div>

