<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('home') }}" class="brand-link text-center">
        <b>{{ Session::get('company_name') }}</b>
    </a>
    @php
        $user = auth()->user()->id;
        $userInstance = App\Models\User::find($user);
    @endphp

    @php

        use App\Models\MenuPermission;
        use App\Models\SubMenuPermission;

        $user = auth()->user()->role;
        $userInstance = App\Models\User::find($user);

        $menu_permission = MenuPermission::where('role', $user)->get()->toArray();

        $sub_menu_permission = SubMenuPermission::where('role', $user)->get()->toArray();

    @endphp

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('image/admin_layout/avatar5.png') }}" class="img-thumbnail elevation-2"
                    alt="Admin Photo">
            </div>

            <div class="info">
                <a href="#" class="d-block">
                    <b class="text-info">{{ auth()->user()->name ? auth()->user()->name : '' }}</b>
                    <br />@<small class="text-success">{{ auth()->user()->role ? auth()->user()->role : '' }}</small>
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="fas fa-home fa-lg "></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>

                {{-- <li class="nav-item {{((isset($main_menu) && $main_menu=='hrm')?'menu-open':'')}}">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-users "></i>
                      <p>
                        HRM
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li>
                            <a href="{{ route('department-list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='department-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Department</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('branch-list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='branch-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Branch Office</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('section-list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='section-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Section</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('designation-list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='designation-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Designation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('grade-list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='grade-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Grade</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('paymentType-list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='paymentType-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Payment Type</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('schedule-list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='schedule-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Schedule</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee-create') }}" class="nav-link {{((isset($child_menu) && $child_menu=='employee-create')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Employee Create</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-employee') }}" class="nav-link {{((isset($child_menu) && $child_menu=='manage-employee')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Employee</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('employee-performance')}}" class="nav-link {{((isset($child_menu) && $child_menu=='employee-performance')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Employee Performance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('attendance-entry')}}" class="nav-link {{((isset($child_menu) && $child_menu=='attendance-entry')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Attendance Entry</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('attendance-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='attendance-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Attendance List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('attendance-monthly-report')}}" class="nav-link {{((isset($child_menu) && $child_menu=='attendance-monthly-report')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Monthly Attendance Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('job-card')}}" class="nav-link {{((isset($child_menu) && $child_menu=='job-card')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Job Card Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('holiday') }}" class="nav-link {{((isset($child_menu) && $child_menu=='holiday')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Holiday</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('leave-type-list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='leave-type-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave Type</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('leave') }}" class="nav-link {{((isset($child_menu) && $child_menu=='leave')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave Entry</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('leave-report') }}" class="nav-link {{((isset($child_menu) && $child_menu=='leave-report')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('employee-salary')}}" class="nav-link {{((isset($child_menu) && $child_menu=='employee-salary')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Salary Generate</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('employee-salary-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='employee-salary-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Salary Report</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                @if (array_search('income', array_column($menu_permission, 'menu_slug')) !== false)
                    <li
                        class="nav-item {{ isset($child_menu) && ($child_menu == 'create-income' || $child_menu == 'income-statement-list' || $child_menu == 'income-list') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($child_menu) && ($child_menu == 'create-income' || $child_menu == 'income-list') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>
                                Income
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('create-income') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'create-income' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Entry</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('income-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'income-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('income-statement-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'income-statement-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Income Statement</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (array_search('expense', array_column($menu_permission, 'menu_slug')) !== false)
                    <li
                        class="nav-item {{ isset($child_menu) && ($child_menu == 'create-expense' || $child_menu == 'expense-list' || $child_menu == 'advance-expense' || $child_menu == 'advance-cheque' || $child_menu == 'requisition-list' || $child_menu == 'reject-list') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($child_menu) && ($child_menu == 'create-expense' || $child_menu == 'expense-list' || $child_menu == 'advance-expense' || $child_menu == 'advance-cheque' || $child_menu == 'reject-list') ? 'active' : '' }}">
                            <i class="fa fa-money-bill nav-icon"></i>
                            <p>
                                Expense
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('advance-expense') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'advance-expense' ? 'active' : '' }} ">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Advance Expense</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('advance-cheque') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'advance-cheque' ? 'active' : '' }} ">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Advance Cheque</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('create-expense') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'create-expense' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Expense Entry</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('requisition-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'requisition-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Expense Requisition List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('expense-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'expense-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Expense List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reject-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'reject-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Reject Expense List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (array_search('accounts', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'accounts' ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-dollar-sign"></i>
                            <p>
                                Accounts
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin') --}}
                            {{-- <li class="nav-item">
                            <a href="{{ route('petty-cash') }}" class="nav-link {{((isset($child_menu) && $child_menu=='petty-cash')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Petty cash</p>
                            </a>
                        </li> --}}
                            {{-- @endif --}}
                            <li
                                class="nav-item {{ isset($child_menu) && ($child_menu == 'daily-status' || $child_menu == 'ledger' || $child_menu == 'receipt_and_payment_statement' || $child_menu == 'project-received' || $child_menu == 'completion-project-received' || $child_menu == 'payable-due-amount') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ isset($child_menu) && ($child_menu == 'daily-status' || $child_menu == 'ledger' || $child_menu == 'project-received' || $child_menu == 'completion-project-received' || $child_menu == 'payable-due-amount') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Report
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item {{ request()->is('ledger') ? 'menu-open' : '' }}">
                                        <a href="{{ route('ledger') }}"
                                            class="nav-link {{ request()->is('ledger') ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Daily Ledger</p>
                                        </a>
                                    </li>

                                    <li class="nav-item {{ request()->is('head.ledger') ? 'menu-open' : '' }}">
                                        <a href="{{ route('head.ledger') }}"
                                            class="nav-link {{ request()->is('head.ledger') ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Headwise Ledger</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('daily-status') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'daily-status' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Daily Status</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('project-received') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'project-received' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Project Received Amount</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('completion-project-received') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'completion-project-received' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Completion Project Received</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('payable-due-amount') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'payable-due-amount' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Payable Due Amount</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ request()->is('receipt_and_payment_statement') ? 'menu-open' : '' }}">
                                        <a href="{{ route('receipt_and_payment_statement') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'receipt_and_payment_statement' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Receipt and Payment Statement</p>
                                        </a>
                                    </li>
                                    {{-- <li class="nav-item {{ request()->is('balance_sheet') ? 'menu-open' : '' }}">
                                        <a href="{{ route('balance_sheet') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'balance_sheet' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Balance Sheet</p>
                                        </a>
                                    </li> --}}
                                </ul>
                            </li>
                            {{-- <li class="nav-item {{((isset($child_menu) && ($child_menu=='deposit-entry' || $child_menu=='deposit-list'))?'menu-open':'')}}">
                            <a href="#" class="nav-link {{((isset($child_menu) && ($child_menu=='deposit-entry' || $child_menu=='deposit-list'))?'active':'')}}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>
                                Deposit
                                <i class="right fas fa-angle-left"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                              <li class="nav-item">
                                <a href="{{route('deposit-entry')}}" class="nav-link {{((isset($child_menu) && $child_menu=='deposit-entry')?'active':'')}}">
                                  <i class="far fa-dot-circle nav-icon"></i>
                                  <p>Entry</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="{{route('deposit-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='deposit-list')?'active':'')}}">
                                  <i class="far fa-dot-circle nav-icon"></i>
                                  <p>List</p>
                                </a>
                              </li>

                            </ul>
                        </li>

                        <li class="nav-item {{((isset($child_menu) && ($child_menu=='create-garanty' || $child_menu=='garanty-list'))?'menu-open':'')}}">
                            <a href="#" class="nav-link {{((isset($child_menu) && ($child_menu=='create-garanty' || $child_menu=='garanty-list'))?'active':'')}}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>
                                Bank Granty
                                <i class="right fas fa-angle-left"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('create-garanty')}}" class="nav-link {{((isset($child_menu) && $child_menu=='create-garanty')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Entry</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('garanty-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='garanty-list')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{((isset($child_menu) && ($child_menu=='create-pg' || $child_menu=='pg-list'))?'menu-open':'')}}">
                            <a href="#" class="nav-link {{((isset($child_menu) && ($child_menu=='create-pg' || $child_menu=='pg-list'))?'active':'')}}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>
                                PG Amount
                                <i class="right fas fa-angle-left"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('create-pg')}}" class="nav-link {{((isset($child_menu) && $child_menu=='create-pg')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Entry</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('pg-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='pg-list')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{((isset($child_menu) && ($child_menu=='create-fdr' || $child_menu=='fdr-list'))?'menu-open':'')}}">
                            <a href="#" class="nav-link {{((isset($child_menu) && ($child_menu=='create-fdr' || $child_menu=='fdr-list'))?'active':'')}}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>
                                FDR Amount
                                <i class="right fas fa-angle-left"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('create-fdr')}}" class="nav-link {{((isset($child_menu) && $child_menu=='create-fdr')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Entry</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('fdr-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='fdr-list')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{((isset($child_menu) && ($child_menu=='create-security' || $child_menu=='security-list'))?'menu-open':'')}}">
                            <a href="#" class="nav-link {{((isset($child_menu) && ($child_menu=='create-security' || $child_menu=='security-list'))?'active':'')}}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>
                                Security Money
                                <i class="right fas fa-angle-left"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('create-security')}}" class="nav-link {{((isset($child_menu) && $child_menu=='create-security')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Entry</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('security-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='security-list')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                            {{-- <li class="nav-item {{((isset($child_menu) && ($child_menu=='create-loan' || $child_menu=='loan-list'))?'menu-open':'')}}">
                            <a href="#" class="nav-link {{((isset($child_menu) && ($child_menu=='create-loan' || $child_menu=='loan-list'))?'active':'')}}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>
                                Loan Status
                                <i class="right fas fa-angle-left"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('create-loan')}}" class="nav-link {{((isset($child_menu) && $child_menu=='create-loan')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Entry</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('loan-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='loan-list')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                            <li class="nav-item {{ request()->is('account-category') ? 'menu-open' : '' }}">
                                <a href="{{ route('account-category') }}"
                                    class="nav-link {{ request()->is('account-category') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Account Main Head</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('account-head') ? 'menu-open' : '' }}">
                                <a href="{{ route('account-head') }}"
                                    class="nav-link {{ request()->is('account-head') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Account Sub Head</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('account-opening-balance') ? 'menu-open' : '' }}">
                                <a href="{{ route('account-opening-balance') }}"
                                    class="nav-link {{ request()->is('account-opening-balance') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Head Wise Opening/Previous Balance</p>
                                </a>
                            </li>


                        </ul>
                    </li>
                @endif
                {{-- @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin') --}}
                @if (array_search('fund_transfer', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item  {{ isset($main_menu) && $main_menu == 'Fund Transfer' ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-money-bill"></i>
                            <p>
                                Fund Transfer
                            </p>
                            <i class="right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('fund-transfer') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'transfer_entry' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Entry</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transfer-log') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'transfer-log' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Transfer Log</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                            <a href="{{route('create-income')}}" class="nav-link {{((isset($child_menu) && $child_menu=='create-income')?'active':'')}}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Capital</p>
                            </a>
                        </li> --}}

                        </ul>
                    </li>
                @endif
                <li
                    class="nav-item  {{ isset($main_menu) && $main_menu == 'Head_to_head_transfer' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-money-bill"></i>
                        <p>
                            Head To Head Transfer
                        </p>
                        <i class="right fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('head-to-head-transfer') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'Head_to_head_transfer_entry' ? 'active' : '' }}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Entry</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('head-to-head-transfer-log') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'Head_to_head_transfer_log' ? 'active' : '' }}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Head To Head Transfer Log</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                        <a href="{{route('create-income')}}" class="nav-link {{((isset($child_menu) && $child_menu=='create-income')?'active':'')}}">
                            <i class="far fa-dot-circle nav-icon"></i>
                            <p>Capital</p>
                        </a>
                    </li> --}}

                    </ul>
                </li>
                @if (array_search('bank_info', array_column($menu_permission, 'menu_slug')) !== false)
                    <li
                        class="nav-item {{ isset($child_menu) && isset($main_menu) && ($child_menu == 'bank_info_create' || $child_menu == 'bank_list' || $child_menu == 'account_list') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($child_menu) && ($child_menu == 'bank_list' || $child_menu == 'bank_info_create' || $child_menu == 'account_list') ? 'active' : '' }}">
                            <i class="far fa-building nav-icon"></i>
                            <p>
                                Bank Information
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('bank-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'bank_list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Bank</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('account-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'account_list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Bank Accounts</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                            <a href="{{route('bank-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='bank_info_list')?'active':'')}}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li> --}}
                        </ul>
                    </li>
                @endif

                @if (array_search('fund', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'fund' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'fund' ? 'active' : '' }} ">
                            <i class="nav-icon fa fa-money-check"></i>
                            <p>
                                Fund
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('fund-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'fund-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Fund Type</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('fund-opening-balance') ? 'menu-open' : '' }}">
                                <a href="{{ route('fund-balance-entry') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'fund-opening-balance' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Opening Balance </p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('balance-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'balance-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Fund Current Balance</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('fund-transaction') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'fund-ledger' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Fund Transaction History</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                {{-- @endif
                @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin') --}}
                @if (array_search('project', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item  {{ isset($main_menu) && $main_menu == 'project' ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-bars nav-icon"></i>

                            {{-- <i class="fas fa-database  nav-icon"></i> --}}
                            <p>
                                Project
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('project') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'main_project' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Project</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                            <a href="{{route('sub-project')}}" class="nav-link {{((isset($child_menu) && $child_menu=='sub_project')?'active':'')}}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Sub Project</p>
                            </a>
                        </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('project-ledger') }}" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Project Ledger</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                            <a href="{{route('create-income')}}" class="nav-link {{((isset($child_menu) && $child_menu=='create-income')?'active':'')}}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Capital</p>
                            </a>
                        </li> --}}

                        </ul>
                    </li>
                @endif
                <li class="nav-item  {{ isset($main_menu) && $main_menu == 'Capital' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-database  nav-icon"></i>
                        <p>
                        Capital
                        <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- <li class="nav-item">
                            <a href="{{ route('capital-category') }}" class="nav-link {{ isset($child_menu) && $child_menu == 'capital_category' ? 'active' : '' }}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Category</p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ route('capital-list') }}" class="nav-link {{ isset($child_menu) && $child_menu == 'capital-list' ? 'active' : '' }}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Capital</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('transfer-index') }}" class="nav-link {{ isset($child_menu) && $child_menu == 'capital_transfer' ? 'active' : '' }}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Capital Transfer</p>
                            </a>
                        </li>
                    </ul>
                </li>


                {{-- @endif --}}
                @if (array_search('loan', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'loans' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'loans' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p>
                                Loan
                            </p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('create-loan') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'create-loan' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Loan Entry</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('loan-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'loan-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Loan List</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('loan-collection-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'loan-collection-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p> Loan Collection </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('receivable-loan-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'receivable-loan-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Receivable Loan List</p>
                                </a>
                            </li>

                            {{-- <li class="nav-item">
                            <a href="{{route('loan_report')}}" class="nav-link {{((isset($child_menu) && $child_menu=='loan_report')?'active':'')}}">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p> Loan Report</p>
                            </a>
                        </li> --}}
                        </ul>
                    </li>
                @endif
                @if (array_search('investment', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'investment' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'investment' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-seedling"></i>
                            <p>
                                Consumer Investment
                            </p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- <li class="nav-item">
                                <a href="{{ route('create_investment') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'create_investment' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Investment Entry</p>
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('investment_list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'investment_list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Consumer Investor List</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('collect-invest-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'collect-invest-list' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p> Invest Amount Collection List</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('investment_report') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'investment_report' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p> Consumer Investor Statement</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- @if (array_search('investment', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'consumer_investors' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'consumer_investors' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-seedling"></i>
                            <p>
                                Consumer Investment
                            </p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('consumer_investors') }}" class="nav-link {{((isset($child_menu) && $child_menu=='consumer_investors')?'active':'')}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Consumer Investor list</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('collect_invest_index') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'collect_invest_index' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Invest Amount Collection</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('investment_report') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'investment_report' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p> Investment Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif --}}
                @if (array_search('supplier', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'supplier' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'supplier' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large "></i>
                            <p>
                                Supplier
                            </p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('supplier-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'supplier-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Supplier List
                                    </p>
                                </a>
                            </li>

                            {{-- <li class="nav-item">
                            <a href="{{ route('create_work_order') }}" class="nav-link {{((isset($child_menu) && $child_menu=='work-order-create')?'active':'')}}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>
                                Work Order Entry
                              </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('work-order-list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='work-order-list')?'active':'')}}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>
                                Work Order List
                              </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('supplier-payment') }}" class="nav-link {{((isset($child_menu) && $child_menu=='supplier-payment')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Supplier Payment</p>
                            </a>
                        </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('supplier-payment-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'supplier-payment-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Supplier Payment List</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('supplier-due') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'supplier-due' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Supplier Due List</p>
                                </a>
                            </li>

                            {{--
                            <li class="nav-item">
                            <a href="{{ route('supplier-voucher') }}" class="nav-link ">
                              <i class="far fa-circle nav-icon"></i>
                              <p>
                                Supplier Credit Voucher
                              </p>
                            </a>
                        </li>
                         --}}
                        </ul>
                    </li>
                @endif
                @if (array_search('purchase', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'purchase' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'purchase' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cart-arrow-down"></i>
                            <p>
                                Purchase
                            </p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('add-purchase') }}"
                                    class="nav-link  {{ isset($child_menu) && $child_menu == 'add-purchase' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Purchase</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('purchase') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'purchase' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Purchase List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (array_search('inventory', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'inventory' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'inventory' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p>
                                Inventory
                            </p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('item-category') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'item-category' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Material Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('item-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'item-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Material List</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stock-report') }}"
                                    class="nav-link  {{ isset($child_menu) && $child_menu == 'stock-report' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Stock Report</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                            <a href="{{url('stock-transfer-list')}}" class="nav-link {{((isset($child_menu) && $child_menu=='stock-transfer-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transfer list</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('stock-transfer')}}" class="nav-link {{((isset($child_menu) && $child_menu=='stock-transfer')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transfer</p>
                            </a>
                        </li> --}}

                            <li class="nav-item">
                                <a href="{{ url('stock-out') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'stock-out' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Stock Out</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('stock-out-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'stock-out-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Stock Out List</p>
                                </a>
                            </li>


                        </ul>
                    </li>
                @endif
                @if (array_search('asset-management', array_column($menu_permission, 'menu_slug')) !== false)
                    <li
                        class="nav-item {{ isset($main_menu) && $main_menu == 'asset_management' ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-layer-group"></i>
                            <p>
                                Asset Management
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('current_asset_list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'current_asset' ? 'active' : '' }}">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Current Asset </p>
                                </a>
                            </li>
                            <li
                                class="nav-item {{ isset($child_menu) &&
                                ($child_menu == 'asset_category_list' ||
                                    $child_menu == 'asset_group_list' ||
                                    $child_menu == 'asset-list' ||
                                    $child_menu == 'asset_purchase_list' ||
                                    $child_menu == 'asset_expense' ||
                                    $child_menu == 'asset_stock' ||
                                    $child_menu == 'asset_assign_list' ||
                                    $child_menu == 'damage_asset_list' ||
                                    $child_menu == 'asset_lost_list' ||
                                    $child_menu == 'asset_liquidation_list' ||
                                    $child_menu == 'asset_revoke_list')
                                    ? 'menu-open'
                                    : '' }}">
                                <a href="#"
                                    class="nav-link {{ isset($child_menu) &&
                                    ($child_menu == 'asset_category_list' ||
                                        $child_menu == 'asset_group_list' ||
                                        $child_menu == 'asset-list' ||
                                        $child_menu == 'asset_purchase_list' ||
                                        $child_menu == 'asset_expense' ||
                                        $child_menu == 'asset_stock' ||
                                        $child_menu == 'asset_assign_list' ||
                                        $child_menu == 'damage_asset_list' ||
                                        $child_menu == 'asset_lost_list' ||
                                        $child_menu == 'asset_liquidation_list' ||
                                        $child_menu == 'asset_revoke_list')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Fixed Asset
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li>
                                        <a href="{{ route('asset_category_list') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'asset_category_list' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Asset Category</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('asset_group_list') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'asset_group_list' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Asset Group</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('asset_list') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'asset-list' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Asset</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('asset_stock') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'asset_stock' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Assets Stock</p>
                                        </a>
                                    </li>
                                    {{-- <li>
                                    <a href="{{ route('asset_purchase_list') }}" class="nav-link {{((isset($child_menu) && $child_menu=='asset_purchase_list')?'active':'')}}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Asset Purchase</p>
                                    </a>
                                </li> --}}
                                    <li>
                                        <a href="{{ route('asset_expense') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'asset_expense' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Asset Expense</p>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('asset_assign_list') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'asset_assign_list' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Asset Assign</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('damage_asset_list') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'damage_asset_list' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Damage Assets</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('asset_lost_list') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'asset_lost_list' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Lost Assets</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('asset_liquidation_list') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'asset_liquidation_list' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Liquidation Assets</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('revoke_list') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'asset_revoke_list' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Revoke List</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </li>
                @endif
                @if (array_search('sales', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'sales' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'sales' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hourglass "></i>
                            <p>
                                Sales
                            </p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('user_type_index') }}"
                                    class="nav-link  {{ isset($child_menu) && $child_menu == 'user_type' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>User Type</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('land_sale_employee') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'land_sale_employee' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Director/Co-ordinator/Shareholder Entry</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sales_related_incentive') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'sales_related_incentive' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sales Related Incentive List</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('incentive_stock_list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'incentive_payment_list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Head Wise Incentive List</p>
                                </a>
                            </li>

                            <li
                                class="nav-item {{ isset($child_menu) &&
                                ($child_menu == 'sector' || $child_menu == 'road' || $child_menu == 'plot' || $child_menu == 'plot-type')
                                    ? 'menu-open'
                                    : '' }}">
                                <a href="#"
                                    class="nav-link {{ isset($child_menu) &&
                                    ($child_menu == 'sector' || $child_menu == 'road' || $child_menu == 'plot' || $child_menu == 'plot-type')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Plot Info.
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li>
                                        <a href="{{ route('sector') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'sector' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Sector</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('road') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'road' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Road</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('plot_type') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'plot-type' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Plot Size/Type</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('plot') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'plot' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Plot</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <li
                                class="nav-item {{ isset($child_menu) && ($child_menu == 'flat_floor' || $child_menu == 'flat') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ isset($child_menu) && ($child_menu == 'flat_floor' || $child_menu == 'flat') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Flat Info.
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li>
                                        <a href="{{ route('flat_floor') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'flat_floor' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Floor</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('flat') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'flat' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Flat</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            
                            <li class="nav-item {{ isset($child_menu) && in_array($child_menu, ['landshare_index', 'landshare_create', 'landshare_edit', 'landshare_show']) ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ isset($child_menu) && in_array($child_menu, ['landshare_index', 'landshare_create', 'landshare_edit', 'landshare_show']) ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Land Info.
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li>
                                        <a href="{{ route('landshareindex') }}"
                                            class="nav-link {{ isset($child_menu) && $child_menu == 'landshare_index' ? 'active' : '' }}">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Land Share List</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            
                            
                            <li>
                                <a href="{{ route('create_application_form') }}"
                                    class="nav-link  {{ isset($child_menu) && $child_menu == 'application_form' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Application Form Entry</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('land_sale_list') }}"
                                    class="nav-link  {{ isset($child_menu) && $child_menu == 'land_sale_list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Land Sale Details List</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('paid_installment_list') }}"
                                    class="nav-link  {{ isset($child_menu) && $child_menu == 'paid_installment_list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Payment List</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('installment_statement') }}"
                                    class="nav-link  {{ isset($child_menu) && $child_menu == 'installment_statement' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Installment Statement</p>
                                </a>
                            </li>
                            {{-- <li>
                            <a href="{{ route('invoice-list') }}" class="nav-link  {{((isset($child_menu) && $child_menu=='invoice-list')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Invoice List</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('create-invoice') }}" class="nav-link  {{((isset($child_menu) && $child_menu=='create-invoice')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Generate Invoice</p>
                            </a>
                        </li> --}}
                        </ul>
                    </li>
                @endif

                {{-- @if (array_search('sales', array_column($menu_permission, 'menu_slug')) !== false) --}}
                <li class="nav-item {{ isset($main_menu) && $main_menu == 'land_purchase' ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ isset($main_menu) && $main_menu == 'land_purchase' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-hourglass "></i>
                        <p>
                            Land Purchase
                        </p>
                        <i class="fas fa-angle-left right"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item {{ request()->is('save-land-purchase','land-purchase') ? 'menu-open' : '' }}">
                            <a href="{{ route('land.purchase') }}"
                                class="nav-link  {{ isset($child_menu) && $child_menu == 'land_purchase_create' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Land Purchase</p>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('land-purchase-list') ? 'menu-open' : '' }}">
                            <a href="{{ route('land_purchase_list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'land_purchase_list' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Land Purchase List</p>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('land-stock-list') ? 'menu-open' : '' }}">
                            <a href="{{ route('land_stock_list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'land_stock_list' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Land Stock</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- @endif --}}

                <li class="nav-item {{ isset($main_menu) && $main_menu == 'rent' ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ isset($main_menu) && $main_menu == 'rent' ? 'active' : '' }}">
                        <i class="nav-icon fa fa-building"></i>
                        <p>
                            Rental Management
                        </p>
                        <i class="fas fa-angle-left right"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li>
                            <a href="{{ route('property_list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'property' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Property</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('floor_list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'floor' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Floor</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('meter_list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'meter' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Meter</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('unit_create') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'create-unit' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Unit Entry</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('unit_list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'unit' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Unit List</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('renter_list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'renter' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Renter List</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rental_bill_generate') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'rental-bill-generate' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bill Generate</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rental_bill_list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'rental-bill-list' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bills</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @if (array_search('requisition', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'requisition' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'requisition' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book "></i>
                            <p>
                                Requisition
                            </p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('money-requisition') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'money-requisition' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Money Requisition List</p>
                                </a>
                            </li>

                            {{-- <li class="nav-item">
                                <a href="{{ route('material-requisition') }}" class="nav-link {{((isset($child_menu) && $child_menu=='material-requisition')?'active':'')}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Material Requisition List</p>
                                </a>
                            </li> --}}
                        </ul>
                    </li>
                @endif

                {{-- <li class="nav-item {{((isset($main_menu) && $main_menu=='work-status')?'menu-open':'')}}">
                    <a href="#" class="nav-link {{((isset($main_menu) && $main_menu=='work-status')?'active':'')}}">
                      <i class="nav-icon fas fa-hourglass "></i>
                      <p>
                        Works & Man Power
                      </p>
                      <i class="fas fa-angle-left right"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li>
                            <a href="{{ route('add-work-status') }}" class="nav-link  {{((isset($child_menu) && $child_menu=='add')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('work-status') }}" class="nav-link {{((isset($child_menu) && $child_menu=='view')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Work Status</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('man-power') }}" class="nav-link {{((isset($child_menu) && $child_menu=='man-power')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Man Power</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{((isset($main_menu) && $main_menu=='requisition')?'menu-open':'')}}">
                    <a href="#" class="nav-link {{((isset($main_menu) && $main_menu=='requisition')?'active':'')}}">
                      <i class="nav-icon fas fa-book "></i>
                      <p>
                       Requisition
                      </p>
                      <i class="fas fa-angle-left right"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li>
                            <a href="{{ route('add-money-requisition') }}" class="nav-link  {{((isset($child_menu) && $child_menu=='add-money-requisition')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Money Requisition</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('money-requisition') }}" class="nav-link {{((isset($child_menu) && $child_menu=='money-requisition')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Money Requisition</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('add-requisition') }}" class="nav-link {{((isset($child_menu) && $child_menu=='add-requisition')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Material Requisition</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('material-requisition') }}" class="nav-link {{((isset($child_menu) && $child_menu=='material-requisition')?'active':'')}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Material Requisition</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
                @if (array_search('vendor', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'vendor' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'vendor' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large "></i>
                            <p>
                                Vendor / Sub Contractor
                            </p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('vendor-payment-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'vendor-payment-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Payment List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendor-payment') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'vendor-payment' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Payment</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendor-due-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'vendor-due-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Due List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendor-project-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'vendor-project-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Vendor Project List
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendor-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'vendor-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Vendor List
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- <li class="nav-item">
                    <a href="{{ route('project') }}" class="nav-link {{((isset($main_menu) && $main_menu=='project')?'active':'')}}">
                      <i class="nav-icon fas fa-briefcase"></i>
                      <p>
                        Project Status
                      </p>
                    </a>
                </li> --}}
                @if (array_search('audit-list', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item">
                        <a href="{{ route('audit-list') }}"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'audit-list' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-database "></i>
                            <p>
                                Audit Report
                            </p>
                        </a>
                    </li>
                @endif
                @if (array_search('bill-list', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item">
                        <a href="{{ route('bill-list') }}"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'bill-list' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>
                                Bill
                            </p>
                        </a>
                    </li>
                @endif

                @if (array_search('licenses-list', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item">
                        <a href="{{ route('licenses-list') }}"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'licenses-list' ? 'active' : '' }}"
                            class="nav-link">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>
                                Licenses Status
                            </p>
                        </a>
                    </li>
                @endif

                {{-- @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')  --}}
                {{-- @if (array_search('basic_settings', array_column($menu_permission, 'menu_slug')) !== false) --}}
                <li class="nav-item {{ isset($main_menu) && $main_menu == 'basic_settings' ? 'menu-open' : '' }}">

                    <a href="#"
                        class="nav-link {{ isset($main_menu) && $main_menu == 'basic_settings' ? 'active' : '' }} ">
                        <i class="nav-icon fas fa-calculator "></i>
                        <p>
                            Basic Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li>
                            <a href="{{ route('user-list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'user' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user-permission') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'user-permission' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Users Permission</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('company-list') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'company-list' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Companies</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('systemsetting.index') }}"
                                class="nav-link {{ isset($child_menu) && $child_menu == 'system_settings' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>System Setting</p>
                            </a>
                        </li>


                    </ul>
                </li>
                {{-- @endif --}}

                @if (array_search('site-manager', array_column($menu_permission, 'menu_slug')) !== false)
                    <li class="nav-item">
                        <a href="{{ route('site-opening-balance') }}"
                            class="nav-link {{ isset($child_menu) && $child_menu == 'site-opening-balance' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>
                                Opening Balance
                            </p>
                        </a>
                    </li>
                    <li class="nav-item {{ isset($main_menu) && $main_menu == 'Expense' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'Expense' ? 'active' : '' }} ">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>
                                Site Expense
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('site-expense-entry') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'expense-entry' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Expense Entry</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('site-expense-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'expense-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Expense List</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item ">
                        <a href="{{ route('money-requisition-list') }}"
                            class="nav-link {{ isset($child_menu) && $child_menu == 'money-requisition-entry' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p>Financial Requisition</p>
                        </a>
                    </li>

                    <li
                        class="nav-item {{ isset($main_menu) && $main_menu == 'basic_settings' ? 'menu-open' : '' }}">

                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'basic_settings' ? 'active' : '' }} ">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>
                                Reports
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('daily-ledger-report') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'daily-ledger' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Daily Ledger</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li
                        class="nav-item {{ isset($main_menu) && $main_menu == 'basic_settings' ? 'menu-open' : '' }}">

                        <a href="#"
                            class="nav-link {{ isset($main_menu) && $main_menu == 'basic_settings' ? 'active' : '' }} ">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>
                                Material Requisition
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('user-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'user' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Expense Entry</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user-permission') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'user-permission' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Expense List</p>
                                </a>
                            </li>
                            {{-- <li>
                                    <a href="{{ route('site_manager') }}" class="nav-link {{((isset($child_menu) && $child_menu=='site_manager')?'active':'')}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Site Managers</p>
                                    </a>
                                </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('company-list') }}"
                                    class="nav-link {{ isset($child_menu) && $child_menu == 'company-list' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Expense Report</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                <li class="nav-item" style="margin-top: 20px">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="nav-link">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
