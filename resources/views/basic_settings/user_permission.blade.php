@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            User Permission
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-5">
                        <form action="{{ url('save-permission') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="user">User</label>
                                <select name="role" id="role" class="form-control"
                                    onchange="select_menu(this.value)">
                                    <option value="">Select One</option>
                                    <option value="Admin">Admin</option>
                                    <option value="SuperAdmin">Super Admin</option>
                                    <option value="Accounts">Accounts</option>
                                    <option value="OfficeExecutive">Office Executive</option>
                                    <!--<option value="SiteManager">Site Manager</option>-->
                                </select>
                            </div>

                            <div class="card card-info card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Select Your Accessible Menu
                                    </h3>
                                </div>
                                <div class="card-body pl-5">
                                    <div class="w-100 text-right">
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="all"
                                                name="menu[]" id="select-all" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="select-all">Select All</label>
                                        </div>
                                    </div>

                                    <div id="body" class="w-100">
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="hrm"
                                                name="menu[]" id="customSwitch1" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch1">HRM</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 pl-5">
                                                <div class="mt-3 mb-2">
                                                    <label class="font-weight-bold">Organization Setup</label>
                                                    <div class="pl-4">
                                                        {{-- Organization Setup --}}
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|department-list"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Department</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|branch-list" name="sub_menu[]"
                                                                class="hrm-submenu">
                                                            <label>Branch Office</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|section-list"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Section</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|designation-list"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Designation</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|grade-list" name="sub_menu[]"
                                                                class="hrm-submenu">
                                                            <label>Grade</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|paymentType-list"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Payment Type</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|schedule-list"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Schedule</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 mb-2">
                                                    <label class="font-weight-bold">Employee Management</label>
                                                    <div class="pl-4">
                                                        {{-- Employee Management --}}
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|employee-create"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Employee Create</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|manage-employee"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Manage Employee</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|employee-performance"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Employee Performance</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 mb-2">
                                                    <label class="font-weight-bold">Attendance Management</label>
                                                    <div class="pl-4">
                                                        {{-- Attendance Management --}}
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|attendance-entry"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Attendance Entry</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|attendance-list"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Attendance List</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|attendance-monthly-report"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Monthly Attendance Report</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|job-card" name="sub_menu[]"
                                                                class="hrm-submenu">
                                                            <label>Job Card Details</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 mb-2">
                                                    <label class="font-weight-bold">Leave Management</label>
                                                    <div class="pl-4">
                                                        {{-- Leave Management --}}
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|leave-type-list"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Leave Type</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|leave" name="sub_menu[]"
                                                                class="hrm-submenu">
                                                            <label>Leave Entry</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|leave-report"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Leave Report</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 mb-2">
                                                    <label class="font-weight-bold">Salary Management</label>
                                                    <div class="pl-4">
                                                        {{-- Salary Management --}}
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|employee-salary"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Salary Generate</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|employee-salary-list"
                                                                name="sub_menu[]" class="hrm-submenu">
                                                            <label>Salary Report</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 mb-2">
                                                    <label class="font-weight-bold">Holiday Management</label>
                                                    <div class="pl-4">
                                                        {{-- Holiday Management --}}
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="hrm|holiday" name="sub_menu[]"
                                                                class="hrm-submenu">
                                                            <label>Holiday</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="income"
                                                name="menu[]" id="income" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="income">Income</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 pl-5">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="income|entry" class="income-submenu"
                                                        name="sub_menu[]">
                                                    <label>Entry</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="income|list" class="income-submenu"
                                                        name="sub_menu[]">
                                                    <label>List</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="income|income-statement"
                                                        class="income-submenu" name="sub_menu[]">
                                                    <label>Income Statement</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="expense"
                                                name="menu[]" id="expense" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="expense">Expense</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 pl-5">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="expense|advance-expense"
                                                        class="expense-submenu" name="sub_menu[]">
                                                    <label>Advance Expense</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="expense|advance-cheque"
                                                        class="expense-submenu" name="sub_menu[]">
                                                    <label>Advance Cheque</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="expense|expense-entry"
                                                        class="expense-submenu" name="sub_menu[]">
                                                    <label>Expense Entry</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="expense|expense-requisition"
                                                        class="expense-submenu" name="sub_menu[]">
                                                    <label>Expense Requisition List</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="expense|expense-list"
                                                        class="expense-submenu" name="sub_menu[]">
                                                    <label>Expense List</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="expense|reject-expense"
                                                        class="expense-submenu" name="sub_menu[]">
                                                    <label>Reject Expense List</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="accounts"
                                                name="menu[]" id="accounts" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="accounts">Accounts</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 pl-5">
                                                <div class="mt-3 mb-2">
                                                    <label class="font-weight-bold">Report</label>
                                                    <div class="pl-4">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="accounts|daily-ledger"
                                                                class="accounts-submenu" name="sub_menu[]">
                                                            <label>Daily Ledger</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="accounts|headwise-ledger"
                                                                class="accounts-submenu" name="sub_menu[]">
                                                            <label>Headwise Ledger</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="accounts|daily-status"
                                                                class="accounts-submenu" name="sub_menu[]">
                                                            <label>Daily Status</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                value="accounts|project-received-amount"
                                                                class="accounts-submenu" name="sub_menu[]">
                                                            <label>Project Received Amount</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                value="accounts|completion-project-received"
                                                                class="accounts-submenu" name="sub_menu[]">
                                                            <label>Completion Project Received</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" value="accounts|payable-due-amount"
                                                                class="accounts-submenu" name="sub_menu[]">
                                                            <label>Payable Due Amount</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                value="accounts|receipt-payment-statement"
                                                                class="accounts-submenu" name="sub_menu[]">
                                                            <label>Receipt and Payment Statement</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="accounts|account-main-head"
                                                        class="accounts-submenu" name="sub_menu[]">
                                                    <label>Account Main Head</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="accounts|account-sub-head"
                                                        class="accounts-submenu" name="sub_menu[]">
                                                    <label>Account Sub Head</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox"
                                                        value="accounts|head-wise-opening-previous-balance"
                                                        class="accounts-submenu" name="sub_menu[]">
                                                    <label>Head Wise Opening/Previous Balance</label>
                                                </div>

                                                {{-- <div class="custom-control custom-checkbox">
                                                <input type="checkbox" value="accounts|deposit" class="submenu-2" name="sub_menu[]">
                                                <label >Deposit</label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" value="accounts|bank-granty" class="submenu-2" name="sub_menu[]">
                                                <label >Bank Granty</label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" value="accounts|pg-amount" class="submenu-2" name="sub_menu[]">
                                                <label >PG Amount</label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" value="accounts|fdr-amount" class="submenu-2" name="sub_menu[]">
                                                <label >FDR Amount</label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" value="accounts|security-money" class="submenu-2" name="sub_menu[]">
                                                <label >Security Money</label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" value="accounts|loan-status" class="submenu-2" name="sub_menu[]">
                                                <label >Loan Status</label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" value="accounts|expense" class="submenu-2" name="sub_menu[]">
                                                <label >Expense</label>
                                            </div> --}}
                                            </div>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="fund_transfer"
                                                name="menu[]" id="fund_transfer" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="fund_transfer">Fund Transfer</label>
                                        </div>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="bank_info"
                                                name="menu[]" id="bank_info" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="bank_info">Bank Information</label>
                                        </div>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="fund"
                                                name="menu[]" id="fund" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="fund">Fund Management</label>
                                        </div>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="project"
                                                name="menu[]" id="project" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="project">Project</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="capital"
                                                name="menu[]" id="capital" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="capital">Capital</label>
                                        </div>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="loan"
                                                name="menu[]" id="Loan" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="Loan">Loan</label>
                                        </div>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="investment"
                                                name="menu[]" id="investment" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="investment">Investment</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="sales"
                                                name="menu[]" id="customSwitch3" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch3">Land Sales</label>
                                        </div>
                                        {{-- 
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" value="work-status" name="menu[]" id="customSwitch4" style="cursor: pointer !important">
                                        <label class="custom-control-label" for="customSwitch4"> Works & Man Power</label>
                                    </div> --}}

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="requisition"
                                                name="menu[]" id="customSwitch5" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch5">Requisition</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="purchase"
                                                name="menu[]" id="customSwitch6" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch6">Purchase</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="inventory"
                                                name="menu[]" id="customSwitch7" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch7">Inventroy</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="supplier"
                                                name="menu[]" id="customSwitch8" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch8">Supplier</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="vendor"
                                                name="menu[]" id="customSwitch9" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch9">Vendor / Sub
                                                Contractor</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="audit-list"
                                                name="menu[]" id="customSwitch11" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch11">Audit Report</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="bill-list"
                                                name="menu[]" id="customSwitch12" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch12">Bill</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="licenses-list"
                                                name="menu[]" id="customSwitch13" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch13">Licenses
                                                Status</label>
                                        </div>

                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="basic_settings"
                                                name="menu[]" id="customSwitch14" style="cursor: pointer !important">
                                            <label class="custom-control-label" for="customSwitch14">Basic
                                                Settings</label>
                                        </div>

                                        <!--<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">-->
                                        <!--    <input type="checkbox" class="custom-control-input" value="site-manager" name="menu[]" id="site-manager" style="cursor: pointer !important">-->
                                        <!--    <label class="custom-control-label" for="site-manager">Site Manager Access</label>-->
                                        <!--</div>-->
                                        <!--<div class="row">-->
                                        <!--    <div class="col-12 pl-5">-->
                                        <!--        <div class="custom-control custom-checkbox">-->
                                        <!--            <input type="checkbox" value="site-manager|expenses" class="submenu-3" name="sub_menu[]">-->
                                        <!--            <label>Site Expneses</label>-->
                                        <!--        </div>-->
                                        <!--        <div class="custom-control custom-checkbox">-->
                                        <!--            <input type="checkbox" value="site-manager|financial-requisition" class="submenu-3" name="sub_menu[]">-->
                                        <!--            <label>Financial Requisition</label>-->
                                        <!--        </div>-->
                                        <!--        <div class="custom-control custom-checkbox">-->
                                        <!--            <input type="checkbox" value="site-manager|material-requisition" class="submenu-3" name="sub_menu[]">-->
                                        <!--            <label>Material Requisition</label>-->
                                        <!--        </div>-->

                                        <!--    </div>-->
                                        <!--</div>-->

                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary m-5"><i class="fa fa-check"></i>
                                        Submit</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        function select_menu(role) {
            //alert(user_id);
            url = '{{ url('select-user-menu') }}';
            url = url + '/' + role;
            //alert(url);
            $.ajax({
                cache: false,
                type: "GET",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                url: url,
                success: function(response) {
                    $('#body').html(response);
                }
            })
        }

        $('#select-all').click(function(event) {
            if (this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        $('#customSwitch1').click(function(event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.hrm-submenu:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.hrm-submenu:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        $('#income').click(function(event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.income-submenu:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.income-submenu:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        $('#expense').click(function(event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.expense-submenu:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.expense-submenu:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        $('#accounts').click(function(event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.accounts-submenu:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.accounts-submenu:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        $('#customSwitch2').click(function(event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.submenu-2:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.submenu-2:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        $('#site-manager').click(function(event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.submenu-3:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.submenu-3:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    </script>
@endsection
