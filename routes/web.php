<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\BillController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CapitalController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DipositController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\BankInfoController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PgAmountController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\FdrAmountController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PettyCashController;
use App\Http\Controllers\VendorDueController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\LoanStatusController;
use App\Http\Controllers\WorkStatusController;
use App\Http\Controllers\AccountHeadController;
use App\Http\Controllers\AuditReportController;
use App\Http\Controllers\BankGarantyController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\FundTransferController;
use App\Http\Controllers\LandPurchaseController;
use App\Http\Controllers\AdvanceChequeController;
use App\Http\Controllers\CheckUserTypeController;
use App\Http\Controllers\SecurityMoneyController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\VendorPaymentController;
use App\Http\Controllers\VendorProjectController;
use App\Http\Controllers\EmployeeSalaryController;
use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\MoneyRequisitionController;
use App\Http\Controllers\RentalManagementController;
use App\Http\Controllers\ConsumerInvestorsController;
use App\Http\Controllers\EmployeeAttendanceController;
use App\Http\Controllers\FundCurrentBalanceController;
use App\Http\Controllers\HeadToHeadTransferController;
use App\Http\Controllers\EmployeePerformanceController;
use App\Http\Controllers\MaterialRequisitionController;
use App\Http\Controllers\SiteManager\SiteReportController;
use App\Http\Controllers\SiteManager\SiteExpenseController;
use App\Http\Controllers\SiteManager\FinancialRequisitionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');

    return '<!DOCTYPE html>
<html>
<head>
    <title>Cache Cleared</title>
    <meta http-equiv="refresh" content="2;url=/">
</head>
<body style="font-family: Arial; text-align: center; padding: 50px;">
    <h1 style="color: #4CAF50;">✓ Cache cleared successfully!</h1>
    <p>Redirecting to homepage...</p>
</body>
</html>';
});


