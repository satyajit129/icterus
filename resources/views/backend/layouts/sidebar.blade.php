<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="index.html">
                <img src="../assets/images/brand/logo-white.png" class="header-brand-img desktop-logo" alt="logo">
                <img src="../assets/images/brand/icon-white.png" class="header-brand-img toggle-logo" alt="logo">
                <img src="../assets/images/brand/icon-dark.png" class="header-brand-img light-logo" alt="logo">
                <img src="../assets/images/brand/logo-dark.png" class="header-brand-img light-logo1" alt="logo">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>Main</h3>
                </li>

                <!-- Dashboard -->
                <li class="slide">
                    <a class="side-menu__item has-link {{ Route::is('adminDashboard') ? 'active' : '' }}"
                        href="{{ route('adminDashboard') }}">
                        <i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                <!-- Settings -->
                <li class="slide">
                    <a class="side-menu__item has-link {{ Route::is('adminSettings') ? 'active' : '' }}"
                        href="{{ route('adminSettings') }}">
                        <i class="side-menu__icon fe fe-sliders"></i>
                        <span class="side-menu__label">Settings</span>
                    </a>
                </li>

                <li class="sub-category">
                    <h3>Administration</h3>
                </li>
                <!-- Company -->
                <li class="slide">
                    <a class="side-menu__item has-link {{ Route::is('adminCompanyList', 'adminCompanyCreateOrEdit', 'adminCompanyView') ? 'active' : '' }}"
                        href="{{ route('adminCompanyList') }}">
                        <i class="side-menu__icon fe fe-layers"></i> 
                        <span class="side-menu__label">Company</span>
                    </a>
                </li>
                @php
                    $company_deals_routes = ['adminCompanyDealsList','adminCompanyDealsView','adminCompanyDealsCreateOrEdit'];

                    $is_company_deals_active = Route::is($company_deals_routes);

                    $is_earning_active = $is_company_deals_active;
                @endphp
                <li class="slide {{ $is_earning_active ? 'is-expanded' : '' }}">
                    <a class="side-menu__item {{ $is_earning_active ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0)">
                        <i class="side-menu__icon fe fe-trending-up"></i>
                        <span class="side-menu__label">Earning Purpose</span>
                        <i class="angle fe fe-chevron-right"></i>
                    </a>

                    <ul class="slide-menu">
                        <li>
                            <a href="{{ route('adminCompanyDealsList') }}" class="slide-item {{ $is_company_deals_active ? 'active' : '' }}">
                                <i class="fe fe-briefcase me-2"></i> Company Deals
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adminCompanyDealsList') }}" class="slide-item {{ $is_company_deals_active ? 'active' : '' }}">
                                <i class="fe fe-briefcase me-2"></i> Company Deals
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Department -->
                <li class="slide">
                    <a class="side-menu__item has-link {{ Route::is('adminDepartment', 'adminDepartmentCreateOrEdit') ? 'active' : '' }}"
                        href="{{ route('adminDepartment') }}">
                        <i class="side-menu__icon fe fe-grid"></i>
                        <span class="side-menu__label">Department</span>
                    </a>
                </li>

                <!-- Designation -->
                <li class="slide">
                    <a class="side-menu__item has-link {{ Route::is('adminDesignation', 'adminDesignationCreateOrEdit') ? 'active' : '' }}"
                        href="{{ route('adminDesignation') }}">
                        <i class="side-menu__icon fe fe-briefcase"></i>
                        <span class="side-menu__label">Designation</span>
                    </a>
                </li>

                <!-- Employees -->
                <li class="slide">
                    <a class="side-menu__item has-link {{ Route::is('adminEmployeeList', 'adminEmployeeCreateOrEdit','adminEmployeeView') ? 'active' : '' }}"
                        href="{{ route('adminEmployeeList') }}">
                        <i class="side-menu__icon fe fe-users"></i>
                        <span class="side-menu__label">Employees</span>
                    </a>
                </li>
                @php
                    $salary_routes = ['adminSalaryExpense', 'adminSalaryExpenseCreateOrEdit','adminSalaryExpenseView'];
                    $incentive_routes = ['adminIncentiveExpense', 'adminIncentiveExpenseCreateOrEdit','adminIncentiveExpenseView'];
                    $office_expense_routes = ['adminOfficeExpense', 'adminOfficeExpenseCreateOrEdit','adminOfficeExpenseView'];

                    $is_salary_active = Route::is($salary_routes);
                    $is_incentive_active = Route::is($incentive_routes);
                    $is_office_expense_active = Route::is($office_expense_routes);

                    $is_expense_active = $is_salary_active || $is_incentive_active || $is_office_expense_active;
                @endphp

                <li class="slide {{ $is_expense_active ? 'is-expanded' : '' }}">
                    <a class="side-menu__item {{ $is_expense_active ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0)">
                        <i class="side-menu__icon fe fe-dollar-sign"></i>
                        <span class="side-menu__label">Expense</span>
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
                                <i class="fe fe-award me-2"></i> Incentive
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adminOfficeExpense') }}" class="slide-item {{ $is_office_expense_active ? 'active' : '' }}">
                                <i class="fe fe-briefcase me-2"></i> Office Expense
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                    height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </div>
</div>