Route::get('/', function () {
    return redirect('home');
});
Route::get('/login/customer', [CheckUserTypeController::class, 'customerLogin'])->name('checkUser');
Route::post('/submitLogin', [CheckUserTypeController::class, 'submitLogin'])->name('submitLogin');
Route::get('/customer_info', [CustomerController::class, 'customer_info'])->name('customer_info');
Route::get('/submitLogout', [CheckUserTypeController::class, 'logout'])->name('submitLogout');
//Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/select-company/{id}', [HomeController::class, 'select_company'])->name('select-company');

    /** user section  */
    Route::get('/user-permission', [UserController::class, 'permission'])->name('user-permission');
    Route::get('/select-user-menu/{id}', [UserController::class, 'user_menu'])->name('select-user-menu');
    Route::post('/save-permission', [UserController::class, 'save_permission'])->name('save-permission');
    Route::get('/user-list', [UserController::class, 'index'])->name('user-list');
    Route::post('/save-user', [UserController::class, 'store'])->name('save-user');
    Route::get('/change-user-status/{status}/{id}', [UserController::class, 'status_update'])->name('change-user-status');
    Route::post('/update-user', [UserController::class, 'update'])->name('update-user');

    /** Petty Cash Routes */
    Route::get('/petty-cash', [PettyCashController::class, 'index'])->name('petty-cash');
    Route::get('/get-user', [PettyCashController::class, 'companywiseUser'])->name('get-user');
    Route::post('/store-petty-cash', [PettyCashController::class, 'store'])->name('store-petty-cash');

    /** company section */
    Route::get('/company-list', [CompanyController::class, 'index'])->name('company-list');
    Route::post('/save-company', [CompanyController::class, 'store'])->name('save-company');
    Route::get('/change-company-status/{status}/{id}', [CompanyController::class, 'status_update'])->name('change-company-status');
    Route::post('/update-company', [CompanyController::class, 'update'])->name('update-company');

    /** fund section */
    Route::get('/fund-list', [FundController::class, 'index'])->name('fund-list');
    Route::post('/save-fund', [FundController::class, 'store'])->name('save-fund');
    Route::get('/change-fund-status/{status}/{id}', [FundController::class, 'status_update'])->name('change-fund-status');
    Route::post('/update-fund', [FundController::class, 'update'])->name('update-fund');
    Route::get('/fund-transaction', [FundController::class, 'fundLedger'])->name('fund-transaction');
    Route::get('/fund-ledger-list', [FundController::class, 'fundLedgerList'])->name('fund-ledger-list');
    Route::get('/fund-opening-balance', [FundController::class, 'fundOpeningBalance'])->name('fund-balance-entry');
    Route::post('/fund-opening-balance-store', [FundController::class, 'fundOpeningBalanceStore'])->name('fund-balance-store');

    Route::get('/balance-list', [FundCurrentBalanceController::class, 'index'])->name('balance-list');

    /** department section */
    Route::get('/department-list', [DepartmentController::class, 'index'])->name('department-list');
    Route::post('/save-department', [DepartmentController::class, 'store'])->name('save-department');
    Route::get('/change-department-status/{status}/{id}', [DepartmentController::class, 'status_update'])->name('change-department-status');
    Route::post('/update-department', [DepartmentController::class, 'update'])->name('update-department');

    /** branch section */
    Route::get('/branch-list', [BranchController::class, 'index'])->name('branch-list');
    Route::post('/save-branch', [BranchController::class, 'store'])->name('save-branch');
    Route::get('/change-branch-status/{status}/{id}', [BranchController::class, 'status_update'])->name('change-branch-status');
    Route::post('/update-branch', [BranchController::class, 'update'])->name('update-branch');

    /** section section */
    Route::get('/section-list', [SectionController::class, 'index'])->name('section-list');
    Route::post('/save-section', [SectionController::class, 'store'])->name('save-section');
    Route::get('/change-section-status/{status}/{id}', [SectionController::class, 'status_update'])->name('change-section-status');
    Route::post('/update-section', [SectionController::class, 'update'])->name('update-section');

    /** designation section */
    Route::get('/designation-list', [DesignationController::class, 'index'])->name('designation-list');
    Route::post('/save-designation', [DesignationController::class, 'store'])->name('save-designation');
    Route::get('/change-designation-status/{status}/{id}', [DesignationController::class, 'status_update'])->name('change-designation-status');
    Route::post('/update-designation', [DesignationController::class, 'update'])->name('update-designation');

    /** grade section */
    Route::get('/grade-list', [GradeController::class, 'index'])->name('grade-list');
    Route::post('/save-grade', [GradeController::class, 'store'])->name('save-grade');
    Route::get('/change-grade-status/{status}/{id}', [GradeController::class, 'status_update'])->name('change-grade-status');
    Route::post('/update-grade', [GradeController::class, 'update'])->name('update-grade');

    /** leave-type section */
    Route::get('/leave-type-list', [LeaveTypeController::class, 'index'])->name('leave-type-list');
    Route::post('/save-leave-type', [LeaveTypeController::class, 'store'])->name('save-leave-type');
    Route::get('/change-leave-type-status/{status}/{id}', [LeaveTypeController::class, 'status_update'])->name('change-leave-type-status');
    Route::post('/update-leave-type', [LeaveTypeController::class, 'update'])->name('update-leave-type');

    /** leave section */
    Route::get('/leave-report', [LeaveController::class, 'index'])->name('leave-report');
    Route::get('/leave', [LeaveController::class, 'create'])->name('leave');
    Route::post('/save-leave', [LeaveController::class, 'store'])->name('save-leave');
    Route::get('/leave-delete/{id}', [LeaveController::class, 'destroy'])->name('leave-delete');

    /** holyday section */
    Route::get('/holiday', [HolidayController::class, 'index'])->name('holiday');
    Route::post('/save-holiday', [HolidayController::class, 'store'])->name('save-holiday');
    Route::get('/change-holiday-status/{status}/{id}', [HolidayController::class, 'status_update'])->name('change-holiday-status');
    Route::post('/update-holiday', [HolidayController::class, 'update'])->name('update-holiday');

    /** payment type section */
    Route::get('/paymentType-list', [PaymentTypeController::class, 'index'])->name('paymentType-list');
    Route::post('/save-paymentType', [PaymentTypeController::class, 'store'])->name('save-paymentType');
    Route::get('/change-paymentType-status/{status}/{id}', [PaymentTypeController::class, 'status_update'])->name('change-paymentType-status');
    Route::post('/update-paymentType', [PaymentTypeController::class, 'update'])->name('update-paymentType');

    /** schedule section */
    Route::get('/schedule-list', [ScheduleController::class, 'index'])->name('schedule-list');
    Route::post('/save-schedule', [ScheduleController::class, 'store'])->name('save-schedule');
    Route::get('/change-schedule-status/{status}/{id}', [ScheduleController::class, 'status_update'])->name('change-schedule-status');
    Route::post('/update-schedule', [ScheduleController::class, 'update'])->name('update-schedule');

    /** Employee section */
    Route::get('/employee-create', [EmployeeController::class, 'create'])->name('employee-create');
    Route::post('/search-employee', [EmployeeController::class, 'search'])->name('search-employee');
    Route::post('/save-employee', [EmployeeController::class, 'store'])->name('save-employee');
    Route::get('/manage-employee', [EmployeeController::class, 'index'])->name('manage-employee');
    Route::get('/load_employee_view/{id}', [EmployeeController::class, 'load_employee_view'])->name('load_employee_view');
    Route::get('/edit-employee/{id}', [EmployeeController::class, 'edit'])->name('edit-employee');
    Route::post('/update-employee', [EmployeeController::class, 'update'])->name('update-employee');
    Route::get('/employee-profile/{id}', [EmployeeController::class, 'profile'])->name('employee-profile');

    /** Employee Performance */
    Route::get('/employee-performance', [EmployeePerformanceController::class, 'index'])->name('employee-performance');
    Route::post('/save-employee-performance', [EmployeePerformanceController::class, 'store'])->name('save-employee-performance');
    Route::post('/update-employee-performance', [EmployeePerformanceController::class, 'update'])->name('update-employee-performance');

    /** Employee Attendance */
    Route::get('/attendance-entry', [EmployeeAttendanceController::class, 'create'])->name('attendance-entry');
    Route::post('/attendance-save', [EmployeeAttendanceController::class, 'store'])->name('attendance-save');
    Route::get('/attendance-list', [EmployeeAttendanceController::class, 'index'])->name('attendance-list');
    Route::post('/attendance-update', [EmployeeAttendanceController::class, 'update'])->name('attendance-update');
    Route::get('/attendance-monthly-report', [EmployeeAttendanceController::class, 'monthly_report'])->name('attendance-monthly-report');
    Route::get('/monthly_attendance_report_ajax/{department_id}/{start_date}/{end_date}', [EmployeeAttendanceController::class, 'monthly_attendance_report_ajax']);
    Route::get('/monthly_attendance_report_print/{department_id}/{start_date}/{end_date}', [EmployeeAttendanceController::class, 'monthly_attendance_report_print']);
    Route::get('/job-card', [EmployeeAttendanceController::class, 'job_card'])->name('job-card');
    Route::get('/job_card_ajax/{employee_id}/{start_date}/{end_date}', [EmployeeAttendanceController::class, 'job_card_ajax']);
    Route::get('/job_card_print/{employee_id}/{start_date}/{end_date}', [EmployeeAttendanceController::class, 'job_card_print']);

    /** Employee Salary */
    Route::get('/employee-salary', [EmployeeSalaryController::class, 'index'])->name('employee-salary');
    Route::post('/save-employee-salary', [EmployeeSalaryController::class, 'store'])->name('save-employee-salary');
    Route::get('/employee-salary-list', [EmployeeSalaryController::class, 'list'])->name('employee-salary-list');
    Route::get('/select_salary_details/{department_id}/{start_date}/{end_date}/{month}', [EmployeeSalaryController::class, 'select_salary_details']);
    Route::get('/employee-salary-print/{salary_id}', [EmployeeSalaryController::class, 'print_salary_details'])->name('employee-salary-print');
    Route::get('/employee-salary-paid/{salary_id}', [EmployeeSalaryController::class, 'salary_payment'])->name('employee-salary-paid');
    Route::post('/save-salary-payment', [EmployeeSalaryController::class, 'save_salary_payment'])->name('save-salary-payment');

    /** vendor section */
    Route::get('/vendor-list', [VendorController::class, 'index'])->name('vendor-list');
    Route::get('/vendor-print', [VendorController::class, 'print']);
    Route::get('/vendor-pdf', [VendorController::class, 'pdf']);
    Route::post('/save-vendor', [VendorController::class, 'store'])->name('save-vendor');
    Route::get('/change-vendor-status/{status}/{id}', [VendorController::class, 'status_update'])->name('change-vendor-status');
    Route::post('/update-vendor', [VendorController::class, 'update'])->name('update-vendor');
    Route::get('/vendor-project-list', [VendorProjectController::class, 'index'])->name('vendor-project-list');

    Route::get('/vendor-project-print', [VendorProjectController::class, 'print']);
    Route::get('/vendor-project-pdf', [VendorProjectController::class, 'pdf']);

    Route::post('/save-vendor-project', [VendorProjectController::class, 'store'])->name('save-vendor-project');
    Route::post('/update-vendor-project', [VendorProjectController::class, 'update'])->name('update-vendor-project');
    Route::get('/vendor-due-list', [VendorDueController::class, 'index'])->name('vendor-due-list');
    Route::get('/vendor-due-print', [VendorDueController::class, 'print']);
    Route::get('/vendor-due-pdf', [VendorDueController::class, 'pdf']);
    Route::get('/vendor-payment', [VendorPaymentController::class, 'create'])->name('vendor-payment');
    Route::get('/print-voucher', [VendorPaymentController::class, 'printVoucher'])->name('vendor-print-voucher');
    Route::post('/save-vendor-payment', [VendorPaymentController::class, 'store'])->name('save-vendor-payment');
    Route::get('/load-vendor-due/{vendor_id}/{project_id}', [VendorPaymentController::class, 'load_vendor_due']);
    Route::get('/vendor-log/{vendor_id}/{project_id}', [VendorPaymentController::class, 'vendor_log']);
    Route::get('/vendor-payment-list', [VendorPaymentController::class, 'index'])->name('vendor-payment-list');
    Route::get('/vendor-payment-print', [VendorPaymentController::class, 'print']);
    Route::get('/vendor-payment-pdf', [VendorPaymentController::class, 'pdf']);

    /** supplier section */

    Route::get('/supplier-list', [SupplierController::class, 'index'])->name('supplier-list');
    Route::post('/save-supplier', [SupplierController::class, 'store'])->name('save-supplier');
    Route::get('/change-supplier-status/{status}/{id}', [SupplierController::class, 'status_update'])->name('change-supplier-status');
    Route::post('/update-supplier', [SupplierController::class, 'update'])->name('update-supplier');
    Route::get('/supplier-print', [SupplierController::class, 'print']);
    Route::get('/supplier-pdf', [SupplierController::class, 'pdf']);

    Route::get('/work-order-list', [SupplierController::class, 'work_order_index'])->name('work-order-list');
    Route::get('/create-work-order', [SupplierController::class, 'create_work_order'])->name('create_work_order');
    Route::post('/save-work-order', [SupplierController::class, 'store_work_order'])->name('save-work-order');
    Route::get('/edit-work-order/{id}', [SupplierController::class, 'editWorkOrder'])->name('edit_work_order');
    Route::post('/update-work-order/{id}', [SupplierController::class, 'updateWorkOrder'])->name('update-work-order');
    Route::post('/check-type', [SupplierController::class, 'getTypeWiseName'])->name('getTypeWiseName');
    Route::get('/work-order-status/{id}/{status}', [SupplierController::class, 'change_purchase_status']);
    Route::get('/work-order-print', [SupplierController::class, 'work_order_print']);
    Route::get('/orderInvoice/{id}', [SupplierController::class, 'orderInvoice'])->name('order-invoice');

    /** item section */
    Route::get('/category', [ItemController::class, 'categoryIndex'])->name('item-category');
    Route::post('/category-store', [ItemController::class, 'storeCategory'])->name('item-category-store');
    Route::post('/category-update/{id}', [ItemController::class, 'updateCategory'])->name('item-category-update');
    Route::get('/item-category-status/{id}', [ItemController::class, 'statusChange'])->name('item-category-status');
    Route::get('/item-list', [ItemController::class, 'index'])->name('item-list');
    Route::get('/item-print', [ItemController::class, 'print']);
    Route::get('/item-pdf', [ItemController::class, 'pdf']);
    Route::post('/save-item', [ItemController::class, 'store'])->name('save-item');
    Route::get('/change-item-status/{status}/{id}', [ItemController::class, 'status_update'])->name('change-item-status');
    Route::post('/update-item/{id}', [ItemController::class, 'update'])->name('update-item');

    /** man-power-status section */
    Route::get('/man-power-status', [ItemController::class, 'index'])->name('man-power-status');
    Route::post('/save-man-power', [ItemController::class, 'store'])->name('save-man-power');
    Route::get('/change-man-power-status/{status}/{id}', [ItemController::class, 'status_update'])->name('change-man-power-status');
    Route::post('/update-man-power', [ItemController::class, 'update'])->name('update-man-power');

    /** project section */
    Route::get('/project', [ProjectController::class, 'index'])->name('project');
    Route::post('/save-project', [ProjectController::class, 'store'])->name('save-project');
    Route::get('/change-project-status/{status}/{id}', [ProjectController::class, 'status_update'])->name('change-project-status');
    Route::post('/update-project/{id}', [ProjectController::class, 'update'])->name('update-project');
    Route::get('/project-print', [ProjectController::class, 'print']);
    Route::get('/project-pdf', [ProjectController::class, 'pdf']);

    Route::get('/sub-project', [ProjectController::class, 'subProjectIndex'])->name('sub-project');
    Route::post('/save-sub-project', [ProjectController::class, 'storeSubProject'])->name('save-sub-project');
    Route::post('/update-sub-project', [ProjectController::class, 'updateSubProject'])->name('update-sub-project');
    Route::get('/project-ledger', [ProjectController::class, 'projectLedger'])->name('project-ledger');
    Route::get('/project-ledger-list', [ProjectController::class, 'projectLedgerList'])->name('project-ledger-list');
    Route::get('/filter-subproject', [ProjectController::class, 'filtersubProject'])->name('filter-subproject');
    Route::get('/subproject-print', [ProjectController::class, 'printsubproject']);
    // Route::get('/project-pdf',[ProjectController::class,'pdf']);

    /** Common Settings */
    Route::get('/load_department_by_company_id/{id}', [EmployeeController::class, 'load_department_by_company_id'])->name('load_department_by_company_id');
    Route::get('/load_section_by_company_id/{id}', [EmployeeController::class, 'load_section_by_company_id'])->name('load_section_by_company_id');
    Route::get('/load_branch_by_company_id/{id}', [EmployeeController::class, 'load_branch_by_company_id'])->name('load_branch_by_company_id');
    Route::get('/change-employee-status/{status}/{id}', [EmployeeController::class, 'status_update'])->name('change-employee-status');

    /** Work status section */
    Route::get('/add-work-status', [WorkStatusController::class, 'create'])->name('add-work-status');
    Route::post('/save-work-status', [WorkStatusController::class, 'store'])->name('save-work-status');
    Route::get('/WorkStatusData/{project_id}', [WorkStatusController::class, 'previous_data'])->name('WorkStatusData');
    Route::get('/work-status-log/{work_status_id}', [WorkStatusController::class, 'work_status_log'])->name('work-status-log');
    Route::get('/work-status', [WorkStatusController::class, 'index'])->name('work-status');
    Route::get('/work-status-print', [WorkStatusController::class, 'print']);
    Route::get('/work-status-pdf', [WorkStatusController::class, 'pdf']);
    Route::get('/man-power-log/{work_status_id}', [WorkStatusController::class, 'man_power_log'])->name('man-power-log');
    Route::get('/man-power', [WorkStatusController::class, 'man_power'])->name('man-power');
    Route::get('/man-power-print', [WorkStatusController::class, 'man_power_print']);
    Route::get('/man-power-pdf', [WorkStatusController::class, 'man_power_pdf']);

    /** Requisition */
    Route::get('/money-requisition', [MoneyRequisitionController::class, 'index'])->name('money-requisition');
    Route::get('/money-requisition-print', [MoneyRequisitionController::class, 'print']);
    Route::get('/money-requisition-pdf', [MoneyRequisitionController::class, 'pdf']);
    Route::get('/add-money-requisition', [MoneyRequisitionController::class, 'create'])->name('add-money-requisition');
    Route::post('/save-money-requisition', [MoneyRequisitionController::class, 'store'])->name('save-money-requisition');
    Route::post('/approve-money-requisition', [MoneyRequisitionController::class, 'approve'])->name('approve-money-requisition');
    Route::get('/change-money-requisition-status/{id}/{status}', [MoneyRequisitionController::class, 'change_money_requisition_status'])->name('change-money-requisition-status');
    Route::get('/payment-money-requisition/{id}', [MoneyRequisitionController::class, 'payment'])->name('payment-money-requisition');
    Route::post('/save-payment-money-requisition', [MoneyRequisitionController::class, 'save_payment'])->name('save-payment-money-requisition');

    /** Material Requisition */
    Route::get('/material-requisition', [MaterialRequisitionController::class, 'index'])->name('material-requisition');
    Route::get('/material-requisition-print', [MaterialRequisitionController::class, 'print']);
    Route::get('/material-requisition-pdf', [MaterialRequisitionController::class, 'pdf']);
    Route::get('/add-requisition', [MaterialRequisitionController::class, 'create'])->name('add-requisition');
    Route::post('/save-requisition', [MaterialRequisitionController::class, 'store'])->name('save-requisition');
    Route::get('/change-requisition-status/{id}/{status}', [MaterialRequisitionController::class, 'change_requisition_status'])->name('change-requisition-status');

    /** Material Requisition */
    Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase');
    Route::get('/purchase-print', [PurchaseController::class, 'print']);
    Route::get('/purchase-pdf', [PurchaseController::class, 'pdf']);
    Route::get('/add-purchase', [PurchaseController::class, 'create'])->name('add-purchase');
    Route::post('/save-purchase', [PurchaseController::class, 'store'])->name('save-purchase');
    Route::get('/purchaseInvoice/{id}', [PurchaseController::class, 'purchase_details'])->name('purchaseInvoice');
    Route::get('/generatePayment/{id}', [PurchaseController::class, 'generateSupplierPayment'])->name('generatePayment');
    Route::get('/change-purchase-status/{id}/{status}', [PurchaseController::class, 'change_purchase_status'])->name('change-purchase-status');
    Route::post('/save-payment', [PurchaseController::class, 'generalPaymentStore'])->name('save-payment');
    Route::get('/payment-details-view/{id}', [PurchaseController::class, 'purchasePaymentDetails'])->name('payment-details-view');

    /** land Purchase*/
    Route::get('/land-purchase-list', [LandPurchaseController::class, 'index'])->name('land_purchase_list');
    Route::get('/land-purchase', [LandPurchaseController::class, 'create'])->name('land.purchase');
    Route::post('/save-land-purchase', [LandPurchaseController::class, 'store'])->name('save-land-purchase');
    Route::get('/land-stock-list', [LandPurchaseController::class, 'land_stock_list'])->name('land_stock_list');
    Route::get('/land-purchase-edit/{id}', [LandPurchaseController::class, 'purchaseEdit'])->name('land_stock_edit');
    Route::post('/land-purchase-update/{id}', [LandPurchaseController::class, 'updatePurchase'])->name('update-land-purchase');

    /**Suppier Payment */
    Route::get('/supplier-payment-list', [PaymentController::class, 'index'])->name('supplier-payment-list');
    Route::get('/supplier-payment-print', [PaymentController::class, 'print']);
    Route::get('/supplier-payment-pdf', [PaymentController::class, 'pdf']);
    Route::get('/supplier-payment', [PaymentController::class, 'supplier_payment'])->name('supplier-payment');
    Route::post('/supplier-payment-store', [PaymentController::class, 'store'])->name('supplier-payment-store');
    Route::get('/supplier-credit-voucher', [PaymentController::class, 'supplier_credit_voucher'])->name('supplier-voucher');
    Route::get('/supplier-due', [PaymentController::class, 'supplier_due'])->name('supplier-due');
    Route::get('/filter-order', [PaymentController::class, 'filterOrder'])->name('filter-order');
    Route::post('/payment-ready-voucher', [PaymentController::class, 'readyVoucher'])->name('payment-ready-voucher');

    Route::get('/supplier-due-print', [PaymentController::class, 'due_print']);
    Route::get('/supplier-due-pdf', [PaymentController::class, 'due_pdf']);

    Route::post('/save-supplier-payment', [PaymentController::class, 'save_supplier_payment'])->name('save-supplier-payment');
    Route::get('/load-supplier-due/{order_id}', [PaymentController::class, 'load_supplier_due']);
    Route::get('/supplier-log/{supplier_id}', [PaymentController::class, 'supplier_log']);

    /**Audit Payment */
    Route::get('/audit-list', [AuditReportController::class, 'index'])->name('audit-list');
    Route::post('/save-audit', [AuditReportController::class, 'store'])->name('save-audit');
    Route::post('/update-audit', [AuditReportController::class, 'update'])->name('update-audit');
    Route::get('/audit-print', [AuditReportController::class, 'print']);
    Route::get('/audit-pdf', [AuditReportController::class, 'pdf']);

    /**Licenses Payment */
    Route::get('/licenses-list', [LicenseController::class, 'index'])->name('licenses-list');
    Route::post('/save-licenses', [LicenseController::class, 'store'])->name('save-licenses');
    Route::post('/update-licenses', [LicenseController::class, 'update'])->name('update-licenses');
    Route::get('/licenses-print', [LicenseController::class, 'print']);
    Route::get('/licenses-pdf', [LicenseController::class, 'pdf']);

    /**Property */
    Route::get('/property-list', [RentalManagementController::class, 'property_index'])->name('property_list');
    Route::post('/save-property', [RentalManagementController::class, 'property_store'])->name('save_property');
    Route::post('/update-property/{id}', [RentalManagementController::class, 'property_update'])->name('update_property');
    Route::get('/property-status/{id}', [RentalManagementController::class, 'property_status'])->name('property_status');

    /**Floor */
    Route::get('/floor-list', [RentalManagementController::class, 'floor_index'])->name('floor_list');
    Route::post('/save-floor', [RentalManagementController::class, 'floor_store'])->name('save_floor');
    Route::post('/update-floor/{id}', [RentalManagementController::class, 'floor_update'])->name('update_floor');
    Route::get('/floor-status/{id}', [RentalManagementController::class, 'floor_status'])->name('floor_status');

    /**Meter */
    Route::get('/meter-list', [RentalManagementController::class, 'meter_index'])->name('meter_list');
    Route::post('/save-meter', [RentalManagementController::class, 'meter_store'])->name('save_meter');
    Route::post('/update-meter/{id}', [RentalManagementController::class, 'meter_update'])->name('update_meter');
    Route::get('/meter-status/{id}', [RentalManagementController::class, 'meter_status'])->name('meter_status');

    /**Units */
    Route::get('/unit-list', [RentalManagementController::class, 'unit_index'])->name('unit_list');
    Route::get('/unit-create', [RentalManagementController::class, 'unit_create'])->name('unit_create');
    Route::post('/save-unit', [RentalManagementController::class, 'unit_store'])->name('save_unit');
    Route::get('/edit-unit/{id}', [RentalManagementController::class, 'edit_unit'])->name('edit_unit');
    Route::post('/update-unit/{id}', [RentalManagementController::class, 'unit_update'])->name('update_unit');
    Route::get('/unit-status/{id}', [RentalManagementController::class, 'unit_status'])->name('unit_status');

    /**Renter Info*/
    Route::get('/renter-list', [RentalManagementController::class, 'renter_index'])->name('renter_list');
    Route::post('/save-renter', [RentalManagementController::class, 'renter_store'])->name('save_renter');
    Route::post('/update-renter/{id}', [RentalManagementController::class, 'renter_update'])->name('update_renter');
    Route::get('/renter-status/{id}', [RentalManagementController::class, 'renter_status'])->name('renter_status');

    /**Rental Bill */
    Route::get('/rental-bill-list', [RentalManagementController::class, 'rental_bill_index'])->name('rental_bill_list');
    Route::get('/rental-bill-generate', [RentalManagementController::class, 'rental_bill_generate'])->name('rental_bill_generate');
    Route::post('/save-electricity-bill', [RentalManagementController::class, 'electricity_bill_store'])->name('save_electricity_bill');
    Route::post('/save-rental-bill', [RentalManagementController::class, 'rental_bill_store'])->name('save_rental_bill');
    Route::get('/generate-rental-bill', [RentalManagementController::class, 'generateRentalBill'])->name('generate_rental_bill');
    Route::get('/print-rental-invoice/{id}', [RentalManagementController::class, 'printRentalInvoice'])->name('print_rental_invoice');
    Route::get('/rental-print', [RentalManagementController::class, 'rental_print']);

    /**Rental Bill Payment */
    Route::post('/rental-bill-payment', [RentalManagementController::class, 'save_bill_payment'])->name('save_bill_payment');

    /**Concat*/
    Route::get('/get-floors-by-property/{propertyId}', [RentalManagementController::class, 'getFloorsByProperty']);
    Route::get('/get-units-by-floor/{floorId}/{propertyId}', [RentalManagementController::class, 'getUnitsByFloor']);
    Route::get('/get-units-floor-wise/{floorId}/{propertyId}', [RentalManagementController::class, 'getUnitsFloorWise']);

    /**Bill */
    Route::get('/bill-list', [BillController::class, 'index'])->name('bill-list');
    Route::post('/save-bill', [BillController::class, 'store'])->name('save-bill');
    Route::post('/update-bill', [BillController::class, 'update'])->name('update-bill');
    Route::get('/bill-print', [BillController::class, 'print']);
    Route::get('/bill-pdf', [BillController::class, 'pdf']);

    /** inventory */
    Route::get('/stock-report', [StockController::class, 'index'])->name('stock-report');
    Route::get('/stock-report-print', [StockController::class, 'print']);
    Route::get('/stock-report-pdf', [StockController::class, 'pdf']);
    Route::get('/stock-out', [StockOutController::class, 'index'])->name('stock-out');

    Route::get('/stock-out-print', [StockOutController::class, 'print']);
    Route::get('/stock-out-pdf', [StockOutController::class, 'pdf']);

    Route::get('/stock-out-list', [StockOutController::class, 'stock_out_list'])->name('stock-out-list');
    Route::post('/save-stock-out', [StockOutController::class, 'store'])->name('save-stock-out');
    Route::get('/stock-transfer', [StockTransferController::class, 'index'])->name('stock-transfer-list');
    Route::get('/stock-transfer-list', [StockTransferController::class, 'transfer_list'])->name('stock-transfer');
    Route::get('/stock-transfer-print', [StockTransferController::class, 'print']);
    Route::get('/stock-transfer-pdf', [StockTransferController::class, 'pdf']);
    Route::post('/save-stock-transfer', [StockTransferController::class, 'store'])->name('save-stock-transfer');
    Route::get('/select-transfer-project/{company_id}', [StockTransferController::class, 'transfer_project'])->name('select-transfer-project');

    /**Deposit */
    Route::get('/deposit-list', [DipositController::class, 'index'])->name('deposit-list');
    Route::get('/deposit-print', [DipositController::class, 'print']);
    Route::get('/deposit-pdf', [DipositController::class, 'pdf']);
    Route::get('/deposit-entry', [DipositController::class, 'create'])->name('deposit-entry');
    Route::post('/deposit-save', [DipositController::class, 'store'])->name('deposit-save');

    /** Account Category**/
    Route::get('/account-category', [AccountCategoryController::class, 'index'])->name('account-category');
    Route::post('/category/store', [AccountCategoryController::class, 'store'])->name('category-store');
    Route::post('/category/update/{id}', [AccountCategoryController::class, 'update'])->name('category-update');
    Route::get('/category/delete/{id}', [AccountCategoryController::class, 'delete'])->name('category-delete');

    /* Account Head Wise Opening Balance */
    Route::get('/account-opening-balance', [AccountHeadController::class, 'head_opening_balance_index'])->name('account-opening-balance');
    Route::post('/account-opening-balance/store', [AccountHeadController::class, 'head_opening_balance_store'])->name('account-opening-balance-store');
    Route::post('/account-opening-balance/update/{id}', [AccountHeadController::class, 'head_opening_balance_update'])->name('account-opening-balance-update');
    Route::get('/account-opening-balance/delete/{id}', [AccountHeadController::class, 'head_opening_balance_delete'])->name('account-opening-balance-delete');

    /** Account Head**/
    Route::get('/account-head', [AccountHeadController::class, 'index'])->name('account-head');
    Route::post('/head/store', [AccountHeadController::class, 'store'])->name('head-store');
    Route::post('/head/update/{id}', [AccountHeadController::class, 'update'])->name('head-update');
    Route::get('/head/delete/{id}', [AccountHeadController::class, 'delete'])->name('head-delete');

    Route::get('/advance-cheque', [AdvanceChequeController::class, 'index'])->name('advance-cheque');
    Route::post('/advance-cheque-store', [AdvanceChequeController::class, 'chequeStore'])->name('advance-cheque-store');
    Route::get('/checkstatus/{id}', [AdvanceChequeController::class, 'statusUpdate'])->name('cheque.status.update');

    /**Expense */
    Route::get('/expense-list', [ExpenseController::class, 'index'])->name('expense-list');
    Route::get('/requisition-list', [ExpenseController::class, 'requisitionList'])->name('requisition-list');
    Route::get('/reject-list', [ExpenseController::class, 'rejectExpenseList'])->name('reject-list');
    Route::get('/create-expense', [ExpenseController::class, 'create'])->name('create-expense');
    Route::post('/expense-voucher', [ExpenseController::class, 'readyVoucher'])->name('expense-voucher');
    Route::post('/expense-save', [ExpenseController::class, 'store'])->name('expense-save');
    Route::get('/edit-expense/{id}', [ExpenseController::class, 'editExpense'])->name('edit-expense');
    Route::post('/update-expense/{id}', [ExpenseController::class, 'updateExpense'])->name('update-expense');
    Route::post('/add-attachment/{id}', [ExpenseController::class, 'addAttachment'])->name('add-attachment');
    Route::get('/print-voucher/{id}', [ExpenseController::class, 'printDebitVoucher'])->name('print-debit-voucher');
    Route::get('/filter', [ExpenseController::class, 'filterHead'])->name('filter-head');
    Route::get('/project-filter', [ExpenseController::class, 'filterProject'])->name('filter-project');
    Route::get('/filter-sub-project', [ExpenseController::class, 'filterSubProject'])->name('filter-sub-project');
    Route::get('/filter-account', [ExpenseController::class, 'filterAccount'])->name('filter-account');
    Route::get('/account-holder', [ExpenseController::class, 'accountHolder'])->name('account-holder');
    Route::get('/advance-expense', [ExpenseController::class, 'advanceExpense'])->name('advance-expense');
    Route::post('/advance-expense-store', [ExpenseController::class, 'advanceExpenseStore'])->name('advance-expense-store');
    Route::post('/advance-expense-update/{id}', [ExpenseController::class, 'advanceExpenseUpdate'])->name('advance-expense-update');
    Route::get('/advance-expense-amount', [ExpenseController::class, 'advanceExpenseAmount'])->name('advance-expense-amount');
    Route::get('/advance-cheque-amount', [ExpenseController::class, 'advanceChequeAmount'])->name('advance-cheque-amount');
    Route::get('/advance-cheque-bank-info', [ExpenseController::class, 'advanceChequeBankInfo'])->name('advance-cheque-bank-info');
    Route::get('/expese-print', [ExpenseController::class, 'print']);
    Route::get('/update-expense-status/{id}/{status}', [ExpenseController::class, 'expenseStatusUpdate']);
    Route::get('/update-forward-status/{id}/{status}', [ExpenseController::class, 'expenseForwardStatus']);
    Route::get('/statusupdate/{id}', [ExpenseController::class, 'statusUpdate'])->name('status-update');

    /*** Income ***/
    Route::get('/income-list', [IncomeController::class, 'index'])->name('income-list');
    Route::get('/create-income', [IncomeController::class, 'create'])->name('create-income');
    Route::post('/income-voucher', [IncomeController::class, 'incomeVoucher'])->name('income-voucher');
    Route::post('/income-save', [IncomeController::class, 'store'])->name('income-save');
    Route::get('/print-credit-voucher/{id}', [IncomeController::class, 'printVoucher'])->name('print-voucher');
    Route::get('/filter-head', [IncomeController::class, 'filterHeadData'])->name('filter-head-data');
    Route::get('/filter-project', [IncomeController::class, 'filterProjectData'])->name('filter-project-data');
    Route::get('/edit-income/{id}', [IncomeController::class, 'edit'])->name('edit-income');
    Route::post('/update-income/{id}', [IncomeController::class, 'update'])->name('update-income');
    Route::get('/income-print', [IncomeController::class, 'printList']);
    Route::get('/payment_money_receipt/{id}', [IncomeController::class, 'payment_money_receipt'])->name('payment_money_receipt');
    Route::get('/status-update/{id}', [IncomeController::class, 'statusUpdate'])->name('statusUpdate');
    Route::get('/get-current-balance', [IncomeController::class, 'getCurrentBalance']);
    Route::get('/get-current-balance-edit', [IncomeController::class, 'getCurrentBalanceEdit']);

    /*** ledgers ***/
    Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger');
    Route::get('/ledger-list', [LedgerController::class, 'ledgerListView'])->name('ledger-list');
    Route::get('/head/ledger', [LedgerController::class, 'haedwiseLedger'])->name('head.ledger');
    Route::get('/head-wise-ledger-list', [LedgerController::class, 'haedwiseLedgerList'])->name('head-wise-ledger-list');

    /** Capital Routes */
    Route::get('/capital-category', [CapitalController::class, 'categoryIndex'])->name('capital-category');
    Route::post('/capital-category-store', [CapitalController::class, 'storeCategory'])->name('capital-category-store');
    Route::post('/capital-category-update/{id}', [CapitalController::class, 'updateCategory'])->name('capital-category-update');
    Route::get('/capital-list', [CapitalController::class, 'index'])->name('capital-list');
    Route::post('/capital-store', [CapitalController::class, 'storeCapital'])->name('capital-store');
    Route::post('/share-income', [CapitalController::class, 'sharedContribution'])->name('share-income');
    Route::post('/share-withdraw', [CapitalController::class, 'sharedWithdraw'])->name('share-withdraw');
    Route::get('/transfer-index', [CapitalController::class, 'transferIndex'])->name('transfer-index');
    Route::post('/share-transfer', [CapitalController::class, 'sharedTransfer'])->name('share-transfer');
    Route::post('/capital-update/{id}', [CapitalController::class, 'updateCapital'])->name('capital-update');
    Route::get('/filter-bank-account', [CapitalController::class, 'filterAccount'])->name('filter-bank-account');

    Route::get('/get-heads-by-category', [CapitalController::class, 'getHeadsByCategory']);
    Route::get('/get-accounts-by-bank', [CapitalController::class, 'getAccountsByBank']);

    /********************** Fund Transfer ***********************/
    Route::get('/transfer-log', [FundTransferController::class, 'index'])->name('transfer-log');
    Route::get('/fund-transfer', [FundTransferController::class, 'create'])->name('fund-transfer');
    Route::post('/fund-transfer-store', [FundTransferController::class, 'store'])->name('fund-transfer-store');
    Route::get('/filter-bank-fund', [FundTransferController::class, 'filterAccount'])->name('filter-bank-fund');
    Route::get('/trasfer-status-update/{id}', [FundTransferController::class, 'logStatusUpdate'])->name('trasfer-status-update');

    /********************** Head To Head Transfer ***********************/
    Route::get('/head-to-head-transfer-log', [HeadToHeadTransferController::class, 'index'])->name('head-to-head-transfer-log');
    Route::get('/head-to-head-transfer', [HeadToHeadTransferController::class, 'create'])->name('head-to-head-transfer');
    Route::post('/head-to-head-transfer-store', [HeadToHeadTransferController::class, 'store'])->name('head-to-head-transfer-store');
    Route::get('/filter-bank-fund', [HeadToHeadTransferController::class, 'filterAccount'])->name('filter-bank-fund');
    Route::get('/head-to-head-transfer-status-update/{id}', [HeadToHeadTransferController::class, 'logStatusUpdate'])->name('head-to-head-transfer-status-update');

    /** accounts report */
    Route::get('/daily-status', [AccountController::class, 'daily_status'])->name('daily-status');
    Route::get('/daily-status-print', [AccountController::class, 'daily_status_print']);
    Route::get('/daily-status-pdf', [AccountController::class, 'daily_status_pdf']);
    Route::get('/project-received', [AccountController::class, 'project_received'])->name('project-received');
    Route::get('/project-received-print', [AccountController::class, 'project_received_print']);
    Route::get('/project-received-pdf', [AccountController::class, 'project_received_pdf']);
    Route::get('/completion-project-received', [AccountController::class, 'completion_project_received'])->name('completion-project-received');
    Route::get('/completion-project-received-print', [AccountController::class, 'completion_project_received_print']);
    Route::get('/completion-project-received-pdf', [AccountController::class, 'completion_project_received_pdf']);
    Route::get('/payable-due-amount', [AccountController::class, 'payable_due_amount'])->name('payable-due-amount');
    Route::get('/payable-due-amount-print', [AccountController::class, 'payable_due_amount_print']);
    Route::get('/payable-due-amount-pdf', [AccountController::class, 'payable_due_amount_pdf']);

    /** Bank Information */
    Route::get('/bank-list', [BankInfoController::class, 'index'])->name('bank-list');
    Route::get('/create-bank', [BankInfoController::class, 'create'])->name('create-bank');
    Route::post('/save-bank-info', [BankInfoController::class, 'store'])->name('save-bank-info');
    Route::post('/update-bank/{id}', [BankInfoController::class, 'update'])->name('update-bank');

    /** Bank Account */
    Route::get('/account-list', [BankInfoController::class, 'accountIndex'])->name('account-list');
    Route::post('/store-account', [BankInfoController::class, 'storeAccount'])->name('store-account');
    Route::post('/update-account/{id}', [BankInfoController::class, 'updateAccount'])->name('update-account');

    /** Bank Garanty */
    Route::get('/garanty-list', [BankGarantyController::class, 'index'])->name('garanty-list');
    Route::get('/garanty-print', [BankGarantyController::class, 'print']);
    Route::get('/garanty-pdf', [BankGarantyController::class, 'pdf']);
    Route::get('/create-garanty', [BankGarantyController::class, 'create'])->name('create-garanty');
    Route::post('/save-garanty', [BankGarantyController::class, 'store'])->name('save-garanty');
    Route::get('/edit-garanty/{id}', [BankGarantyController::class, 'edit'])->name('edit-garanty');
    Route::post('/update-garanty', [BankGarantyController::class, 'update'])->name('update-garanty');

    /** PG Amount */
    Route::get('/pg-list', [PgAmountController::class, 'index'])->name('pg-list');
    Route::get('/pg-print', [PgAmountController::class, 'print']);
    Route::get('/pg-pdf', [PgAmountController::class, 'pdf']);
    Route::get('/create-pg', [PgAmountController::class, 'create'])->name('create-pg');
    Route::post('/save-pg', [PgAmountController::class, 'store'])->name('save-pg');
    Route::get('/edit-pg/{id}', [PgAmountController::class, 'edit'])->name('edit-pg');
    Route::post('/update-pg', [PgAmountController::class, 'update'])->name('update-pg');

    /** FDR Amount */
    Route::get('/fdr-list', [FdrAmountController::class, 'index'])->name('fdr-list');
    Route::get('/fdr-print', [FdrAmountController::class, 'print']);
    Route::get('/fdr-pdf', [FdrAmountController::class, 'pdf']);
    Route::get('/create-fdr', [FdrAmountController::class, 'create'])->name('create-fdr');
    Route::post('/save-fdr', [FdrAmountController::class, 'store'])->name('save-fdr');
    Route::get('/edit-fdr/{id}', [FdrAmountController::class, 'edit'])->name('edit-fdr');
    Route::post('/update-fdr', [FdrAmountController::class, 'update'])->name('update-fdr');

    /** security Amount */
    Route::get('/security-list', [SecurityMoneyController::class, 'index'])->name('security-list');
    Route::get('/security-print', [SecurityMoneyController::class, 'print']);
    Route::get('/security-pdf', [SecurityMoneyController::class, 'pdf']);
    Route::get('/create-security', [SecurityMoneyController::class, 'create'])->name('create-security');
    Route::post('/save-security', [SecurityMoneyController::class, 'store'])->name('save-security');
    Route::get('/edit-security/{id}', [SecurityMoneyController::class, 'edit'])->name('edit-security');
    Route::post('/update-security', [SecurityMoneyController::class, 'update'])->name('update-security');

    // /** loan Amount */
    // Route::get('/loan-list',[LoanStatusController::class,'index'])->name('loan-list');
    // Route::get('/loan-print',[LoanStatusController::class,'print']);
    // Route::get('/loan-pdf',[LoanStatusController::class,'pdf']);
    // Route::get('/create-loan',[LoanStatusController::class,'create'])->name('create-loan');
    // Route::post('/save-loan',[LoanStatusController::class,'store'])->name('save-loan');
    // Route::get('/edit-loan/{id}',[LoanStatusController::class,'edit'])->name('edit-loan');
    // Route::post('/update-loan',[LoanStatusController::class,'update'])->name('update-loan');

    /** loan Amount */
    Route::get('/loan-list', [LoanStatusController::class, 'index'])->name('loan-list');
    Route::get('/receivable-loan-list', [LoanStatusController::class, 'receivable_index'])->name('receivable-loan-list');
    Route::get('/receivable-loan-print', [LoanStatusController::class, 'receivable_loan_print'])->name('receivable-loan-print');
    Route::get('/loan-print', [LoanStatusController::class, 'print']);
    Route::get('/loan-pdf', [LoanStatusController::class, 'pdf']);
    Route::get('/create-loan', [LoanStatusController::class, 'create'])->name('create-loan');
    Route::post('/loan-voucher', [LoanStatusController::class, 'readyVoucher'])->name('loan-voucher');
    Route::post('/save-loan', [LoanStatusController::class, 'store'])->name('save-loan');
    Route::get('/print-loan-voucher/{id}', [LoanStatusController::class, 'printLoanDebitVoucher'])->name('print-loan-debit-voucher');
    // Route::get('/edit-loan/{id}', [LoanStatusController::class, 'edit'])->name('edit-loan');
    // Route::post('/update-loan', [LoanStatusController::class, 'update'])->name('update-loan');

    /* loan Collection */
    Route::get('/loan-collection-list', [LoanStatusController::class, 'collection_index'])->name('loan-collection-list');
    Route::get('/create-loan-collection', [LoanStatusController::class, 'collection_create'])->name('loan-collection-create');
    Route::get('/get-current-amount/{loanId}', [LoanStatusController::class, 'getCurrentAmount'])->name('getCurrentAmount');
    Route::post('/collection-loan-voucher', [LoanStatusController::class, 'readyCollectionVoucher'])->name('loan-collection-voucher');
    Route::post('/save-loan-collection', [LoanStatusController::class, 'store_loan_collection'])->name('save-loan-collection');
    Route::get('/loan-collection-print', [LoanStatusController::class, 'loan_collection_print']);
    Route::get('/print-loan-collection-credit-voucher/{id}', [LoanStatusController::class, 'printCollectionVoucher'])->name('print-collection-voucher');

    Route::get('/current-asset', [AssetController::class, 'assetIndex'])->name('current_asset_list');
    Route::post('/store-current-asset', [AssetController::class, 'storeCurrentAsset'])->name('current_asset_store');
    Route::get('/asset-category', [AssetController::class, 'asset_category'])->name('asset_category_list');
    Route::post('/save-asset-category', [AssetController::class, 'store'])->name('save_asset_category');
    Route::post('/update-asset-category', [AssetController::class, 'update'])->name('update_asset_category');
    Route::get('/asset-category/delete/{id}', [AssetController::class, 'delete'])->name('asset_category_delete');
    Route::get('/asset-group', [AssetController::class, 'asset_group'])->name('asset_group_list');
    Route::post('/save-asset-group', [AssetController::class, 'asset_group_store'])->name('save_asset_group');
    Route::post('/update-asset-group/{id}', [AssetController::class, 'update_asset_group'])->name('update_asset_group');
    Route::get('/asset-group/delete/{id}', [AssetController::class, 'asset_group_delete'])->name('asset_group_delete');
    Route::get('/asset', [AssetController::class, 'asset'])->name('asset_list');
    Route::post('/save-asset', [AssetController::class, 'asset_store'])->name('save_asset');
    Route::post('/update-asset/{id}', [AssetController::class, 'asset_update'])->name('update_asset');
    Route::get('/asset/delete/{id}', [AssetController::class, 'asset_delete'])->name('asset_delete');
    Route::get('/asset_purchase', [AssetController::class, 'asset_purchase'])->name('asset_purchase_list');
    Route::get('/assetPurchaseInvoice/{id}', [AssetController::class, 'assetPurchaseInvoice'])->name('asset-purchase-invoice');
    Route::get('/asset_purchase_print', [AssetController::class, 'print_asset_purchase'])->name('asset_purchase_print');
    Route::post('/save_asset_purchase', [AssetController::class, 'asset_purchase_store'])->name('save_asset_purchase');
    Route::post('/update_asset_purchase/{id}', [AssetController::class, 'asset_purchase_update'])->name('update_asset_purchase');
    Route::get('/get-groupWise-asset', [AssetController::class, 'getGroupWiseAsset'])->name('getGroupWiseAsset');
    Route::get('/asset_stock', [AssetController::class, 'asset_stock'])->name('asset_stock');
    Route::get('/asset_expense', [AssetController::class, 'asset_expense'])->name('asset_expense');
    Route::get('/asset_assign', [AssetController::class, 'asset_assign'])->name('asset_assign_list');
    Route::get('/getStockAsset', [AssetController::class, 'getStockAsset'])->name('getStockAsset');
    Route::get('/getStockQuantity', [AssetController::class, 'getStockQuantity'])->name('getStockQuantity');
    Route::get('/getAssignedQuantity', [AssetController::class, 'getAssignedQuantity'])->name('getAssignedQuantity');
    Route::post('/save_asset_assign', [AssetController::class, 'asset_assign_store'])->name('save_asset_assign');
    Route::post('/update_asset_assign/{id}', [AssetController::class, 'asset_assign_update'])->name('update_asset_assign');
    Route::get('/asset_assign/delete/{id}', [AssetController::class, 'asset_assign_delete'])->name('asset_assign_delete');
    Route::get('/get_employee_wise_group_fixed', [AssetController::class, 'getEmployeeWiseGroup'])->name('getEmployeeWiseGroup');
    Route::get('/get_employee_wise_group_asset_fixed', [AssetController::class, 'getEmployeeWiseGroupAsset'])->name('getEmployeeWiseGroupAsset');
    Route::get('/get_group_wise_asset_usable', [AssetController::class, 'getGroupWiseAssetUsable'])->name('getGroupWiseAssetUsable');
    Route::post('/save_fixed_asset_damage', [AssetController::class, 'fixed_asset_damage_store'])->name('save_fixed_asset_damage');
    Route::post('/save_usable_asset_damage', [AssetController::class, 'usable_asset_damage_store'])->name('save_usable_asset_damage');
    Route::get('/damage_asset_list', [AssetController::class, 'damage_asset_list'])->name('damage_asset_list');
    Route::post('/update_damage_asset/{id}', [AssetController::class, 'update_damage_asset'])->name('update_damage_asset');
    Route::post('/save_report_lost_asset/{id}', [AssetController::class, 'save_report_lost_asset'])->name('save_report_lost_asset');
    Route::get('/asset_lost', [AssetController::class, 'asset_lost_list'])->name('asset_lost_list');
    Route::post('/update_lost_asset/{id}', [AssetController::class, 'update_lost_asset'])->name('update_lost_asset');
    Route::post('/save_liquidation_asset/{id}', [AssetController::class, 'save_liquidation_asset'])->name('save_liquidation_asset');
    Route::get('/asset_liquidation', [AssetController::class, 'asset_liquidation_list'])->name('asset_liquidation_list');
    Route::post('/update_liquidation_asset/{id}', [AssetController::class, 'update_liquidation_asset'])->name('update_liquidation_asset');
    Route::post('/check-asset-type', [AssetController::class, 'getAssetTypeWiseLifeTime'])->name('getAssetTypeWiseLifeTime');
    Route::get('/asset_expense_print', [AssetController::class, 'asset_expense_print'])->name('asset_expense_print');
    Route::get('/asset_stock_print', [AssetController::class, 'asset_stock_print'])->name('asset_stock_print');
    Route::get('/asset_assign_print', [AssetController::class, 'asset_assign_print'])->name('asset_assign_print');
    Route::get('/asset_damage_print', [AssetController::class, 'asset_damage_print'])->name('asset_damage_print');
    Route::get('/asset_lost_print', [AssetController::class, 'asset_lost_print'])->name('asset_lost_print');
    Route::get('/asset_liquidation_print', [AssetController::class, 'asset_liquidation_print'])->name('asset_liquidation_print');
    Route::post('/save_revoke_asset/{id}', [AssetController::class, 'save_revoke_asset'])->name('save_revoke_asset');
    Route::get('/revoke_list', [AssetController::class, 'revoke_list'])->name('revoke_list');
    Route::get('/print_revoke_list', [AssetController::class, 'print_revoke_list'])->name('print_revoke_list');

    Route::get('/receivable-loan-log/{loan_id}', [LoanStatusController::class, 'receivable_loan_log']);

    /* loan report */
    Route::get('/loan_report', [LoanStatusController::class, 'loan_report_index'])->name('loan_report');
    Route::get('/loan_report_list', [LoanStatusController::class, 'loanReportList'])->name('loan_report_list');

    /* investment */
    Route::get('/investment-list', [InvestmentController::class, 'index'])->name('investment_list');
    Route::get('/investment-create', [InvestmentController::class, 'create_investment'])->name('create_investment');
    Route::post('/save-investment', [InvestmentController::class, 'store'])->name('save-investment');
    Route::get('/edit-investment/{id}', [InvestmentController::class, 'edit'])->name('edit-investment');
    Route::post('/update-investment/{id}', [InvestmentController::class, 'update'])->name('update-investment');
    Route::get('/print-investment-voucher/{id}', [InvestmentController::class, 'printInvestDebitVoucher'])->name('print-invest-debit-voucher');
    Route::get('/investment-print', [InvestmentController::class, 'print']);
    Route::get('/investment-pdf', [InvestmentController::class, 'pdf']);
    Route::get('/receivable-investment-list', [InvestmentController::class, 'receivable_invest_index'])->name('receivable-invest-list');
    Route::get('/receivable-investment-print', [InvestmentController::class, 'receivable_invest_print'])->name('receivable-invest-print');
    Route::get('/receivable-invest-log/{invest_id}', [InvestmentController::class, 'receivable_invest_log']);
    Route::get('/collect-invest-list', [InvestmentController::class, 'collect_invest_index'])->name('collect-invest-list');
    Route::get('/create-collect-invest', [InvestmentController::class, 'collect_invest_create'])->name('collect-invest-create');
    Route::get('/get-invest-amount/{investId}', [InvestmentController::class, 'getInvestAmount'])->name('getInvestAmount');
    Route::get('/get-due-amount-month-wise/{investId}', [InvestmentController::class, 'getDueAmountMonthWise'])->name('getDueAmountMonthWise');
    Route::post('/collect-invest-voucher', [InvestmentController::class, 'readyCollectVoucher'])->name('collect-invest-voucher');
    Route::post('/save-collect-invest', [InvestmentController::class, 'store_collect_invest'])->name('save-collect-invest');
    Route::get('/print-collect-voucher/{id}', [InvestmentController::class, 'printCollectVoucher'])->name('print-collect-voucher');
    Route::get('/collect-invest-print', [InvestmentController::class, 'collect_invest_print']);
    Route::get('/investor_money_receipt/{id}', [InvestmentController::class, 'investor_money_receipt'])->name('investor_money_receipt');

    Route::post('/update-consumer_investors/{id}', [InvestmentController::class, 'update_consumer_investors'])->name('update_consumer_investors');
    Route::get('/status-update-consumer_investors/{id}', [InvestmentController::class, 'updateConsumerStatus'])->name('updateConsumerStatus');

    /* investment report */
    Route::get('/investment_report', [InvestmentController::class, 'investment_report_index'])->name('investment_report');
    Route::get('/investment_report_list', [InvestmentController::class, 'investmentReportList'])->name('investment_report_list');

    // /** sales */
    // Route::get('/invoice-list',[SalesController::class,'index'])->name('invoice-list');
    // Route::get('/invoice-print',[SalesController::class,'print']);
    // Route::get('/invoice-pdf',[SalesController::class,'pdf']);
    // Route::get('/create-invoice',[SalesController::class,'create'])->name('create-invoice');
    // Route::post('/save-invoice',[SalesController::class,'store'])->name('save-invoice');
    // Route::get('/edit-invoice/{id}',[SalesController::class,'edit'])->name('edit-invoice');
    // Route::post('/update-invoice',[SalesController::class,'update'])->name('update-invoice');
    Route::post('/filter-item', [SalesController::class, 'filterItem'])->name('filterItem');
    // Route::get('/sales-invoice/{id}',[SalesController::class,'salesInvoiceDetails'])->name('salesInvoice');
    // Route::get('/sales-payment-details/{id}',[SalesController::class,'salesPaymentDetails'])->name('salesPayments');
    // Route::get('/payment-create/{id}',[SalesController::class,'createPayment'])->name('payment-create');
    // Route::post('/sales-payment-store',[SalesController::class,'storePayment'])->name('sales-payment-store');

    /** sales */
    Route::get('/invoice-list', [SalesController::class, 'index'])->name('invoice-list');
    Route::get('/sector', [SalesController::class, 'sector'])->name('sector');
    Route::post('/save-sector', [SalesController::class, 'save_sector'])->name('save_sector');
    Route::post('/update_sector/{id}', [SalesController::class, 'update_sector'])->name('update_sector');
    Route::get('/sector_delete/{id}', [SalesController::class, 'sector_delete'])->name('sector_delete');
    Route::get('/road', [SalesController::class, 'road'])->name('road');
    Route::get('/getProjectWisePloat', [SalesController::class, 'getProjectWisePloat'])->name('getProjectWisePloat');
    Route::post('/save-road', [SalesController::class, 'save_road'])->name('save_road');
    Route::post('/update_road/{id}', [SalesController::class, 'update_road'])->name('update_road');
    Route::get('/road_delete/{id}', [SalesController::class, 'road_delete'])->name('road_delete');
    Route::get('/plot/type', [SalesController::class, 'plotType'])->name('plot_type');
    Route::post('/plot/type/store', [SalesController::class, 'plotTypeSave'])->name('plot_type_store');
    Route::post('/plot/type/update', [SalesController::class, 'plotTypeUpdate'])->name('plot_type_update');
    Route::get('/plot', [SalesController::class, 'plot'])->name('plot');
    Route::get('/plot/create', [SalesController::class, 'createPlotForm'])->name('plot-create');
    Route::post('/save-plot', [SalesController::class, 'save_plot'])->name('save_plot');
    Route::post('/update_plot/{id}', [SalesController::class, 'update_plot'])->name('update_plot');
    Route::get('/plot_delete/{id}', [SalesController::class, 'plot_delete'])->name('plot_delete');
    Route::get('/getSectorWiseRoad', [SalesController::class, 'getSectorWiseRoad'])->name('getSectorWiseRoad');
    Route::get('/getPlot', [SalesController::class, 'getPlot'])->name('getPlot');
    Route::get('/getFlat', [SalesController::class, 'getFlat'])->name('getFlat');
    Route::get('/getPlotData', [SalesController::class, 'getPlotData'])->name('getPlotData');
    Route::get('/getFlatData', [SalesController::class, 'getFlatData'])->name('getFlatData');
    Route::get('/flats', [SalesController::class, 'flat'])->name('flat');
    Route::post('/save-flat', [SalesController::class, 'save_flat'])->name('save_flat');
    Route::post('/update_flat/{id}', [SalesController::class, 'update_flat'])->name('update_flat');

    /* Consumer Investor */
    Route::get('/consumer_investors', [ConsumerInvestorsController::class, 'consumer_investors'])->name('consumer_investors');
    Route::post('/save-consumer-investors', [ConsumerInvestorsController::class, 'save_consumer_investors'])->name('save_consumer_investors');
    Route::post('/update-consumer_investors/{id}', [ConsumerInvestorsController::class, 'update_consumer_investors'])->name('update_consumer_investors');

    Route::get('/print-invest-collect-credit-voucher/{id}', [ConsumerInvestorsController::class, 'printCollectInvestVoucher'])->name('printCollectInvestVoucher');
    Route::get('/collection-invest-list', [ConsumerInvestorsController::class, 'collect_invest_index'])->name('collect_invest_index');
    Route::get('/get-current-amount-invest/{investId}', [ConsumerInvestorsController::class, 'getCurrentAmount'])->name('getCurrentAmountInvest');
    Route::post('/return-invest-voucher', [ConsumerInvestorsController::class, 'readyReturnVoucher'])->name('return-invest-voucher');
    Route::get('/print-invest-return-credit-voucher/{id}', [ConsumerInvestorsController::class, 'printReturnVoucher'])->name('print-return-voucher');
    Route::get('/return-invest-print', [ConsumerInvestorsController::class, 'return_invest_print']);

    Route::get('/flat_floor', [SalesController::class, 'flat_floor'])->name('flat_floor');
    Route::post('/save-flat_floor', [SalesController::class, 'save_flat_floor'])->name('save_flat_floor');
    Route::post('/update_flat_floor/{id}', [SalesController::class, 'update_flat_floor'])->name('update_flat_floor');
    Route::get('/create_application_form', [SalesController::class, 'create_application_form'])->name('create_application_form');
    Route::post('/application-voucher', [SalesController::class, 'application_voucher'])->name('application_voucher');
    Route::post('/save-application-form', [SalesController::class, 'save_application_form'])->name('save_application_form');
    Route::get('/edit-application-form/{id}', [SalesController::class, 'editSalesDetails'])->name('edit_application_form');
    Route::post('/update-application-form/{id}', [SalesController::class, 'updateSalesDetails'])->name('update_application_form');
    Route::get('/projects-by-type', [SalesController::class, 'getProjectsByType'])->name('get_projects_by_type');
    Route::delete('/delete-application-form/{id}', [SalesController::class, 'deleteApplicationForm'])->name('delete_application_form');

    Route::get('/projects-by-type', [SalesController::class, 'getProjectsByType'])->name('get_projects_by_type');

    /* Plot Type */

    // Route::get('/plot_type', [SalesController::class, 'plot_type'])->name('plot.type');

    Route::get('/getAccountBranch', [SalesController::class, 'getAccountBranch'])->name('getAccountBranch');
    Route::get('/land_sale_list', [SalesController::class, 'land_sale_list'])->name('land_sale_list');
    Route::get('/land_sale_bill_generate/{id}', [SalesController::class, 'land_sale_bill_generate'])->name('land_sale_bill_generate');
    Route::post('/save-land-sale-payment', [SalesController::class, 'store_land_sale_payment'])->name('store_land_sale_payment');
    Route::post('/development-payment', [SalesController::class, 'development_payment'])->name('development_payment');
    Route::get('/development-payment-list', [SalesController::class, 'development_payment_list'])->name('development_payment_list');
    Route::get('/paid_installment_list', [SalesController::class, 'paid_installment_list'])->name('paid_installment_list');
    Route::get('/payment_credit_voucher/{id}', [SalesController::class, 'payment_credit_voucher'])->name('payment_credit_voucher');
    Route::get('/sale_payment_money_receipt/{id}', [SalesController::class, 'sale_payment_money_receipt'])->name('sale_payment_money_receipt');
    Route::get('/installment_statement', [SalesController::class, 'installment_statement'])->name('installment_statement');
    Route::get('/invoice-print', [SalesController::class, 'print']);
    Route::get('/invoice-pdf', [SalesController::class, 'pdf']);
    Route::get('/create-invoice', [SalesController::class, 'create'])->name('create-invoice');
    Route::post('/save-invoice', [SalesController::class, 'store'])->name('save-invoice');
    Route::get('/edit-invoice/{id}', [SalesController::class, 'edit'])->name('edit-invoice');
    Route::post('/update-invoice', [SalesController::class, 'update'])->name('update-invoice');
    Route::get('/user-type', [SalesController::class, 'user_type_index'])->name('user_type_index');
    Route::post('/user-type-store', [SalesController::class, 'user_type_store'])->name('user_type_store');
    Route::post('/user-type-update', [SalesController::class, 'update_user_type'])->name('update_user_type');
    Route::get('/land-sale-employee', [SalesController::class, 'land_sale_employee'])->name('land_sale_employee');
    Route::post('/land-sale-employee-store', [SalesController::class, 'land_sale_employee_store'])->name('land_sale_employee_store');
    Route::post('/land-sale-employee-update/{id}', [SalesController::class, 'update_land_sale_employee'])->name('update_land_sale_employee');

    Route::get('/director-wise-employees', [SalesController::class, 'directorWiseEmployees'])->name('director_wise_employees');
    Route::get('/get-employees-by-director/{id}', [SalesController::class, 'getEmployeesByDirector']);
    Route::get('/get-employees-by-coordinator/{id}', [SalesController::class, 'getEmployeesByCoordinator']);
    Route::get('/get-directors-list', [SalesController::class, 'getDirectorsList']);
    Route::get('/get-all-employees', [SalesController::class, 'getAllEmployees']);
    Route::get('/get-coordinators-list', [SalesController::class, 'getCoordinatorsList']);
    Route::get('/get-shareholders-list', [SalesController::class, 'getShareholdersList']);
    Route::get('/get-outsiders-list', [SalesController::class, 'getOutsidersList']);

    Route::get('/employee-status/{id}', [SalesController::class, 'employeeStatusUpdate'])->name('employee_status');
    Route::get('/sales-related-incentive', [SalesController::class, 'sales_related_incentive'])->name('sales_related_incentive');
    Route::get('/incentive-stock-list', [SalesController::class, 'incentive_stock_list'])->name('incentive_stock_list');
    Route::get('/incentive-withdrawn-list', [SalesController::class, 'incentive_withdrawn_list'])->name('incentive_withdrawn_list');
    Route::get('/get-incentive-amount/{id}', [SalesController::class, 'getIncentiveAmount']);
    Route::post('/save-incentive-withdraw', [SalesController::class, 'store_incentive_withdraw'])->name('store_incentive_withdraw');
    Route::get('/filter-employee-data', [SalesController::class, 'getEmployeeData'])->name('filter-employee-data');
    Route::get('/filter-employee-wise-data', [SalesController::class, 'getEmployeeWiseData'])->name('filter-employee-wise-data');
    Route::get('/coordinator-director-wise/{directorId}', [SalesController::class, 'getCoordinatorsByDirector'])->name('sales_executives_director_wise');
    Route::get('/shareholder-coordinator-wise/{directorId}/{selectedCoordinatorId}/', [SalesController::class, 'getShareholdersByCoordinator'])->name('shareholder_coordinator_wise');
    Route::get('/shareholder-director-wise/{directorId}', [SalesController::class, 'getShareholdersByDirector'])->name('shareholder_director_wise');
    Route::get('/outsider-coordinator-wise/{directorId}/{selectedCoordinatorId}/', [SalesController::class, 'getOutsidersByCoordinator'])->name('outsider_coordinator_wise');
    Route::get('/outsider-director-wise/{directorId}', [SalesController::class, 'getOutsidersByDirector'])->name('outsider_director_wise');
    Route::get('/customer-statement-list', [SalesController::class, 'customerStatement'])->name('customer-statement-list');

    Route::post('/approve-incentives/{land_sale_id}', [SalesController::class, 'approveIncentives'])->name('approve.incentives');




    // Landshare Routes
    Route::get('/landshare', [SalesController::class, 'landshareIndex'])->name('landshareindex');
    Route::get('/landshare/create', [SalesController::class, 'landshareCreate'])->name('landsharecreate');
    Route::post('/landshare/store', [SalesController::class, 'landshareStore'])->name('landsharestore');
    Route::get('/landshare/show/{id}', [SalesController::class, 'landshareShow'])->name('landshareshow');
    Route::get('/landshare/edit/{id}', [SalesController::class, 'landshareEdit'])->name('landshareedit');
    Route::post('/landshare/update/{id}', [SalesController::class, 'landshareUpdate'])->name('landshareupdate');
    Route::delete('/landshare/delete/{id}', [SalesController::class, 'landshareDestroy'])->name('landsharedestroy');
    Route::get('/get-land-data', [SalesController::class, 'getLandData'])->name('getLandData');



    //Report
    Route::get('/income-statement-list', [ReportController::class, 'incomeStatement'])->name('income-statement-list');
    Route::get('/income-statement-list-view', [ReportController::class, 'incomeStatementView'])->name('income-statement-list-view');

    Route::get('/receipt_and_payment_statement', [ReportController::class, 'receiptAndPaymentStatement'])->name('receipt_and_payment_statement');
    Route::get('/balance_sheet', [ReportController::class, 'balance_sheet'])->name('balance_sheet');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//************************************* Site Manager Route List ********************* */

Route::prefix('money-requisition')->group(function () {
    Route::get('/entry', [FinancialRequisitionController::class, 'requisitionForm'])->name('money-requisition-entry');
    Route::post('/store', [FinancialRequisitionController::class, 'storeRequisition'])->name('money-requisition-store');
    Route::post('/update', [FinancialRequisitionController::class, 'updateRequisition'])->name('money-requisition-update');
    Route::get('/list', [FinancialRequisitionController::class, 'requisitionList'])->name('money-requisition-list');
    Route::get('/reject/list', [FinancialRequisitionController::class, 'rejectRequisitionList'])->name('reject-requisition-list');
    Route::get('/site-print', [FinancialRequisitionController::class, 'print']);
    Route::get('/site-pdf', [FinancialRequisitionController::class, 'pdf']);
});

Route::get('/site-opening-balance', [SiteExpenseController::class, 'openingBalance'])->name('site-opening-balance');
Route::post('/site-opening-balance-store', [SiteExpenseController::class, 'openingBalanceStore'])->name('site-opening-balance-store');

Route::prefix('expense')->group(function () {
    Route::get('/list', [SiteExpenseController::class, 'siteExpenseList'])->name('site-expense-list');
    Route::get('/entry', [SiteExpenseController::class, 'siteExpenseEntryform'])->name('site-expense-entry');
    Route::post('/store', [SiteExpenseController::class, 'siteExpenseStore'])->name('site-expense-store');
    Route::get('/edit/{id}', [SiteExpenseController::class, 'editSiteExpense'])->name('site-expense-edit');
    Route::post('/update/{id}', [SiteExpenseController::class, 'updateSiteExpense'])->name('site-expense-update');
    Route::get('/delete/{id}', [SiteExpenseController::class, 'delete'])->name('site-expense-delete');
    Route::get('/print/{id}', [SiteExpenseController::class, 'printSiteDebitVoucher'])->name('print-site-expense');
});

Route::prefix('report')->group(function () {
    Route::get('/daily-ledger', [SiteReportController::class, 'dailyLedger'])->name('daily-ledger-report');
    Route::get('/daily-ledger-after-search', [SiteReportController::class, 'datewiseDailyLedger'])->name('daily-ledger-after-search');
});

//System Setting Route
Route::get('systemsetting', [SystemSettingController::class, 'index'])->name('systemsetting.index');
Route::post('systemsetting/update', [SystemSettingController::class, 'update'])->name('systemsetting.update');
