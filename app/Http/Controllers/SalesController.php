<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Flat;
use App\Models\Fund;
use App\Models\Item;
use App\Models\Plot;
use App\Models\Road;
use App\Models\Sales;
use App\Models\Sector;
use App\Models\Company;
use App\Models\FundLog;
use App\Models\Project;
use App\Models\Customer;
use App\Models\LandSale;
use App\Models\PlotType;
use App\Models\UserType;
use App\Models\FlatFloor;
use App\Models\LandStock;
use App\Models\Investment;
use App\Models\AccountHead;
use App\Models\BankAccount;
use App\Models\Installment;
use App\Models\LandPayment;
use App\Models\PaymentType;
use App\Models\PlotSaleDetail;
use App\Models\FlatSaleDetail;
use App\Models\Landshare;
use App\Models\LandSaleDetail;
use Illuminate\Http\Request;
use App\Models\SalesIncentive;
use App\Models\DevelopmentPayment;
use App\Models\AccountCategory;
use App\Models\LandSaleEmployee;
use App\Models\FundCurrentBalance;
use Illuminate\Support\Facades\DB;
use App\Models\IncentiveStockPerSale;
use App\Models\SalesIncentivePayment;
use Illuminate\Support\Facades\Session;

class SalesController extends Controller
{
    public function getIncentiveAmount($id)
    {
        // dd($id);
        $incentive = IncentiveStockPerSale::where('land_sale_employee_id', $id)->first();
        // dd($incentive);
        return response()->json([
            'incentive_amount' => $incentive->left_amount ?? $incentive->incentive_amount,
        ]);
    }

    public function getEmployeeData(Request $request)
    {
        $head_id = $request->get('head_id');
        // dd($head_id);
        $employees = IncentiveStockPerSale::where('head_id', $head_id)
            ->whereNotNull('left_amount')
            ->where('left_amount', '>', 0)
            ->with('employee')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->land_sale_employee_id,
                    'employee_name' => $item->employee->employee_name ?? 'N/A',
                ];
            });

        // dd($employees);
        return response()->json($employees);
    }

    public function getEmployeeWiseData(Request $request)
    {
        $head_id = $request->get('head_id_filter');
        // dd($head_id);
        $employees = IncentiveStockPerSale::where('head_id', $head_id)
            ->with('employee')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->land_sale_employee_id,
                    'employee_name' => $item->employee->employee_name ?? 'N/A',
                ];
            });

        // dd($employees);
        return response()->json($employees);
    }

    public function user_type_index()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'user_type';
        $data['user_type'] = UserType::with('company')->get();
        // dd($data['user_type']);

        return view('sales.user_type', $data);
    }

    public function user_type_store(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);
        $model = new UserType();
        $model->type = $request->post('type');
        $model->company_id = Session::get('company_id');
        $model->save();

        return redirect()->route('user_type_index')->with('status', 'User Type created!');
    }

    public function update_user_type(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);
        //dd($request->post());
        $model = UserType::find($request->post('id'));
        $model->type = $request->post('type');
        $model->company_id = Session::get('company_id');
        $model->save();

        return redirect()->route('user_type_index')->with('status', 'User Type updated!');
    }

    public function land_sale_employee(Request $request)
    {
        // dd($request->all());
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'land_sale_employee';
        $data['user_type'] = UserType::with('company')->where(['company_id' => Session::get('company_id')])->get();
        $data['sale_emp'] = LandSaleEmployee::with('userType', 'head', 'category')->where(['company_id' => Session::get('company_id')])->orderBy('id', 'DESC')->get();
        $data['land_sale_director'] = LandSaleEmployee::with('userType')
            ->where('company_id', Session::get('company_id'))
            ->whereHas('userType', function ($query) {
                $query->where('type', 'Director');
            })
            ->get();
        $data['land_sale_coordinator'] = LandSaleEmployee::with('userType')
            ->where('company_id', Session::get('company_id'))
            ->whereHas('userType', function ($query) {
                $query->where('type', 'Co-ordinator');
            })
            ->get();
        $invest_code = Investment::where('company_id', Session::get('company_id'))->orderByDesc('id')->first();
        if ($invest_code) {
            $data['lastInvestId'] = $invest_code->id;
        }
        $data['categories'] = AccountCategory::get();
        $data['head'] = AccountHead::get();

        $sale_employees = LandSaleEmployee::with('userType', 'head', 'category')->where(['company_id' => Session::get('company_id')])->orderBy('id', 'DESC');
        $where = array();
        if ($request->employee_name != null) {
            $where['employee_name'] = $request->employee_name;
            $sale_employees->where('id', '=', $request->employee_name);
        }
        if ($request->designation != null) {
            $where['designation'] = $request->designation;
            $sale_employees->where('designation', '=', $request->designation);
        }
        if ($request->employee_code != null) {
            $where['employee_code'] = $request->employee_code;
            $sale_employees->where('employee_code', '=', $request->employee_code);
        }
        if ($request->head_id != null) {
            $where['head_id'] = $request->head_id;
            $sale_employees->where('head_id', '=', $request->head_id);
        }
        if ($request->start_date != null) {
            $where['start_date'] = $request->start_date;
            $sale_employees->where('created_at', '>=', $request->start_date);
        }
        if ($request->end_date != null) {
            $where['end_date'] = $request->end_date;
            $sale_employees->where('created_at', '<=', $request->end_date);
        }
        $sale_employees = $sale_employees->paginate(20);
        $sale_employees->appends($where);
        $data['sale_employees'] = $sale_employees;

        return view('sales.land_sale_employee', $data);
    }

    public function land_sale_employee_store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required',
        ]);

        // $lastEmployee = LandSaleEmployee::where('company_id', Session::get('company_id'))->latest()->first();
        // $lastCode = ($lastEmployee) ? $lastEmployee->id : 0;
        // $prefix = "CODE";
        // $nextCode = $lastCode + 1;
        // $EmployeeCode = $prefix . '-' . $nextCode;

        $model = new LandSaleEmployee();
        $model->employee_name = $request->employee_name;
        $model->employee_code = $request->employee_code;
        $model->user_type_id = $request->user_type_id;
        $model->designation = $request->designation;
        $model->email = $request->email;
        $model->mobile_no = $request->mobile_no;
        $model->address = $request->address;
        $model->director_id = $request->director_id;
        $model->coordinator_id = $request->coordinator_id;
        // $model->shareholder_id = $request->shareholder_id;
        $model->date_of_birth = $request->date_of_birth;
        $model->blood_group = $request->blood_group;
        $model->head_id = $request->head;
        $model->category_id = $request->category_id;

        if ($request->hasFile('nid')) {
            $newNidName = uniqid('land_sale_employee_nid_') . '.' . $request->nid->extension();
            $request->nid->move(public_path('upload_images/land_sale_employee_nid'), $newNidName);
            $model->nid = $newNidName;
        }

        if ($request->hasFile('employee_photo')) {
            $newPhotoName = uniqid('land_sale_employee_photo_') . '.' . $request->employee_photo->extension();
            $request->employee_photo->move(public_path('upload_images/employee_photo'), $newPhotoName);
            $model->employee_photo = $newPhotoName;
        }

        $model->company_id = Session::get('company_id');
        $model->created_by = auth()->user()->id;
        $model->save();

        return redirect()->route('land_sale_employee')->with('status', 'Land Sale Employee inserted!');
    }

    public function update_land_sale_employee(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'employee_name' => 'required',
        ]);

        $employee = LandSaleEmployee::findOrFail($id);

        if ($request->hasFile('nid')) {
            // Remove existing file if it exists
            if ($employee->nid && file_exists(public_path('upload_images/land_sale_employee_nid/' . $employee->nid))) {
                unlink(public_path('upload_images/land_sale_employee_nid/' . $employee->nid));
            }

            $newNidName = uniqid('land_sale_employee_nid_') . '.' . $request->nid->extension();
            $request->nid->move(public_path('upload_images/land_sale_employee_nid'), $newNidName);
            $employee->nid = $newNidName;
        } elseif ($request->has('remove_image')) {
            $employee->nid = null;
        }

        if ($request->hasFile('employee_photo')) {
            // Remove existing file if it exists
            if ($employee->employee_photo && file_exists(public_path('upload_images/employee_photo/' . $employee->employee_photo))) {
                unlink(public_path('upload_images/employee_photo/' . $employee->employee_photo));
            }

            $newPhotoName = uniqid('land_sale_employee_photo_') . '.' . $request->employee_photo->extension();
            $request->employee_photo->move(public_path('upload_images/employee_photo'), $newPhotoName);
            $employee->employee_photo = $newPhotoName;
        } elseif ($request->has('remove_image')) {
            $employee->employee_photo = null;
        }

        $employee->employee_name = $request->employee_name;
        $employee->employee_code = $request->employee_code;
        $employee->user_type_id = $request->user_type_id;
        $employee->designation = $request->designation;
        $employee->email = $request->email;
        $employee->mobile_no = $request->mobile_no;
        $employee->address = $request->address;
        $employee->director_id = $request->director_id;
        $employee->coordinator_id = $request->coordinator_id;
        // $employee->shareholder_id = $request->shareholder_id;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->blood_group = $request->blood_group;
        $employee->company_id = Session::get('company_id');
        $employee->updated_by = auth()->user()->id;
        $employee->head_id = $request->head;
        $employee->category_id = $request->category_id;
        // dd($employee);
        $employee->update();
        IncentiveStockPerSale::where('land_sale_employee_id', $employee->id)->where('company_id', Session::get('company_id'))->update(['head_id' => $employee->head_id]);

        return redirect()->route('land_sale_employee', ['page' => $request->input('page')])
            ->with('success', 'Data updated successfully!');
    }

    public function employeeStatusUpdate($id)
    {
        $model = LandSaleEmployee::find($id);

        if ($model->status == 1) {
            $model->status = 2;
            $model->update();
        } else {
            $model->status = 1;
            $model->update();
        }
        $msg = "Status Updated...";

        return redirect()->back()->with('status', $msg);
    }
    
    /**
     *
     * Author Name: Sazal Abdullah;
     * Date: 28-05-2025
     *
     */
    
    public function directorWiseEmployees()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'director_wise_employees';
        $data['directors'] = LandSaleEmployee::whereHas('userType', function ($q) {
            $q->where('type', 'Director');
        })->where('company_id', Session::get('company_id'))->get();

        return view('sales.director_wise_employees', $data);
    }

    public function getEmployeesByDirector($id)
    {
        // Get all employees (Co-ordinator, Shareholder, Outsider) under this director
        $employees = LandSaleEmployee::with(['userType', 'director', 'coordinator'])
            ->where('company_id', Session::get('company_id'))
            ->where('director_id', $id)
            ->whereHas('userType', function ($query) {
                $query->whereIn('type', ['Co-ordinator', 'Shareholder', 'Outsider']);
            })
            ->get();

        return response()->json($employees);
    }

    public function getEmployeesByCoordinator($id)
    {
        // Get only Shareholder and Outsider under this coordinator
        $employees = LandSaleEmployee::with(['userType', 'director', 'coordinator'])
            ->where('company_id', Session::get('company_id'))
            ->where('coordinator_id', $id)
            ->whereHas('userType', function ($query) {
                $query->whereIn('type', ['Shareholder', 'Outsider']);
            })
            ->get();

        return response()->json($employees);
    }

    public function getDirectorsList()
    {
        $directors = LandSaleEmployee::whereHas('usertype', function($query) {
            $query->where('type', 'Director');
        })->get();

        return response()->json($directors);
    }
    
        public function getAllEmployees()
    {
        $employees = LandSaleEmployee::with(['userType', 'director', 'coordinator'])
            ->where('company_id', Session::get('company_id'))
            ->get();

        return response()->json($employees);
    }


    public function getCoordinatorsList()
    {
        $coordinators = LandSaleEmployee::with(['userType', 'director', 'coordinator'])
            ->whereHas('userType', function($query) {
                $query->where('type', 'Co-ordinator');
            })
            ->where('company_id', Session::get('company_id'))
            ->get();

        return response()->json($coordinators);
    }

    public function getShareholdersList()
    {
        $shareholders = LandSaleEmployee::with(['userType', 'director', 'coordinator'])
            ->whereHas('userType', function($query) {
                $query->where('type', 'Shareholder');
            })
            ->where('company_id', Session::get('company_id'))
            ->get();

        return response()->json($shareholders);
    }

    public function getOutsidersList()
    {
        $outsiders = LandSaleEmployee::with(['userType', 'director', 'coordinator'])
            ->whereHas('userType', function($query) {
                $query->where('type', 'Outsider');
            })
            ->where('company_id', Session::get('company_id'))
            ->get();

        return response()->json($outsiders);
    }


    public function index(Request $request)
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'invoice-list';
        $sales = Sales::where(['company_id' => Session::get('company_id')])->with('company', 'project', 'sales_details', 'sales_details.item', 'sales_payment', 'sales_payment.payment_details', 'sales_payment.fund')->orderBy('id', 'DESC');
        //dd($sales->sales_payment);
        $where = array();
        if ($request->project_id != null) {
            $where['project_id'] = $request->project_id;
            $sales->where('project_id', '=', $request->project_id);
        }
        if ($request->start_date != null) {
            $where['start_date'] = $request->start_date;
            $sales->where('sales_date', '>=', $request->start_date);
        }
        if ($request->end_date != null) {
            $where['end_date'] = $request->end_date;
            $sales->where('sales_date', '<=', $request->end_date);
        }
        $sales = $sales->paginate(20);
        $sales->appends($where);
        $data['sales'] = $sales;
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();

        return view('sales.invoice_list', $data);
    }

    // public function land_sale_list()
    // {
    //     $data['main_menu'] = 'sales';
    //     $data['child_menu'] = 'land_sale_list';
    //     $data['fund_types'] = Fund::all();
    //     $data['banks'] = Bank::get();
    //     $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
    //     $data['payment_types'] = PaymentType::get();
    //     $data['company_name'] = Session::get('company_name');
    //     $currentDate = Carbon::now();
    //     $startOfWeek = $currentDate->copy()->startOfWeek(Carbon::SATURDAY);
    //     $endOfWeek = $currentDate->copy()->endOfWeek(Carbon::FRIDAY);
    //     // $data['landSales'] = LandSale::with('plot','flat', 'customer','land_payments','installment')->where('company_id', Session::get('company_id'))->get();
    //     // dd($data['landSales']);
    //     // $data['landSaleCount'] = LandSale::join('installments', 'land_sales.id', '=', 'installments.land_sale_id')
    //     //     ->where('land_sales.company_id', Session::get('company_id'))
    //     //     ->whereBetween('installments.installment_date', [$startOfWeek, $endOfWeek])
    //     //     ->count();
    //     //     dd($data['landSaleCount']);

    //     // $data['landSaleColor'] = LandSale::join('installments', 'land_sales.id', '=', 'installments.land_sale_id')
    //     //     ->where('land_sales.company_id', Session::get('company_id'))
    //     //     ->whereBetween('installments.installment_date', [$startOfWeek, $endOfWeek])
    //     //     ->get();

    //     $data['land_sale_list'] = LandSale::where(['company_id' => Session::get('company_id')])->with('plot', 'flat', 'customer', 'land_payments')->orderBy('id', 'DESC')->get();
    //     // dd($data['land_sale_list']);
    //     return view('sales.land_sale_details_list', $data);
    // }
    
    //     public function land_sale_list()
    // {
    //     $data['main_menu'] = 'sales';
    //     $data['child_menu'] = 'land_sale_list';
    //     $data['fund_types'] = Fund::all();
    //     $data['banks'] = Bank::get();
    //     $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
    //     $data['payment_types'] = PaymentType::get();
    //     $data['company_name'] = Session::get('company_name');

    //     // মাল্টিপল ফ্ল্যাট/প্লট সহ ডাটা লোড করুন
    //     $data['land_sale_list'] = LandSale::where(['company_id' => Session::get('company_id')])
    //         ->with(['customer', 'land_payments', 'flats.flat_floor', 'plots.road.sector'])
    //         ->orderBy('id', 'DESC')
    //         ->get();

    //     return view('sales.land_sale_details_list', $data);
    // }
    
    public function land_sale_list()
{
    $data['main_menu'] = 'sales';
    $data['child_menu'] = 'land_sale_list';
    $data['fund_types'] = Fund::all();
    $data['banks'] = Bank::get();
    $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
    $data['payment_types'] = PaymentType::get();
    $data['company_name'] = Session::get('company_name');

    // মাল্টিপল কাস্টমার সহ ডাটা লোড করুন
    $data['land_sale_list'] = LandSale::where(['company_id' => Session::get('company_id')])
        ->with([
            'customer', // মেইন কাস্টমার
            'customers', // সব কাস্টমার
            'land_payments',
            'flats.flat_floor',
            'plots.road.sector',
            'landshares.project' // Add this line to load landshares with their project
        ])
        ->orderBy('id', 'DESC')
        ->get();

    return view('sales.land_sale_details_list', $data);
}


    //     public function sales_related_incentive(Request $request)
    // {
    //     $data['main_menu'] = 'sales';
    //     $data['child_menu'] = 'sales_related_incentive';
    //     $data['fund_types'] = Fund::all();
    //     $data['banks'] = Bank::get();
    //     $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
    //     $data['payment_types'] = PaymentType::get();
    //     $data['company_name'] = Session::get('company_name');
    //     $sales_related_incentive = LandSale::where(['company_id' => Session::get('company_id')])->with('incentive', 'director')->orderBy('id', 'DESC');
    //     $where = array();
    //     if ($request->type != null) {
    //         $where['type'] = $request->type;
    //         $sales_related_incentive->where('type', '=', $request->type);
    //     }
    //     if ($request->director != null) {
    //         $sales_related_incentive->whereHas('director', function ($query) use ($request) {
    //             $query->where('employee_name', 'like', "%{$request->director}%");
    //         });
    //     }

    //     $sales_related_incentive = $sales_related_incentive->paginate(20);
    //     $sales_related_incentive->appends($where);
    //     $data['sales_related_incentive'] = $sales_related_incentive;
    //     return view('sales.sales_related_incentive_list', $data);
    // }
    
    public function sales_related_incentive(Request $request)
{
    $data['main_menu'] = 'sales';
    $data['child_menu'] = 'sales_related_incentive';
    $data['fund_types'] = Fund::all();
    $data['banks'] = Bank::get();
    $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
    $data['payment_types'] = PaymentType::get();
    $data['company_name'] = Session::get('company_name');

    $sales_related_incentive = LandSale::where(['company_id' => Session::get('company_id')])
        ->with(['incentive', 'director', 'customer.project', 'flats.flat_floor', 'plots.road.sector', 'plots.plotType'])
        ->orderBy('id', 'DESC');

    $where = array();
    if ($request->type != null) {
        $where['type'] = $request->type;
        $sales_related_incentive->where('type', '=', $request->type);
    }
    if ($request->director != null) {
        $sales_related_incentive->whereHas('director', function ($query) use ($request) {
            $query->where('employee_name', 'like', "%{$request->director}%");
        });
    }

    // তারিখ অনুযায়ী ফিল্টার যোগ করুন
    if ($request->start_date != null && $request->end_date != null) {
        $where['start_date'] = $request->start_date;
        $where['end_date'] = $request->end_date;

        $startDate = date('Y-m-d 00:00:00', strtotime($request->start_date));
        $endDate = date('Y-m-d 23:59:59', strtotime($request->end_date));

        $sales_related_incentive->whereHas('incentive', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        });
    }

    // $sales_related_incentive = $sales_related_incentive->paginate(50);
    // $sales_related_incentive->appends($where);

    $sales_related_incentive = $sales_related_incentive->get();

    // নতুন টেবিল ভিউ এর জন্য ডাটা প্রস্তুত
    $incentiveData = [];
    foreach($sales_related_incentive as $sale) {
        if($sale->incentive) {
            $row = [
                'sale_id' => $sale->id,
                'invoice_no' => $sale->invoice_no ?? '',
                'customer_name' => $sale->customer->customer_name ?? '',
                'customer_code' => $sale->customer->customer_code ?? '',
                'type' => $sale->type,
                'directors' => [],
                'coordinators' => [],
                'shareholders' => [],
                'outsiders' => [],
                'total_incentive' => 0
            ];

            foreach($sale->incentive as $inc) {
                // তারিখ ফিল্টার চেক করুন (যদি তারিখ ফিল্টার দেওয়া থাকে)
                if (isset($startDate) && isset($endDate)) {
                    if ($inc->created_at < $startDate || $inc->created_at > $endDate) {
                        continue;
                    }
                }

                // Director তথ্য যোগ
                if($inc->director) {
                    $row['directors'][] = [
                        'name' => $inc->director->employee_name ?? '',
                        'percent' => $inc->directors_incentive ?? '',
                        'amount' => (float)($inc->directors_incentive_amount ?? $inc->director_per_sales_incentive ?? 0),
                        'is_per_sales' => $inc->director_per_sales_incentive !== null,
                        'created_at' => $inc->created_at->format('d-m-Y')
                    ];
                }

                // Coordinator তথ্য যোগ
                if($inc->co_ordinator) {
                    $row['coordinators'][] = [
                        'name' => $inc->co_ordinator->employee_name ?? '',
                        'percent' => $inc->coordinators_incentive ?? '',
                        'amount' => (float)($inc->coordinators_incentive_amount ?? $inc->coordinator_per_sales_incentive ?? 0),
                        'is_per_sales' => $inc->coordinator_per_sales_incentive !== null,
                        'created_at' => $inc->created_at->format('d-m-Y')
                    ];
                }

                // Shareholder তথ্য যোগ
                if($inc->shareholder) {
                    $row['shareholders'][] = [
                        'name' => $inc->shareholder->employee_name ?? '',
                        'percent' => $inc->shareholders_incentive ?? '',
                        'amount' => (float)($inc->shareholders_incentive_amount ?? $inc->shareholder_per_sales_incentive ?? 0),
                        'is_per_sales' => $inc->shareholder_per_sales_incentive !== null,
                        'created_at' => $inc->created_at->format('d-m-Y')
                    ];
                }

                // Outsider তথ্য যোগ
                if($inc->outsider) {
                    $row['outsiders'][] = [
                        'name' => $inc->outsider->employee_name ?? '',
                        'percent' => $inc->outsiders_incentive ?? '',
                        'amount' => (float)($inc->outsiders_incentive_amount ?? $inc->outsider_per_sales_incentive ?? 0),
                        'is_per_sales' => $inc->outsider_per_sales_incentive !== null,
                        'created_at' => $inc->created_at->format('d-m-Y')
                    ];
                }

                // মোট ইনসেনটিভ হিসাব
                $row['total_incentive'] += ($inc->directors_incentive_amount ?? $inc->director_per_sales_incentive ?? 0) +
                                        ($inc->coordinators_incentive_amount ?? $inc->coordinator_per_sales_incentive ?? 0) +
                                        ($inc->shareholders_incentive_amount ?? $inc->shareholder_per_sales_incentive ?? 0) +
                                        ($inc->outsiders_incentive_amount ?? $inc->outsider_per_sales_incentive ?? 0);
            }

            // শুধুমাত্র সেই রেকর্ডগুলো যোগ করুন যাদের অন্তত একটি ইনসেনটিভ আছে
            if(count($row['directors']) > 0 || count($row['coordinators']) > 0 ||
               count($row['shareholders']) > 0 || count($row['outsiders']) > 0) {
                $incentiveData[] = $row;
            }
        }
    }

    $data['incentive_details'] = $incentiveData;
    $data['sales_related_incentive'] = $sales_related_incentive;

    return view('sales.sales_related_incentive_list', $data);
}

    ////// Incentive Payment

    // public function incentive_stock_list(Request $request)
    // {
    //     // dd($request->all());
    //     $data['main_menu'] = 'sales';
    //     $data['child_menu'] = 'incentive_payment_list';
    //     $data['fund_types'] = Fund::all();
    //     $data['banks'] = Bank::get();
    //     $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
    //     $data['payment_types'] = PaymentType::get();
    //     $data['company_name'] = Session::get('company_name');
    //     $data['head'] = IncentiveStockPerSale::with('head', 'employee')->where(['company_id' => Session::get('company_id')])->orderBy('id', 'DESC')->get();
    //     $incentive_stock = IncentiveStockPerSale::with('head', 'employee')->where(['company_id' => Session::get('company_id')])->orderBy('id', 'DESC');
    //     // dd($data['incentive_stock']);
    //     $where = array();
    //     if ($request->head_id != null) {
    //         $where['head_id'] = $request->head_id;
    //         $incentive_stock->where('head_id', '=', $request->head_id);
    //     }
    //     if ($request->land_sale_employee_id != null) {
    //         $where['land_sale_employee_id'] = $request->land_sale_employee_id;
    //         $incentive_stock->where('land_sale_employee_id', '=', $request->land_sale_employee_id);
    //     }
    //     $incentive_stock = $incentive_stock->paginate(20);
    //     $incentive_stock->appends($where);
    //     $data['incentive_stock'] = $incentive_stock;
    //     return view('sales.incentive_stock', $data);
    // }
    
    public function incentive_stock_list(Request $request)
{
    $data['main_menu'] = 'sales';
    $data['child_menu'] = 'incentive_payment_list';
    $data['fund_types'] = Fund::all();
    $data['banks'] = Bank::get();
    $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
    $data['payment_types'] = PaymentType::get();
    $data['company_name'] = Session::get('company_name');

    $incentive_stock = IncentiveStockPerSale::with([
        'head',
        'employee.userType',
        'landSale.customer',
        'landSale.project',
        'landSale.plot',
        'landSale.flat',
        'landSale.land_payments' => function($q) {
            $q->with(['PaymentType', 'bank', 'account'])
              ->orderBy('created_at', 'DESC');
        }
    ])->where(['company_id' => Session::get('company_id')])
      ->orderBy('id', 'DESC');


    if ($request->head_id) {
        $incentive_stock->where('head_id', $request->head_id);
    }
    if ($request->land_sale_employee_id) {
        $incentive_stock->where('land_sale_employee_id', $request->land_sale_employee_id);
    }

    $data['incentive_stock'] = $incentive_stock->paginate(20);
    $data['head'] = $data['incentive_stock']; // Duplicate for backward compatibility

    return view('sales.incentive_stock', $data);
}


    public function incentive_withdrawn_list(Request $request)
    {
        $data['main_menu']              = 'sales';
        $data['child_menu']             = 'incentive_payment_list';
        // $data['fund_types']             = Fund::all();
        // $data['banks']                  = Bank::get();
        // $data['accounts']               = BankAccount::where('company_id', Session::get('company_id'))->get();
        // $data['payment_types']          = PaymentType::get();
        $data['company_name']           = Session::get('company_name');
        $data['head'] = IncentiveStockPerSale::with('head', 'employee')->where(['company_id' => Session::get('company_id')])->orderBy('id', 'DESC')->get();
        $incentive_payment     = SalesIncentivePayment::with('sales_incentive')->where(['company_id' => Session::get('company_id')])->orderBy('id', 'DESC');
        // dd($data['incentive_payment']);
        $where = array();
        if ($request->voucher_no != null) {
            $where['voucher_no'] = $request->voucher_no;
            $incentive_payment->where('voucher_no', '=', $request->voucher_no);
        }
        if ($request->head_id != null) {
            $incentive_payment->whereHas('sales_incentive', function ($query) use ($request) {
                $query->where('head_id', $request->head_id);
            });
        }
        if ($request->land_sale_employee_id != null) {
            $incentive_payment->whereHas('sales_incentive', function ($query) use ($request) {
                $query->where('land_sale_employee_id', $request->land_sale_employee_id);
            });
        }
        $incentive_payment = $incentive_payment->paginate(20);
        $incentive_payment->appends($where);
        $data['incentive_payment'] = $incentive_payment;
        return view('sales.incentive_payment_list', $data);
    }

    public function getCoordinatorsByDirector($directorId)
    {
        $salesExecutives = LandSaleEmployee::with('userType')->where('director_id', $directorId)
            ->whereHas('userType', function ($query) {
                $query->where('type', 'Co-ordinator');
            })
            ->get(['id', 'employee_name', 'designation']);
        // dd($salesExecutives);
        return response()->json($salesExecutives);
    }

    public function getShareholdersByCoordinator($directorId, $selectedCoordinatorId)
    {
        $salesExecutives = LandSaleEmployee::with('userType')->where('director_id', $directorId)->where('coordinator_id', $selectedCoordinatorId)
            ->whereHas('userType', function ($query) {
                $query->where('type', 'Shareholder');
            })
            ->get(['id', 'employee_name', 'designation']);
        // dd($salesExecutives);

        return response()->json($salesExecutives);
    }

    public function getOutsidersByDirector($directorId)
    {
        $salesExecutivesOutsider = LandSaleEmployee::with('userType')->where('director_id', $directorId)
            ->whereHas('userType', function ($query) {
                $query->where('type', 'Outsider');
            })
            ->get(['id', 'employee_name', 'designation']);
        // dd($salesExecutives);
        return response()->json($salesExecutivesOutsider);
    }

    public function getOutsidersByCoordinator($directorId, $selectedCoordinatorId)
    {
        // dd($directorId, $selectedCoordinatorId);
        $salesExecutivesOutsider = LandSaleEmployee::with('userType')->where('director_id', $directorId)->where('coordinator_id', $selectedCoordinatorId)
            ->whereHas('userType', function ($query) {
                $query->where('type', 'Outsider');
            })
            ->get(['id', 'employee_name', 'designation']);
        // dd($salesExecutives);

        return response()->json($salesExecutivesOutsider);
    }

    public function getShareholdersByDirector($directorId)
    {
        $salesExecutives = LandSaleEmployee::with('userType')->where('director_id', $directorId)
            ->whereHas('userType', function ($query) {
                $query->where('type', 'Shareholder');
            })
            ->get(['id', 'employee_name', 'designation']);
        // dd($salesExecutives);
        return response()->json($salesExecutives);
    }

    public function land_sale_bill_generate($id)
    {
        $land_sale_payment = LandPayment::with('landSale', 'PaymentType')->where('land_sale_id', $id)->first();
        // dd($land_sale_payment);
        return view('sales.bill_generate', compact('land_sale_payment'));
    }

    //Application Form
    public function create_application_form()
{
    $data['main_menu'] = 'sales';
    $data['child_menu'] = 'application_form';
    $data['fund_types'] = Fund::all();
    $data['banks'] = Bank::get();
    $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
    $data['payment_types'] = PaymentType::get();
    $data['projects'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get(); // Changed from project_data to projects
    $data['plot_data'] = Plot::where(['company_id' => Session::get('company_id')])->with('road', 'sector')->get();
    $data['flat_data'] = Flat::where(['company_id' => Session::get('company_id')])->with('flat_floor')->get();
    $data['landshares'] = Landshare::with('project')->get();
    $data['land_sale_employee'] = LandSaleEmployee::with('userType')
        ->where('company_id', Session::get('company_id'))
        ->whereHas('userType', function ($query) {
            $query->where('type', 'Director');
        })
        ->get();
    $data['sales_employee'] = LandSaleEmployee::with('userType')
        ->where('company_id', Session::get('company_id'))
        ->get();

    return view('sales.create_application_form', $data);
}

// public function save_application_form(Request $request)
// {
//     DB::beginTransaction();
//     try {
//         // Common data
//         $coordinatorIds = $request->input('coordinator_id');
//         $coordinatorIncentives = $request->input('coordinators_incentive');
//         $coordinatorIncentiveAmounts = $request->input('coordinators_incentive_amount');

//         $shareholderIds = $request->input('shareholder_id');
//         $shareholderIncentives = $request->input('shareholders_incentive');
//         $shareholderIncentiveAmounts = $request->input('shareholders_incentive_amount');

//         $project_id = $request->project_id;
//         $project = Project::where('id', $project_id)->where('company_id', Session::get('company_id'))->select('name')->first();
//         $project_name = $project->name;
//         $lastLandSale = LandSale::latest()->first();

//         $lastNumber = ($lastLandSale) ? $lastLandSale->id : 0;
//         $prefix = strtoupper(substr($project_name, 0, 5));
//         $nextCustomerId = $lastNumber + 1;
//         $invoiceNumber = $prefix . '-' . $nextCustomerId;

//         $lastCode = ($lastLandSale) ? $lastLandSale->id : 0;
//         $prefix = "SALE";
//         $nextLandSaleCode = $lastCode + 1;
//         $landSaleNumber = $prefix . '-' . $nextLandSaleCode;

//         // Create Land Sale first
//         $land_sale = new LandSale();
//         $land_sale->project_id = $request->project_id;
//         $land_sale->company_id = Session::get('company_id');
//         $land_sale->type = $request->type;

//         if ($request->type == "Flat") {
//             $flatIds = $request->input('flat_id', []);
//             $land_sale->flat_total_price = $request->flat_total_price;
//         } else {
//             $plotIds = $request->input('plot_id', []);
//             $land_sale->rate_per_katha = $request->rate_per_katha;
//             $land_sale->rate_per_katha_words = $request->rate_per_katha_words;
//             $land_sale->total_price = $request->total_price;
//             $land_sale->total_amount_in_words = $request->total_amount_in_words;
//         }

//         $land_sale->application_date = $request->application_date;
//         $land_sale->invoice_no = $invoiceNumber;
//         $land_sale->land_sale_code = $landSaleNumber;

//         if ($request->paymentOption == "notMade") {
//             $land_sale->booking_money = $request->installment_booking_money;
//             $land_sale->down_payment = $request->installment_down_payment;
//             $land_sale->down_payment_word = $request->installment_down_payment_word;
//             $land_sale->booking_date = $request->installment_booking_date;
//             $land_sale->initial_payment_made_date = $request->installment_initial_payment_made_date;
//             $land_sale->remaining_amount = $request->installment_remaining_amount;
//         } else {
//             $land_sale->booking_money = $request->booking_money;
//             $land_sale->down_payment = $request->down_payment;
//             $land_sale->down_payment_word = $request->down_payment_word;
//             $land_sale->booking_date = $request->booking_date;
//             $land_sale->initial_payment_made_date = $request->initial_payment_made_date;
//             $land_sale->remaining_amount = $request->remaining_amount;
//         }

//         $land_sale->payment_option = $request->paymentOption;
//         $land_sale->director_id = $request->director_id;
//         $land_sale->directors_incentive = $request->directors_incentive;
//         $land_sale->directors_incentive_amount = $request->directors_incentive_amount;
//         $land_sale->directors_left_amount = $request->directors_left_amount;
//         $land_sale->created_by = auth()->user()->id;
//         $land_sale->save();

//         // Save main customer
//         $main_customer = $this->saveCustomer($request, $land_sale->id, false);
//         $land_sale->customer_id = $main_customer->id;
//         $land_sale->save();

//         // Save additional customers
//         $i = 1;
//         while($request->has('customer_name_'.$i)) {
//             $this->saveCustomer($request, $land_sale->id, $i);
//             $i++;
//         }

//         // Create flat or plot sale details records
//         if ($request->type == "Flat" && !empty($flatIds)) {
//             foreach ($flatIds as $flatId) {
//                 $flatSaleDetail = new FlatSaleDetail();
//                 $flatSaleDetail->land_sale_id = $land_sale->id;
//                 $flatSaleDetail->flat_id = $flatId;
//                 $flatSaleDetail->company_id = Session::get('company_id');
//                 $flatSaleDetail->save();

//                 $flat = Flat::find($flatId);
//                 if ($flat) {
//                     $flat->save();
//                 }
//             }
//         } elseif (!empty($plotIds)) {
//             foreach ($plotIds as $plotId) {
//                 $plotSaleDetail = new PlotSaleDetail();
//                 $plotSaleDetail->land_sale_id = $land_sale->id;
//                 $plotSaleDetail->plot_id = $plotId;
//                 $plotSaleDetail->company_id = Session::get('company_id');
//                 $plotSaleDetail->save();

//                 $plot = Plot::find($plotId);
//                 if ($plot) {
//                     $plot->save();
//                 }
//             }
//         }

//         // Payment processing (same as before)
//         $payment = null;
//         $initial_payment = null;

//         $lastLandSalePayment = LandPayment::latest()->first();
//         $lastNumber = ($lastLandSalePayment) ? $lastLandSalePayment->id : 0;
//         $prefix = strtoupper(substr($project_name, 0, 5));
//         $voucherPaymentNumber = $prefix . '-' . ($lastNumber + 1);

//         if ($land_sale->payment_option == "notMade") {
//             $installment = new Installment();
//             $installment->land_sale_id = $land_sale->id;
//             $installment->type = $request->type;
//             $installment->monthly_installment = $request->monthly_installment;
//             $installment->total_installment_number = $request->total_installment_number;
//             $installment->initial_payment = $request->down_payment + $request->booking_money;
//             $installment->company_id = Session::get('company_id');
//             $installment->created_by = auth()->user()->id;
//             $installment->save();

//             $payment = new LandPayment();
//             $payment->land_sale_id = $land_sale->id;
//             $payment->voucher_no = $voucherPaymentNumber;
//             $payment->project_id = $request->project_id;
//             $payment->remarks = $request->note_installment;
//             $payment->payment_type_id = $request->installment_payment_type_id;
//             $payment->booking_cheque_no = $request->installment_booking_cheque_no;
//             $payment->down_payment_cheque_no = $request->installment_down_payment_cheque_no;
//             $payment->fund_id = $request->installment_fund_id;
//             $payment->bank_id = $request->installment_bank_id;
//             $payment->account_id = $request->installment_account_id;
//             $payment->amount = $request->installment_down_payment + $request->installment_booking_money;
//             $payment->pay_date = $request->installment_initial_payment_made_date;
//             $payment->payment_option = $request->paymentOption;
//             $payment->company_id = Session::get('company_id');
//             $payment->save();

//             // ... rest of payment processing code (same as before)
//         } else {
//             $initial_payment = new LandPayment();
//             $initial_payment->land_sale_id = $land_sale->id;
//             $initial_payment->project_id = $request->project_id;
//             $initial_payment->voucher_no = $voucherPaymentNumber;
//             $initial_payment->remarks = $request->note;
//             $initial_payment->payment_type_id = $request->payment_type_id;
//             $initial_payment->booking_cheque_no = $request->booking_cheque_no;
//             $initial_payment->down_payment_cheque_no = $request->down_payment_cheque_no;
//             $initial_payment->fund_id = $request->fund_id;
//             $initial_payment->bank_id = $request->bank_id;
//             $initial_payment->account_id = $request->account_id;
//             $initial_payment->amount = $land_sale->down_payment + $land_sale->booking_money;
//             $initial_payment->pay_date = $request->initial_payment_made_date;
//             $initial_payment->payment_option = $request->paymentOption;
//             $initial_payment->company_id = Session::get('company_id');
//             $initial_payment->save();

//             // ... rest of initial payment processing code (same as before)
//         }

//         // // Incentives processing (same as before)
//         // $outsiders = $request->input('outsider_id', []);
//         // $shareholders = $request->input('shareholder_id', []);
//         // $coordinators = $request->input('coordinator_id', []);
//         // $director = $request->input('director_id');

//         // if ($director && ($payment || $initial_payment)) {
//         //     $paymentAmount = $initial_payment->amount ?? $payment->amount ?? 0;
//         //     $directorIncentiveAmount = (floatval($request->input('directors_incentive')) / 100) * $paymentAmount;

//         //     $director_incentive = new SalesIncentive();
//         //     $director_incentive->land_sale_id = $land_sale->id;
//         //     $director_incentive->director_id = $director;
//         //     $director_incentive->directors_incentive = $request->input('directors_incentive');
//         //     $director_incentive->directors_incentive_amount = $directorIncentiveAmount;
//         //     $director_incentive->directors_left_amount = $directorIncentiveAmount;
//         //     $director_incentive->director_per_sales_incentive = $directorIncentiveAmount;
//         //     $director_incentive->company_id = Session::get('company_id');
//         //     $director_incentive->created_by = auth()->user()->id;
//         //     $director_incentive->status = 'pending';
//         //     $director_incentive->save();
//         // }

//         $outsiders = $request->input('outsider_id', []);
//         $shareholders = $request->input('shareholder_id', []);
//         $coordinators = $request->input('coordinator_id', []);
//         $director = $request->input('director_id');

//         // Director (always 1 entry)
//         if ($director && ($payment || $initial_payment)) {
//             $paymentAmount = $initial_payment->amount ?? $payment->amount ?? 0;
//             $directorIncentiveAmount = (floatval($request->input('directors_incentive')) / 100) * $paymentAmount;

//             $director_incentive = new SalesIncentive();
//             $director_incentive->land_sale_id = $land_sale->id;
//             $director_incentive->director_id = $director;
//             $director_incentive->directors_incentive = $request->input('directors_incentive');
//             $director_incentive->directors_incentive_amount = $directorIncentiveAmount;
//             $director_incentive->directors_left_amount = $directorIncentiveAmount;
//             $director_incentive->director_per_sales_incentive = $directorIncentiveAmount;
//             $director_incentive->company_id = Session::get('company_id');
//             $director_incentive->created_by = auth()->user()->id;
//             $director_incentive->status = 'pending';
//             $director_incentive->save();
//         }

//         // Coordinators (entries based on coordinators count)
//         foreach ($coordinators as $index => $coordinatorId) {
//             if ($payment || $initial_payment) {
//                 $paymentAmount = $initial_payment->amount ?? $payment->amount ?? 0;
//                 $coordinatorIncentiveAmount = (floatval($request->input('coordinators_incentive')[$index]) / 100) * $paymentAmount;

//                 $coordinator_incentive = new SalesIncentive();
//                 $coordinator_incentive->land_sale_id = $land_sale->id;
//                 $coordinator_incentive->director_id = $director;
//                 $coordinator_incentive->coordinator_id = $coordinatorId;
//                 $coordinator_incentive->coordinators_incentive = $request->input('coordinators_incentive')[$index];
//                 $coordinator_incentive->coordinators_incentive_amount = $coordinatorIncentiveAmount;
//                 $coordinator_incentive->coordinator_per_sales_incentive = $coordinatorIncentiveAmount;
//                 $coordinator_incentive->company_id = Session::get('company_id');
//                 $coordinator_incentive->created_by = auth()->user()->id;
//                 $coordinator_incentive->status = 'pending';
//                 $coordinator_incentive->save();
//             }
//         }

//         // Shareholders (entries based on shareholders count)
//         foreach ($shareholders as $index => $shareholderId) {
//             if ($payment || $initial_payment) {
//                 $paymentAmount = $initial_payment->amount ?? $payment->amount ?? 0;
//                 $shareholderIncentiveAmount = (floatval($request->input('shareholders_incentive')[$index]) / 100) * $paymentAmount;

//                 $shareholder_incentive = new SalesIncentive();
//                 $shareholder_incentive->land_sale_id = $land_sale->id;
//                 $shareholder_incentive->director_id = $director;
//                 $shareholder_incentive->shareholder_id = $shareholderId;
//                 $shareholder_incentive->shareholders_incentive = $request->input('shareholders_incentive')[$index];
//                 $shareholder_incentive->shareholders_incentive_amount = $shareholderIncentiveAmount;
//                 $shareholder_incentive->shareholder_per_sales_incentive = $shareholderIncentiveAmount;
//                 $shareholder_incentive->company_id = Session::get('company_id');
//                 $shareholder_incentive->created_by = auth()->user()->id;
//                 $shareholder_incentive->status = 'pending';
//                 $shareholder_incentive->save();
//             }
//         }

//         // Outsiders (entries based on outsiders count)
//         foreach ($outsiders as $index => $outsiderId) {
//             if ($payment || $initial_payment) {
//                 $paymentAmount = $initial_payment->amount ?? $payment->amount ?? 0;
//                 $outsiderIncentiveAmount = (floatval($request->input('outsiders_incentive')[$index]) / 100) * $paymentAmount;

//                 $outsider_incentive = new SalesIncentive();
//                 $outsider_incentive->land_sale_id = $land_sale->id;
//                 $outsider_incentive->director_id = $director;
//                 $outsider_incentive->outsider_id = $outsiderId;
//                 $outsider_incentive->outsiders_incentive = $request->input('outsiders_incentive')[$index];
//                 $outsider_incentive->outsiders_incentive_amount = $outsiderIncentiveAmount;
//                 $outsider_incentive->outsider_per_sales_incentive = $outsiderIncentiveAmount;
//                 $outsider_incentive->company_id = Session::get('company_id');
//                 $outsider_incentive->created_by = auth()->user()->id;
//                 $outsider_incentive->status = 'pending';
//                 $outsider_incentive->save();
//             }
//         }

//         // বাকি কোড একই থাকবে
//         $data = SalesIncentive::where('land_sale_id', $land_sale->id)->get();

//         $directorData = $data->map(function ($item) {
//             return [
//                 'id' => $item->director_id,
//                 'per_sales_incentive' => $item->director_per_sales_incentive,
//             ];
//         })->unique('id')->values();

//         $coordinatorData = $data->map(function ($item) {
//             return [
//                 'id' => $item->coordinator_id,
//                 'per_sales_incentive' => $item->coordinator_per_sales_incentive,
//             ];
//         })->unique('id')->values();

//         $shareholderData = $data->map(function ($item) {
//             return [
//                 'id' => $item->shareholder_id,
//                 'per_sales_incentive' => $item->shareholder_per_sales_incentive,
//             ];
//         })->unique('id')->values();

//         $outsiderData = $data->map(function ($item) {
//             return [
//                 'id' => $item->outsider_id,
//                 'per_sales_incentive' => $item->outsider_per_sales_incentive,
//             ];
//         })->unique('id')->values();

//         $allData = [
//             ['role' => 'director', 'data' => $directorData],
//             ['role' => 'coordinator', 'data' => $coordinatorData],
//             ['role' => 'shareholder', 'data' => $shareholderData],
//             ['role' => 'outsider', 'data' => $outsiderData],
//         ];


//         // ... rest of incentives processing code (same as before)

//         DB::commit();
//         $msg = "Land Sale application data Inserted.";
//         return view('sales.voucher', compact('land_sale', 'payment', 'initial_payment'))->with('status', $msg);
//     } catch (\Exception $e) {
//         DB::rollBack();
//         dd($e->getMessage());
//     }
// }

// private function saveCustomer($request, $land_sale_id, $index = false)
// {
//     $customer = new Customer();
//     $customer->company_id = Session::get('company_id');
//     $customer->project_id = $request->project_id;
//     $customer->land_sale_id = $land_sale_id;

//     if($index === false) {
//         // Main customer
//         $customer->customer_code = $request->customer_code;
//         $customer->customer_name = $request->customer_name;
//         $customer->father_name = $request->father_name;
//         $customer->mother_name = $request->mother_name;
//         $customer->spouse_name = $request->spouse_name;
//         $customer->present_mailing_address = $request->present_mailing_address;
//         $customer->permanent_address = $request->permanent_address;
//         $customer->mobile_no = $request->mobile_no;
//         $customer->email = $request->email;
//         $customer->nationality = $request->nationality;
//         $customer->national_id = $request->national_id;
//         $customer->passport_no = $request->passport_no;
//         $customer->tin_no = $request->tin_no;
//         $customer->office_name = $request->office_name;
//         $customer->office_address = $request->office_address;
//         $customer->designation = $request->designation;
//         $customer->customer_cell = $request->customer_cell;

//         if ($request->type == "Flat") {
//             $customer->customer_name_bangla = $request->flat_customer_name_bangla;
//             $customer->blood_group = $request->blood_group;
//             $customer->date_of_birth = $request->date_of_birth;
//             $customer->inheritor = $request->inheritor;
//             $customer->inheritor_relation = $request->inheritor_relation;
//             $customer->portion_of_share = $request->portion_of_share;
//         } else {
//             $customer->customer_name_bangla = $request->customer_name_bangla;
//             $customer->father_name_bangla = $request->father_name_bangla;
//             $customer->mother_name_bangla = $request->mother_name_bangla;
//             $customer->spouse_name_bangla = $request->spouse_name_bangla;
//             $customer->present_mailing_address_bangla = $request->present_mailing_address_bangla;
//             $customer->permanent_address_bangla = $request->permanent_address_bangla;
//             $customer->facebook_id = $request->facebook_id;
//             $customer->religion = $request->religion;
//             $customer->date_of_birth = $request->date_of_birth_plot;
//             $customer->nominee_name = $request->nominee_name;
//             $customer->nominee_address = $request->nominee_address;
//             $customer->nominee_cell = $request->nominee_cell;
//             $customer->relation = $request->relation;
//             $customer->nominee_date_of_birth = $request->nominee_date_of_birth;
//         }

//         // File uploads for main customer
//         if ($request->customer_photo != null) {
//             $newImageName = time() . '_customer.' . $request->customer_photo->extension();
//             $request->customer_photo->move(public_path('upload_images/customer_photo'), $newImageName);
//             $customer->customer_photo = $newImageName;
//         }
//         if ($request->applicant_signature != null) {
//             $newSignature = 'applicant_signature.' . $request->applicant_signature->extension();
//             $request->applicant_signature->move(public_path('upload_images/applicant_signature'), $newSignature);
//             $customer->applicant_signature = $newSignature;
//         }
//         if ($request->nominee_photo != null) {
//             $newNidName = 'nominee.' . $request->nominee_photo->extension();
//             $request->nominee_photo->move(public_path('upload_images/nominee_photo'), $newNidName);
//             $customer->nominee_photo = $newNidName;
//         }
//     } else {
//         // Additional customer
//         $customer->customer_code = $request->customer_code; // Same as main customer
//         $customer->customer_name = $request->{'customer_name_'.$index};
//         $customer->father_name = $request->{'father_name_'.$index};
//         $customer->mother_name = $request->{'mother_name_'.$index};
//         $customer->spouse_name = $request->{'spouse_name_'.$index};
//         $customer->present_mailing_address = $request->{'present_mailing_address_'.$index};
//         $customer->permanent_address = $request->{'permanent_address_'.$index};
//         $customer->mobile_no = $request->{'mobile_no_'.$index};
//         $customer->email = $request->{'email_'.$index};
//         $customer->nationality = $request->{'nationality_'.$index};
//         $customer->national_id = $request->{'national_id_'.$index};
//         $customer->passport_no = $request->{'passport_no_'.$index};
//         $customer->tin_no = $request->{'tin_no_'.$index};
//         $customer->office_name = $request->{'office_name_'.$index};
//         $customer->office_address = $request->{'office_address_'.$index};
//         $customer->designation = $request->{'designation_'.$index};
//         $customer->customer_cell = $request->{'customer_cell_'.$index};

//         if ($request->type == "Flat") {
//             $customer->customer_name_bangla = $request->{'flat_customer_name_bangla_'.$index};
//             $customer->blood_group = $request->{'blood_group_'.$index};
//             $customer->date_of_birth = $request->{'date_of_birth_'.$index};
//             $customer->inheritor = $request->{'inheritor_'.$index};
//             $customer->inheritor_relation = $request->{'inheritor_relation_'.$index};
//             $customer->portion_of_share = $request->{'portion_of_share_'.$index};
//         } else {
//             $customer->customer_name_bangla = $request->{'customer_name_bangla_'.$index};
//             $customer->father_name_bangla = $request->{'father_name_bangla_'.$index};
//             $customer->mother_name_bangla = $request->{'mother_name_bangla_'.$index};
//             $customer->spouse_name_bangla = $request->{'spouse_name_bangla_'.$index};
//             $customer->present_mailing_address_bangla = $request->{'present_mailing_address_bangla_'.$index};
//             $customer->permanent_address_bangla = $request->{'permanent_address_bangla_'.$index};
//             $customer->facebook_id = $request->{'facebook_id_'.$index};
//             $customer->religion = $request->{'religion_'.$index};
//             $customer->date_of_birth = $request->{'date_of_birth_plot_'.$index};
//             $customer->nominee_name = $request->{'nominee_name_'.$index};
//             $customer->nominee_address = $request->{'nominee_address_'.$index};
//             $customer->nominee_cell = $request->{'nominee_cell_'.$index};
//             $customer->relation = $request->{'relation_'.$index};
//             $customer->nominee_date_of_birth = $request->{'nominee_date_of_birth_'.$index};
//         }

//         // File uploads for additional customers
//         if ($request->hasFile('customer_photo_'.$index)) {
//             $newImageName = time() . '_customer_'.$index.'.' . $request->file('customer_photo_'.$index)->extension();
//             $request->file('customer_photo_'.$index)->move(public_path('upload_images/customer_photo'), $newImageName);
//             $customer->customer_photo = $newImageName;
//         }
//         if ($request->hasFile('applicant_signature_'.$index)) {
//             $newSignature = 'applicant_signature_'.$index.'.' . $request->file('applicant_signature_'.$index)->extension();
//             $request->file('applicant_signature_'.$index)->move(public_path('upload_images/applicant_signature'), $newSignature);
//             $customer->applicant_signature = $newSignature;
//         }
//         if ($request->hasFile('nominee_photo_'.$index)) {
//             $newNidName = 'nominee_'.$index.'.' . $request->file('nominee_photo_'.$index)->extension();
//             $request->file('nominee_photo_'.$index)->move(public_path('upload_images/nominee_photo'), $newNidName);
//             $customer->nominee_photo = $newNidName;
//         }
//     }

//     $customer->password = bcrypt('12345');
//     $customer->created_by = auth()->user()->id;
//     $customer->save();

//     return $customer;
// }


public function save_application_form(Request $request)
{
    // dd($request->all());

    DB::beginTransaction();
    try {
        // Safely get array inputs with null checks
        $coordinatorIds = $request->input('coordinator_id', []);
        $coordinatorIncentives = $request->input('coordinators_incentive', []);
        $coordinatorIncentiveAmounts = $request->input('coordinators_incentive_amount', []);

        $shareholderIds = $request->input('shareholder_id', []);
        $shareholderIncentives = $request->input('shareholders_incentive', []);
        $shareholderIncentiveAmounts = $request->input('shareholders_incentive_amount', []);

        $outsiderIds = $request->input('outsider_id', []);
        $outsiderIncentives = $request->input('outsiders_incentive', []);
        $outsiderIncentiveAmounts = $request->input('outsiders_incentive_amount', []);

        $project_id = $request->project_id;
        $project = Project::where('id', $project_id)->where('company_id', Session::get('company_id'))->select('name')->first();

        if (!$project) {
            throw new \Exception("Project not found");
        }

        $project_name = $project->name;
        $lastLandSale = LandSale::latest()->first();

        $lastNumber = ($lastLandSale) ? $lastLandSale->id : 0;
        $prefix = strtoupper(substr($project_name, 0, 5));
        $nextCustomerId = $lastNumber + 1;
        $invoiceNumber = $prefix . '-' . $nextCustomerId;

        $lastCode = ($lastLandSale) ? $lastLandSale->id : 0;
        $prefix = "SALE";
        $nextLandSaleCode = $lastCode + 1;
        $landSaleNumber = $prefix . '-' . $nextLandSaleCode;

        // Create Land Sale
        $land_sale = new LandSale();
        $land_sale->project_id = $request->project_id;
        $land_sale->company_id = Session::get('company_id');
        $land_sale->type = $request->type;

        // Price setting based on type
        if ($request->type == "Flat") {
            $flatIds = $request->input('flat_id', []);
            $land_sale->flat_total_price = $request->flat_total_price;
        } elseif ($request->type == "Land") {
            $landIds = $request->input('land_id', []);
            $land_sale->land_total_price = $request->land_total_price;
        } else {
            $plotIds = $request->input('plot_id', []);
            $land_sale->rate_per_katha = $request->rate_per_katha;
            $land_sale->rate_per_katha_words = $request->rate_per_katha_words;
            $land_sale->total_price = $request->total_price;
            $land_sale->total_amount_in_words = $request->total_amount_in_words;
        }

        $land_sale->application_date = $request->application_date;
        $land_sale->invoice_no = $invoiceNumber;
        $land_sale->land_sale_code = $landSaleNumber;

        if ($request->paymentOption == "notMade") {
            $land_sale->booking_money = $request->installment_booking_money ?? 0;
            $land_sale->down_payment = $request->installment_down_payment ?? 0;
            $land_sale->down_payment_word = $request->installment_down_payment_word ?? '';
            $land_sale->booking_date = $request->installment_booking_date;
            $land_sale->initial_payment_made_date = $request->installment_initial_payment_made_date;
            $land_sale->remaining_amount = $request->installment_remaining_amount ?? 0;
        } else {
            $land_sale->booking_money = $request->booking_money ?? 0;
            $land_sale->down_payment = $request->down_payment ?? 0;
            $land_sale->down_payment_word = $request->down_payment_word ?? '';
            $land_sale->booking_date = $request->booking_date;
            $land_sale->initial_payment_made_date = $request->initial_payment_made_date;
            $land_sale->remaining_amount = $request->remaining_amount ?? 0;
        }

        $land_sale->payment_option = $request->paymentOption;
        $land_sale->director_id = $request->director_id;
        $land_sale->directors_incentive = $request->directors_incentive ?? 0;
        $land_sale->directors_incentive_amount = $request->directors_incentive_amount ?? 0;
        $land_sale->directors_left_amount = $request->directors_left_amount ?? 0;
        $land_sale->created_by = auth()->user()->id;
        $land_sale->save();

        // Save main customer
        $main_customer = $this->saveCustomer($request, $land_sale->id, false);
        $land_sale->customer_id = $main_customer->id;
        $land_sale->save();

        // Save additional customers
        $i = 1;
        while($request->has('customer_name_'.$i)) {
            $this->saveCustomer($request, $land_sale->id, $i);
            $i++;
        }

        // Create flat, plot or land sale details
        if ($request->type == "Flat" && !empty($flatIds)) {
            foreach ($flatIds as $flatId) {
                $flatSaleDetail = new FlatSaleDetail();
                $flatSaleDetail->land_sale_id = $land_sale->id;
                $flatSaleDetail->flat_id = $flatId;
                $flatSaleDetail->company_id = Session::get('company_id');
                $flatSaleDetail->save();

                $flat = Flat::find($flatId);
                if ($flat) {
                    $flat->save();
                }
            }
        } elseif ($request->type == "Plot" && !empty($plotIds)) {
            foreach ($plotIds as $plotId) {
                $plotSaleDetail = new PlotSaleDetail();
                $plotSaleDetail->land_sale_id = $land_sale->id;
                $plotSaleDetail->plot_id = $plotId;
                $plotSaleDetail->company_id = Session::get('company_id');
                $plotSaleDetail->save();

                $plot = Plot::find($plotId);
                if ($plot) {
                    $plot->save();
                }
            }
        } elseif ($request->type == "Land" && !empty($landIds)) {
            foreach ($landIds as $landId) {
                $landSaleDetail = new LandSaleDetail();
                $landSaleDetail->land_sale_id = $land_sale->id;
                $landSaleDetail->landshare_id = $landId;
                $landSaleDetail->company_id = Session::get('company_id');
                $landSaleDetail->save();

                $landShare = Landshare::find($landId);
                if ($landShare) {
                    $landShare->status = 'sold';
                    $landShare->save();
                }
            }
        }

        // Payment processing
        $payment = null;
        $initial_payment = null;

        $lastLandSalePayment = LandPayment::latest()->first();
        $lastNumber = ($lastLandSalePayment) ? $lastLandSalePayment->id : 0;
        $prefix = strtoupper(substr($project_name, 0, 5));
        $voucherPaymentNumber = $prefix . '-' . ($lastNumber + 1);

        if ($land_sale->payment_option == "notMade") {
            $installment = new Installment();
            $installment->land_sale_id = $land_sale->id;
            $installment->type = $request->type;
            $installment->monthly_installment = $request->monthly_installment ?? 0;
            $installment->total_installment_number = $request->total_installment_number ?? 0;
            $installment->initial_payment = ($request->installment_down_payment ?? 0) + ($request->installment_booking_money ?? 0);
            $installment->company_id = Session::get('company_id');
            $installment->created_by = auth()->user()->id;
            $installment->save();

            $payment = new LandPayment();
            $payment->land_sale_id = $land_sale->id;
            $payment->voucher_no = $voucherPaymentNumber;
            $payment->project_id = $request->project_id;
            $payment->remarks = $request->note_installment ?? '';
            $payment->payment_type_id = $request->installment_payment_type_id;
            $payment->booking_cheque_no = $request->installment_booking_cheque_no ?? '';
            $payment->down_payment_cheque_no = $request->installment_down_payment_cheque_no ?? '';
            $payment->fund_id = $request->installment_fund_id;
            $payment->bank_id = $request->installment_bank_id;
            $payment->account_id = $request->installment_account_id;
            $payment->amount = ($request->installment_down_payment ?? 0) + ($request->installment_booking_money ?? 0);
            $payment->pay_date = $request->installment_initial_payment_made_date;
            $payment->payment_option = $request->paymentOption;
            $payment->company_id = Session::get('company_id');
            $payment->save();
        } else {
            $initial_payment = new LandPayment();
            $initial_payment->land_sale_id = $land_sale->id;
            $initial_payment->project_id = $request->project_id;
            $initial_payment->voucher_no = $voucherPaymentNumber;
            $initial_payment->remarks = $request->note ?? '';
            $initial_payment->payment_type_id = $request->payment_type_id;
            $initial_payment->booking_cheque_no = $request->booking_cheque_no ?? '';
            $initial_payment->down_payment_cheque_no = $request->down_payment_cheque_no ?? '';
            $initial_payment->fund_id = $request->fund_id;
            $initial_payment->bank_id = $request->bank_id;
            $initial_payment->account_id = $request->account_id;
            $initial_payment->amount = ($land_sale->down_payment ?? 0) + ($land_sale->booking_money ?? 0);
            $initial_payment->pay_date = $request->initial_payment_made_date;
            $initial_payment->payment_option = $request->paymentOption;
            $initial_payment->company_id = Session::get('company_id');
            $initial_payment->save();
        }

        // Process incentives - Modified as per your requirements
        $isFlat = $request->type == "Flat";
        $isPlot = $request->type == "Plot";
        $paymentAmount = $initial_payment->amount ?? $payment->amount ?? 0;
        $director = $request->input('director_id');

        // Director incentive
        if ($director && ($payment || $initial_payment)) {
            $directorIncentiveAmount = ($isFlat || $request->type == "Land") ?
                floatval($request->input('directors_incentive', 0)) :
                (floatval($request->input('directors_incentive', 0)) / 100) * $paymentAmount;

            $director_incentive = new SalesIncentive();
            $director_incentive->land_sale_id = $land_sale->id;
            $director_incentive->director_id = $director;
            $director_incentive->directors_incentive = $request->input('directors_incentive', 0);
            $director_incentive->directors_incentive_amount = $directorIncentiveAmount;
            $director_incentive->directors_left_amount = $directorIncentiveAmount;
            $director_incentive->director_per_sales_incentive = $directorIncentiveAmount;
            $director_incentive->company_id = Session::get('company_id');
            $director_incentive->created_by = auth()->user()->id;
            $director_incentive->status = 'pending';
            $director_incentive->save();
        }

        // Coordinators incentives - with null checks
        if (!empty($coordinatorIds)) {
            foreach ($coordinatorIds as $index => $coordinatorId) {
                if (($payment || $initial_payment) && isset($coordinatorIncentives[$index])) {
                    $coordinatorIncentiveAmount = ($isFlat || $request->type == "Land") ?
                        floatval($coordinatorIncentives[$index] ?? 0) :
                        (floatval($coordinatorIncentives[$index] ?? 0) / 100) * $paymentAmount;

                    $coordinator_incentive = new SalesIncentive();
                    $coordinator_incentive->land_sale_id = $land_sale->id;
                    $coordinator_incentive->director_id = $director;
                    $coordinator_incentive->coordinator_id = $coordinatorId;
                    $coordinator_incentive->coordinators_incentive = $coordinatorIncentives[$index] ?? 0;
                    $coordinator_incentive->coordinators_incentive_amount = $coordinatorIncentiveAmount;
                    $coordinator_incentive->coordinator_per_sales_incentive = $coordinatorIncentiveAmount;
                    $coordinator_incentive->company_id = Session::get('company_id');
                    $coordinator_incentive->created_by = auth()->user()->id;
                    $coordinator_incentive->status = 'pending';
                    $coordinator_incentive->save();
                }
            }
        }

        // Shareholders incentives - with null checks
        if (!empty($shareholderIds)) {
            foreach ($shareholderIds as $index => $shareholderId) {
                if (($payment || $initial_payment) && isset($shareholderIncentives[$index])) {
                    $shareholderIncentiveAmount = ($isFlat || $request->type == "Land") ?
                        floatval($shareholderIncentives[$index] ?? 0) :
                        (floatval($shareholderIncentives[$index] ?? 0) / 100) * $paymentAmount;

                    $shareholder_incentive = new SalesIncentive();
                    $shareholder_incentive->land_sale_id = $land_sale->id;
                    $shareholder_incentive->director_id = $director;
                    $shareholder_incentive->shareholder_id = $shareholderId;
                    $shareholder_incentive->shareholders_incentive = $shareholderIncentives[$index] ?? 0;
                    $shareholder_incentive->shareholders_incentive_amount = $shareholderIncentiveAmount;
                    $shareholder_incentive->shareholder_per_sales_incentive = $shareholderIncentiveAmount;
                    $shareholder_incentive->company_id = Session::get('company_id');
                    $shareholder_incentive->created_by = auth()->user()->id;
                    $shareholder_incentive->status = 'pending';
                    $shareholder_incentive->save();
                }
            }
        }

        // Outsiders incentives - with null checks
        if (!empty($outsiderIds)) {
            foreach ($outsiderIds as $index => $outsiderId) {
                if (($payment || $initial_payment) && isset($outsiderIncentives[$index])) {
                    $outsiderIncentiveAmount = ($isFlat || $request->type == "Land") ?
                        floatval($outsiderIncentives[$index] ?? 0) :
                        (floatval($outsiderIncentives[$index] ?? 0) / 100) * $paymentAmount;

                    $outsider_incentive = new SalesIncentive();
                    $outsider_incentive->land_sale_id = $land_sale->id;
                    $outsider_incentive->director_id = $director;
                    $outsider_incentive->outsider_id = $outsiderId;
                    $outsider_incentive->outsiders_incentive = $outsiderIncentives[$index] ?? 0;
                    $outsider_incentive->outsiders_incentive_amount = $outsiderIncentiveAmount;
                    $outsider_incentive->outsider_per_sales_incentive = $outsiderIncentiveAmount;
                    $outsider_incentive->company_id = Session::get('company_id');
                    $outsider_incentive->created_by = auth()->user()->id;
                    $outsider_incentive->status = 'pending';
                    $outsider_incentive->save();
                }
            }
        }

        // Get all incentives for voucher
        $data = SalesIncentive::where('land_sale_id', $land_sale->id)->get();

        $directorData = $data->whereNotNull('director_id')->map(function ($item) {
            return [
                'id' => $item->director_id,
                'per_sales_incentive' => $item->director_per_sales_incentive,
            ];
        })->unique('id')->values()->all();

        $coordinatorData = $data->whereNotNull('coordinator_id')->map(function ($item) {
            return [
                'id' => $item->coordinator_id,
                'per_sales_incentive' => $item->coordinator_per_sales_incentive,
            ];
        })->unique('id')->values()->all();

        $shareholderData = $data->whereNotNull('shareholder_id')->map(function ($item) {
            return [
                'id' => $item->shareholder_id,
                'per_sales_incentive' => $item->shareholder_per_sales_incentive,
            ];
        })->unique('id')->values()->all();

        $outsiderData = $data->whereNotNull('outsider_id')->map(function ($item) {
            return [
                'id' => $item->outsider_id,
                'per_sales_incentive' => $item->outsider_per_sales_incentive,
            ];
        })->unique('id')->values()->all();

        $allData = [
            ['role' => 'director', 'data' => $directorData],
            ['role' => 'coordinator', 'data' => $coordinatorData],
            ['role' => 'shareholder', 'data' => $shareholderData],
            ['role' => 'outsider', 'data' => $outsiderData],
        ];

        DB::commit();
        $msg = "Land Sale application data Inserted.";
        return view('sales.voucher', compact('land_sale', 'payment', 'initial_payment', 'allData'))->with('status', $msg);
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withError($e->getMessage())->withInput();
    }
}

private function saveCustomer($request, $land_sale_id, $index = false)
{
    // dd($request->all(), $land_sale_id, $index);

    $customer = new Customer();
    $customer->company_id = Session::get('company_id');
    $customer->project_id = $request->project_id;
    $customer->land_sale_id = $land_sale_id;

    if($index === false) {
        // মূল কাস্টমার - সব টাইপের জন্য কমন ফিল্ড
        $customer->customer_code = $request->customer_code;
        $customer->customer_name = $request->customer_name;
        $customer->father_name = $request->father_name;
        $customer->mother_name = $request->mother_name;
        $customer->spouse_name = $request->spouse_name;
        $customer->present_mailing_address = $request->present_mailing_address;
        $customer->permanent_address = $request->permanent_address;
        $customer->mobile_no = $request->mobile_no;
        $customer->email = $request->email;
        $customer->nationality = $request->nationality;
        $customer->national_id = $request->national_id;
        $customer->passport_no = $request->passport_no;
        $customer->tin_no = $request->tin_no;
        $customer->office_name = $request->office_name;
        $customer->office_address = $request->office_address;
        $customer->designation = $request->designation;
        $customer->customer_cell = $request->customer_cell;

        // টাইপ স্পেসিফিক ফিল্ড
        if ($request->type == "Flat") {
            // ফ্ল্যাটের জন্য বিশেষ ফিল্ড
            $customer->customer_name_bangla = $request->flat_customer_name_bangla;
            $customer->blood_group = $request->blood_group;
            $customer->date_of_birth = $request->date_of_birth;
            $customer->inheritor = $request->inheritor;
            $customer->inheritor_relation = $request->inheritor_relation;
            $customer->portion_of_share = $request->portion_of_share;
        }
        elseif ($request->type == "Plot") {
            // প্লটের জন্য বিশেষ ফিল্ড
            $customer->customer_name_bangla = $request->customer_name_bangla;
            $customer->father_name_bangla = $request->father_name_bangla;
            $customer->mother_name_bangla = $request->mother_name_bangla;
            $customer->spouse_name_bangla = $request->spouse_name_bangla;
            $customer->present_mailing_address_bangla = $request->present_mailing_address_bangla;
            $customer->permanent_address_bangla = $request->permanent_address_bangla;
            $customer->facebook_id = $request->facebook_id;
            $customer->religion = $request->religion;
            $customer->date_of_birth = $request->date_of_birth_plot;
            $customer->nominee_name = $request->nominee_name;
            $customer->nominee_address = $request->nominee_address;
            $customer->nominee_cell = $request->nominee_cell;
            $customer->relation = $request->relation;
            $customer->nominee_date_of_birth = $request->nominee_date_of_birth;
        }
        elseif ($request->type == "Land") {
            // ল্যান্ডের জন্য বিশেষ ফিল্ড
            $customer->customer_name_bangla = $request->flat_customer_name_bangla;
            $customer->blood_group = $request->blood_group;
            $customer->date_of_birth = $request->date_of_birth;
            $customer->inheritor = $request->inheritor;
            $customer->inheritor_relation = $request->inheritor_relation;
            $customer->portion_of_share = $request->portion_of_share;

        }

        // ফাইল আপলোড হ্যান্ডেলিং
        if ($request->customer_photo != null) {
            $newImageName = time() . '_customer.' . $request->customer_photo->extension();
            $request->customer_photo->move(public_path('upload_images/customer_photo'), $newImageName);
            $customer->customer_photo = $newImageName;
        }
        if ($request->applicant_signature != null) {
            $newSignature = 'applicant_signature.' . $request->applicant_signature->extension();
            $request->applicant_signature->move(public_path('upload_images/applicant_signature'), $newSignature);
            $customer->applicant_signature = $newSignature;
        }
        if ($request->nominee_photo != null && $request->type == "Plot") {
            $newNidName = 'nominee.' . $request->nominee_photo->extension();
            $request->nominee_photo->move(public_path('upload_images/nominee_photo'), $newNidName);
            $customer->nominee_photo = $newNidName;
        }
    } else {
        // অতিরিক্ত কাস্টমার - উপরের মতোই কিন্তু ইনডেক্স সহ
        $customer->customer_code = $request->customer_code;
        $customer->customer_name = $request->{'customer_name_'.$index};
        $customer->father_name = $request->{'father_name_'.$index};
        $customer->mother_name = $request->{'mother_name_'.$index};
        $customer->spouse_name = $request->{'spouse_name_'.$index};
        $customer->present_mailing_address = $request->{'present_mailing_address_'.$index};
        $customer->permanent_address = $request->{'permanent_address_'.$index};
        $customer->mobile_no = $request->{'mobile_no_'.$index};
        $customer->email = $request->{'email_'.$index};
        $customer->nationality = $request->{'nationality_'.$index};
        $customer->national_id = $request->{'national_id_'.$index};
        $customer->passport_no = $request->{'passport_no_'.$index};
        $customer->tin_no = $request->{'tin_no_'.$index};
        $customer->office_name = $request->{'office_name_'.$index};
        $customer->office_address = $request->{'office_address_'.$index};
        $customer->designation = $request->{'designation_'.$index};
        $customer->customer_cell = $request->{'customer_cell_'.$index};

        if ($request->type == "Flat") {
            $customer->customer_name_bangla = $request->{'flat_customer_name_bangla_'.$index};
            $customer->blood_group = $request->{'blood_group_'.$index};
            $customer->date_of_birth = $request->{'date_of_birth_'.$index};
            $customer->inheritor = $request->{'inheritor_'.$index};
            $customer->inheritor_relation = $request->{'inheritor_relation_'.$index};
            $customer->portion_of_share = $request->{'portion_of_share_'.$index};
        }
        elseif ($request->type == "Plot") {
            $customer->customer_name_bangla = $request->{'customer_name_bangla_'.$index};
            $customer->father_name_bangla = $request->{'father_name_bangla_'.$index};
            $customer->mother_name_bangla = $request->{'mother_name_bangla_'.$index};
            $customer->spouse_name_bangla = $request->{'spouse_name_bangla_'.$index};
            $customer->present_mailing_address_bangla = $request->{'present_mailing_address_bangla_'.$index};
            $customer->permanent_address_bangla = $request->{'permanent_address_bangla_'.$index};
            $customer->facebook_id = $request->{'facebook_id_'.$index};
            $customer->religion = $request->{'religion_'.$index};
            $customer->date_of_birth = $request->{'date_of_birth_plot_'.$index};
            $customer->nominee_name = $request->{'nominee_name_'.$index};
            $customer->nominee_address = $request->{'nominee_address_'.$index};
            $customer->nominee_cell = $request->{'nominee_cell_'.$index};
            $customer->relation = $request->{'relation_'.$index};
            $customer->nominee_date_of_birth = $request->{'nominee_date_of_birth_'.$index};
        }
        elseif ($request->type == "Land") {
            $customer->customer_name_bangla = $request->{'flat_customer_name_bangla_'.$index};
            $customer->blood_group = $request->{'blood_group_'.$index};
            $customer->date_of_birth = $request->{'date_of_birth_'.$index};
            $customer->inheritor = $request->{'inheritor_'.$index};
            $customer->inheritor_relation = $request->{'inheritor_relation_'.$index};
            $customer->portion_of_share = $request->{'portion_of_share_'.$index};

        }

        // ফাইল আপলোড হ্যান্ডেলিং (অতিরিক্ত কাস্টমার)
        if ($request->hasFile('customer_photo_'.$index)) {
            $newImageName = time() . '_customer_'.$index.'.' . $request->file('customer_photo_'.$index)->extension();
            $request->file('customer_photo_'.$index)->move(public_path('upload_images/customer_photo'), $newImageName);
            $customer->customer_photo = $newImageName;
        }
        if ($request->hasFile('applicant_signature_'.$index)) {
            $newSignature = 'applicant_signature_'.$index.'.' . $request->file('applicant_signature_'.$index)->extension();
            $request->file('applicant_signature_'.$index)->move(public_path('upload_images/applicant_signature'), $newSignature);
            $customer->applicant_signature = $newSignature;
        }
        if ($request->hasFile('nominee_photo_'.$index) && $request->type == "Plot") {
            $newNidName = 'nominee_'.$index.'.' . $request->file('nominee_photo_'.$index)->extension();
            $request->file('nominee_photo_'.$index)->move(public_path('upload_images/nominee_photo'), $newNidName);
            $customer->nominee_photo = $newNidName;
        }
    }

    $customer->password = bcrypt('12345');
    $customer->created_by = auth()->user()->id;
    $customer->save();

    return $customer;
}




    public function approveIncentives(Request $request, $land_sale_id)
{
    DB::beginTransaction();
    try {
        $salesIncentives = SalesIncentive::where('land_sale_id', $land_sale_id)
                                        ->where('status', 'pending')
                                        ->get();

        foreach ($salesIncentives as $incentive) {
            // Update status to approved
            $incentive->status = 'approved';
            $incentive->save();

            // Now update the IncentiveStockPerSale table
            $roles = ['director', 'coordinator', 'shareholder', 'outsider'];

            foreach ($roles as $role) {
                $idField = $role.'_id';
                $amountField = $role.'_per_sales_incentive';

                if ($incentive->$idField && $incentive->$amountField) {
                    $landSaleEmployee = LandSaleEmployee::where('id', $incentive->$idField)->first();

                    if ($landSaleEmployee) {
                        $existingEntry = IncentiveStockPerSale::where('land_sale_employee_id', $landSaleEmployee->id)->first();

                        if ($existingEntry) {
                            $existingEntry->update([
                                'incentive_amount' => $existingEntry->incentive_amount + $incentive->$amountField,
                                'left_amount' => $existingEntry->left_amount + $incentive->$amountField,
                            ]);
                        } else {
                            IncentiveStockPerSale::create([
                                'head_id' => $landSaleEmployee->head_id,
                                'land_sale_employee_id' => $landSaleEmployee->id,
                                'incentive_amount' => $incentive->$amountField,
                                'left_amount' => $incentive->$amountField,
                                'company_id' => Session::get('company_id'),
                                'created_by' => auth()->user()->id,
                            ]);
                        }
                    }
                }
            }
        }

        DB::commit();
        return redirect()->back()->with('success', 'Incentives approved successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error approving incentives: '.$e->getMessage());
    }
}


// IncentiveStockPerSale টেবিল থেকে ডাটা ডিলিট করার অংশ বাদ পড়ে গেছে
public function deleteApplicationForm($id)
{
    DB::beginTransaction();
    try {
        // প্রথমে LandSale ডাটা খুঁজে বের করি
        $landSale = LandSale::findOrFail($id);

        // SalesIncentive ডাটাগুলো সংগ্রহ করি (ডিলিট করার আগে)
        $salesIncentives = SalesIncentive::where('land_sale_id', $id)->get();

        // SalesIncentive ডিলিট
        SalesIncentive::where('land_sale_id', $id)->delete();

        // IncentiveStockPerSale থেকে সংশ্লিষ্ট ডাটা আপডেট/ডিলিট
        foreach($salesIncentives as $incentive) {
            $roles = ['director', 'coordinator', 'shareholder', 'outsider'];

            foreach($roles as $role) {
                $idField = $role.'_id';
                $amountField = $role.'_per_sales_incentive';

                if($incentive->$idField && $incentive->$amountField) {
                    $stockEntry = IncentiveStockPerSale::where('land_sale_employee_id', $incentive->$idField)
                                                     ->first();

                    if($stockEntry) {
                        // ইনসেন্টিভ পরিমাণ কমিয়ে দেই
                        $stockEntry->incentive_amount -= $incentive->$amountField;
                        $stockEntry->left_amount -= $incentive->$amountField;

                        // যদি পরিমাণ শূন্য বা নেগেটিভ হয়, তাহলে ডিলিট করে দিই
                        if($stockEntry->incentive_amount <= 0) {
                            $stockEntry->delete();
                        } else {
                            $stockEntry->save();
                        }
                    }
                }
            }
        }

        // Installment ডিলিট (যদি থাকে)
        Installment::where('land_sale_id', $id)->delete();

        // LandPayment ডিলিট
        $landPayments = LandPayment::where('land_sale_id', $id)->get();
        foreach($landPayments as $payment) {
            // FundLog থেকে সংশ্লিষ্ট ডাটা ডিলিট
            FundLog::where('fund_id', $payment->fund_id)
                   ->where('transection_date', $payment->pay_date)
                   ->where('amount', $payment->amount)
                   ->delete();

            // FundCurrentBalance আপডেট
            $fundBalance = FundCurrentBalance::where('fund_id', $payment->fund_id)
                                           ->where('company_id', Session::get('company_id'))
                                           ->first();
            if($fundBalance) {
                $fundBalance->amount -= $payment->amount;
                $fundBalance->save();
            }

            // Bank Account আপডেট (যদি থাকে)
            if($payment->account_id) {
                $bankAccount = BankAccount::find($payment->account_id);
                if($bankAccount) {
                    $bankAccount->current_balance -= $payment->amount;
                    $bankAccount->save();
                }
            }
        }

        // সব LandPayment ডিলিট
        LandPayment::where('land_sale_id', $id)->delete();

        // FlatSaleDetail বা PlotSaleDetail ডিলিট এবং সংশ্লিষ্ট ফ্ল্যাট/প্লট স্ট্যাটাস আপডেট
        if ($landSale->type == "Flat") {
            // ফ্ল্যাট সেল ডিটেইল ডিলিট
            $flatSaleDetails = FlatSaleDetail::where('land_sale_id', $id)->get();

            foreach ($flatSaleDetails as $detail) {
                // সংশ্লিষ্ট ফ্ল্যাটের স্ট্যাটাস আপডেট (available এ সেট করা)
                $flat = Flat::find($detail->flat_id);
                if ($flat) {
                    // $flat->status = 'available'; // অথবা আপনার সিস্টেমে যে স্ট্যাটাস ব্যবহার করা হয়
                    $flat->save();
                }
            }

            // ফ্ল্যাট সেল ডিটেইল ডিলিট
            FlatSaleDetail::where('land_sale_id', $id)->delete();
        } else {
            // প্লট সেল ডিটেইল ডিলিট
            $plotSaleDetails = PlotSaleDetail::where('land_sale_id', $id)->get();

            foreach ($plotSaleDetails as $detail) {
                // সংশ্লিষ্ট প্লটের স্ট্যাটাস আপডেট (available এ সেট করা)
                $plot = Plot::find($detail->plot_id);
                if ($plot) {
                    // $plot->status = 'available'; // অথবা আপনার সিস্টেমে যে স্ট্যাটাস ব্যবহার করা হয়
                    $plot->save();
                }
            }

            // প্লট সেল ডিটেইল ডিলিট
            PlotSaleDetail::where('land_sale_id', $id)->delete();
        }

        // Customer ডিলিট (শুধু প্রধান কাস্টমার ডিলিট হয়)
        // Customer::where('id', $landSale->customer_id)->delete();

        // Customer ডিলিট (সমস্ত কাস্টমার ডিলিট হবে যাদের land_sale_id = $id)
        Customer::where('land_sale_id', $id)->delete();

        // শেষে LandSale ডিলিট
        $landSale->delete();

        DB::commit();

        return redirect()->back()->with('success', 'অ্যাপ্লিকেশন ফর্ম সফলভাবে ডিলিট করা হয়েছে');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'ডিলিট করতে সমস্যা হয়েছে: '.$e->getMessage());
    }
}




// Landshare
public function landshareIndex()
{
    $child_menu = 'landshare_index';
    $projects = Project::all();
    $landshares = Landshare::with('project')->latest()->get();

    return view('sales.landshare_index', compact('landshares', 'child_menu', 'projects'));
}

public function landshareCreate()
{
    $child_menu = 'landshare_create';
    $projects = Project::all();
    return view('sales.landshare_create', compact('projects', 'child_menu'));
}

public function landshareStore(Request $request)
{
    $data = $request->except('image');

    if ($request->hasFile('image')) {
        $imageName = time() . '_landshare.' . $request->image->extension();
        $request->image->move(public_path('upload_images/landshare_images'), $imageName);
        $data['image'] = 'upload_images/landshare_images/' . $imageName;
    }

    Landshare::create($data);
    return redirect()->route('landshareindex')->with('success', 'Land Share Created Successfully.');
}

public function landshareShow($id)
{
    $landshare = Landshare::with('project')->findOrFail($id);
    return view('sales.landshare_show', compact('landshare'));
}

public function landshareEdit($id)
{
    $landshare = Landshare::findOrFail($id);
    $projects = Project::all();
    return view('sales.landshare_edit', compact('landshare', 'projects'));
}

public function landshareUpdate(Request $request, $id)
{
    $landshare = Landshare::findOrFail($id);
    $data = $request->except('image');

    if ($request->hasFile('image')) {
        $imageName = time() . '_landshare.' . $request->image->extension();
        $request->image->move(public_path('upload_images/landshare_images'), $imageName);
        $data['image'] = 'upload_images/landshare_images/' . $imageName;
    }

    $landshare->update($data);
    return redirect()->route('landshareindex')->with('success', 'Landshare updated successfully.');
}


public function landshareDestroy($id)
{
    Landshare::destroy($id);
    return redirect()->route('landshareindex')->with('success', 'Deleted successfully');
}


public function getLandData(Request $request)
{
    $land = Landshare::find($request->land_id);

    if ($land) {
        return response()->json([
            'shareqty'   => $land->shareqty,
            'sotangsho'  => $land->sotangsho,
            'size'       => $land->size,
        ]);
    } else {
        return response()->json(null, 404);
    }
}




    //================================ Sales Application Details Edit & Update Function Start ===========================//
    /**
     *
     * Author Name: Sazal Abdullah;
     * Date: 04-06-2025
     *
     */

    public function editSalesDetails($id)
    {
        $data['main_menu']  = 'sales';
        $data['child_menu'] = 'application_form_edit';
        $data['fund_types'] = Fund::all();
        $data['banks']      = Bank::get();
        $data['landSale']  = LandSale::find($id);
        $data['payment_types'] = PaymentType::get();
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();
        $data['plot_data'] = Plot::where(['company_id' => Session::get('company_id')])->with('road', 'sector')->get();
        $data['flat_data'] = Flat::where(['company_id' => Session::get('company_id')])->with('flat_floor')->get();

        // dd($data);

        return view('sales.edit_application_form', $data);
    }


    public function updateSalesDetails(Request $request)
    {
        // dd($request->all());
        $customer = Customer::where('id', $request->customer_id)->first();
        $customer->project_id = $request->project_id;
        $customer->customer_code = $request->customer_code;
        $customer->customer_name = $request->customer_name;
        $customer->father_name = $request->father_name;
        $customer->mother_name = $request->mother_name;
        $customer->spouse_name = $request->spouse_name;
        $customer->present_mailing_address = $request->present_mailing_address;
        $customer->permanent_address = $request->permanent_address;
        $customer->mobile_no = $request->mobile_no;
        $customer->email = $request->email;
        $customer->nationality = $request->nationality;
        $customer->national_id = $request->national_id;
        $customer->passport_no = $request->passport_no;
        $customer->tin_no = $request->tin_no;
        $customer->office_name = $request->office_name;
        $customer->office_address = $request->office_address;
        $customer->designation = $request->designation;
        $customer->customer_cell = $request->customer_cell;
        $customer->created_by = auth()->user()->id;

        if ($request->type == "Flat") {
            $customer->customer_name_bangla = $request->flat_customer_name_bangla;
            $customer->blood_group = $request->blood_group;
            $customer->date_of_birth = $request->date_of_birth;
            $customer->inheritor = $request->inheritor;
            $customer->inheritor_relation = $request->inheritor_relation;
            $customer->portion_of_share = $request->portion_of_share;
        } else {
            $customer->customer_name_bangla = $request->customer_name_bangla;
            $customer->father_name_bangla = $request->father_name_bangla;
            $customer->mother_name_bangla = $request->mother_name_bangla;
            $customer->spouse_name_bangla = $request->spouse_name_bangla;
            $customer->present_mailing_address_bangla = $request->present_mailing_address_bangla;
            $customer->permanent_address_bangla = $request->permanent_address_bangla;
            $customer->facebook_id = $request->facebook_id;
            $customer->religion = $request->religion;
            $customer->date_of_birth = $request->date_of_birth_plot;
            $customer->nominee_name = $request->nominee_name;
            $customer->nominee_address = $request->nominee_address;
            $customer->nominee_cell = $request->nominee_cell;
            $customer->relation = $request->relation;
            $customer->nominee_date_of_birth = $request->nominee_date_of_birth;
        }

        // File uploads
        if ($request->customer_photo != null) {
            if ($customer->customer_photo && file_exists(public_path('upload_images/customer_photo/' . $customer->customer_photo))) {
                unlink(public_path('upload_images/customer_photo/' . $customer->customer_photo));
            }

            $newImageName = time() . '_customer.' . $request->customer_photo->extension();
            $request->customer_photo->move(public_path('upload_images/customer_photo'), $newImageName);
            $customer->customer_photo = $newImageName;
        }
        if ($request->applicant_signature != null) {
            if ($customer->applicant_signature && file_exists(public_path('upload_images/applicant_signature/' . $customer->applicant_signature))) {
                unlink(public_path('upload_images/applicant_signature/' . $customer->applicant_signature));
            }

            $newSignature = 'applicant_signature.' . $request->applicant_signature->extension();
            $request->applicant_signature->move(public_path('upload_images/applicant_signature'), $newSignature);
            $customer->applicant_signature = $newSignature;
        }
        if ($request->nominee_photo != null) {
            if ($customer->nominee_photo && file_exists(public_path('upload_images/nominee_photo/' . $customer->nominee_photo))) {
                unlink(public_path('upload_images/nominee_photo/' . $customer->nominee_photo));
            }

            $newNidName = 'nominee.' . $request->nominee_photo->extension();
            $request->nominee_photo->move(public_path('upload_images/nominee_photo'), $newNidName);
            $customer->nominee_photo = $newNidName;
        }
        $customer->update();
        $msg = 'Details Data Updated Successfully';
        return redirect()->back()->with('status', $msg);
    }


    //================================ Sales Application Details Edit & Update Function End ===========================//


    public function getProjectsByType(Request $request)
{
    $type = $request->type;
    $projects = [];

    if ($type === 'Flat') {
        $projects = Flat::with('flat_floor.project')
            ->get()
            ->pluck('flat_floor.project.name', 'flat_floor.project.id')
            ->unique();
    } elseif ($type === 'Plot') {
        $projects = Plot::with('road.sector.project')
            ->get()
            ->pluck('road.sector.project.name', 'road.sector.project.id')
            ->unique();
    } elseif ($type === 'Land') {
        $projects = Landshare::with('project')
            ->get()
            ->pluck('project.name', 'project.id')
            ->unique();
    }

    return response()->json($projects);
}

    // public function getPlot(Request $request)
    // {
    //     $projectId = $request->project_id;

    //     $plots = Plot::distinct()->whereHas('road.sector', function ($query) use ($projectId) {
    //         $query->where('project_id', $projectId);
    //     })
    //         ->where('company_id', Session::get('company_id'))
    //         ->get();
    //     // dd($plots);
    //     return response()->json($plots);
    // }
    
//         public function getPlot(Request $request)
// {
//     $projectId = $request->project_id;

//     $plots = Plot::with('road', 'sector')
//         ->where('project_id', $projectId)
//         ->where('company_id', Session::get('company_id'))
//         ->get();

//     return response()->json($plots);
// }

        public function getPlot(Request $request)
{
    $projectId = $request->project_id;

    $plots = Plot::with('road', 'sector')
        ->where('project_id', $projectId)
        ->where('company_id', Session::get('company_id'))
        ->get();

    return response()->json($plots);
}

    // public function getFlat(Request $request)
    // {
    //     $projectId = $request->project_id;

    //     $flats = Flat::distinct()->whereHas('flat_floor', function ($query) use ($projectId) {
    //         $query->where('project_id', $projectId);
    //     })
    //         ->where('company_id', Session::get('company_id'))
    //         ->get();
    //     // dd($flats);
    //     return response()->json($flats);
    // }
    
//         public function getFlat(Request $request)
// {
//     $projectId = $request->project_id;

//     $flats = Flat::with('flat_floor') // <-- এই লাইনটি যোগ করুন
//         ->distinct()
//         ->whereHas('flat_floor', function ($query) use ($projectId) {
//             $query->where('project_id', $projectId);
//         })
//         ->where('company_id', Session::get('company_id'))
//         ->get();

//     return response()->json($flats);
// }

        public function getFlat(Request $request)
{
    $projectId = $request->project_id;

    $flats = Flat::with('flat_floor') // <-- এই লাইনটি যোগ করুন
        ->distinct()
        ->whereHas('flat_floor', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })
        ->where('company_id', Session::get('company_id'))
        ->get();

    return response()->json($flats);
}

    // public function getPlotData(Request $request)
    // {
    //     $plotId = $request->plot_id;
    //     $plots = Plot::with('road', 'sector')->where('company_id', Session::get('company_id'))->where('id', $plotId)->first();
    //     // dd($plots);
    //     if ($plots) {
    //         return response()->json($plots);
    //     }
    //     return response()->json([]);
    // }
    
    //         public function getPlotData(Request $request)
    // {
    //     $plotId = $request->plot_id;
    //     $plots = Plot::with(['road', 'sector', 'plotType']) // include plotType
    //                 ->where('company_id', Session::get('company_id'))
    //                 ->where('id', $plotId)
    //                 ->first();

    //     if ($plots) {
    //         return response()->json([
    //             'road' => $plots->road,
    //             'sector' => $plots->sector,
    //             'block_no' => $plots->block_no,
    //             'measurement' => $plots->measurement,
    //             'facing' => $plots->facing,
    //             'plot_type' => $plots->plot_type,
    //             'plot_size' => $plots->plotType
    //                 ? $plots->plotType->plot_type . ' (' . $plots->plotType->percentage . ' শতাংশ)'
    //                 : ''
    //         ]);
    //     }

    //     return response()->json([]);
    // }
    
        public function getPlotData(Request $request)
    {
        $plotId = $request->plot_id;
        $plots = Plot::with(['road', 'sector', 'plotType']) // include plotType
                    ->where('company_id', Session::get('company_id'))
                    ->where('id', $plotId)
                    ->first();

        if ($plots) {
            return response()->json([
                'road' => $plots->road,
                'sector' => $plots->sector,
                'block_no' => $plots->block_no,
                'measurement' => $plots->measurement,
                'facing' => $plots->facing,
                'plot_type' => $plots->plot_type,
                'plot_size' => $plots->plotType
                    ? $plots->plotType->plot_type . ' (' . $plots->plotType->percentage . ' শতাংশ)'
                    : ''
            ]);
        }

        return response()->json([]);
    }

    public function getFlatData(Request $request)
    {
        $flatId = $request->flat_id;
        $flats = Flat::with('flat_floor')->where('company_id', Session::get('company_id'))->where('id', $flatId)->first();
        // dd($flats);
        if ($flats) {
            return response()->json($flats);
        }
        return response()->json([]);
    }

    public function getAccountBranch(Request $request)
    {
        $accountId = $request->account_id;
        $branch = BankAccount::where('company_id', Session::get('company_id'))->where('id', $accountId)->first();
        // dd($plots);
        if ($branch) {
            return response()->json($branch);
        }
        return response()->json([]);
    }


// public function store_land_sale_payment(Request $request)
// {
//     $project_id = LandPayment::with('landSale')->where('land_sale_id', $request->land_sale_id)->first();
//     $project = Project::where('id', $project_id->project_id)->where('company_id', Session::get('company_id'))->select('name')->first();
//     $project_name = $project->name;

//     $lastLandSalePayment = LandPayment::latest()->first();
//     $lastNumber = ($lastLandSalePayment) ? $lastLandSalePayment->id : 0;
//     $prefix = strtoupper(substr($project_name, 0, 5));
//     $nextCustomerId = $lastNumber + 1;
//     $voucherNumber = $prefix . '-' . $nextCustomerId;

//     $model = new LandPayment();
//     $model->voucher_no = $voucherNumber;
//     $model->company_id = Session::get('company_id');
//     $model->land_sale_id = $request->land_sale_id;
//     $model->project_id = $project_id->project_id;
//     $model->remarks = $request->remarks;
//     $model->payment_type_id = $request->payment_type_id;
//     $model->remaining_amount_cheque_no = $request->remaining_amount_cheque_no;
//     $model->pay_date = $request->pay_date;
//     $model->fund_id = $request->fund_id;
//     $model->bank_id = $request->bank_id;
//     $model->account_id = $request->account_id;
//     $model->amount = $request->amount;
//     $model->payment_option = $request->payment_option;
//     $model->created_by = auth()->user()->id;
//     $model->save();

//     if ($model->payment_option == 'notMade') {
//         $installment = Installment::where('land_sale_id', $request->land_sale_id)->where('company_id', Session::get('company_id'))->first();
//         $installment->total_installment_number -= 1;
//         $installment->update();
//     }

//     if ($model->payment_option == 'initial') {
//         $land = LandSale::where('id', $request->land_sale_id)->where('company_id', Session::get('company_id'))->first();
//         $land->remaining_amount_date = $request->pay_date;
//         $land->remaining_amount -= $request->amount;
//         $land->update();
//     }

//     // ✅ নতুন ইনসেনটিভ হিসাব ও সেভ
//     if ($model->amount) {
//         $baseIncentives = SalesIncentive::where('land_sale_id', $model->land_sale_id)
//                                         ->whereNull('payment_id')
//                                         ->get();

//         foreach ($baseIncentives as $incentive) {
//             $newIncentive = new SalesIncentive();
//             $newIncentive->land_sale_id = $incentive->land_sale_id;
//             $newIncentive->payment_id = $model->id;
//             $newIncentive->director_id = $incentive->director_id;
//             $newIncentive->coordinator_id = $incentive->coordinator_id;
//             $newIncentive->shareholder_id = $incentive->shareholder_id;
//             $newIncentive->outsider_id = $incentive->outsider_id;

//             $newIncentive->directors_incentive = $incentive->directors_incentive;
//             $newIncentive->coordinators_incentive = $incentive->coordinators_incentive;
//             $newIncentive->shareholders_incentive = $incentive->shareholders_incentive;
//             $newIncentive->outsiders_incentive = $incentive->outsiders_incentive;

//             $paymentAmount = $model->amount;

//             $newIncentive->director_per_sales_incentive = (($incentive->directors_incentive ?? 0) / 100) * $paymentAmount;
//             $newIncentive->coordinator_per_sales_incentive = (($incentive->coordinators_incentive ?? 0) / 100) * $paymentAmount;
//             $newIncentive->shareholder_per_sales_incentive = (($incentive->shareholders_incentive ?? 0) / 100) * $paymentAmount;
//             $newIncentive->outsider_per_sales_incentive = (($incentive->outsiders_incentive ?? 0) / 100) * $paymentAmount;

//             $newIncentive->status = 'pending';
//             $newIncentive->created_by = auth()->user()->id;
//             $newIncentive->save();
//         }
//     }

//     $data = SalesIncentive::where('land_sale_id', $model->land_sale_id)
//                             ->where('payment_id', $model->id)
//                             ->get();

//     $directorData = $data->map(function ($item) {
//         return ['id' => $item->director_id, 'per_sales_incentive' => $item->director_per_sales_incentive];
//     })->unique('id')->values();

//     $coordinatorData = $data->map(function ($item) {
//         return ['id' => $item->coordinator_id, 'per_sales_incentive' => $item->coordinator_per_sales_incentive];
//     })->unique('id')->values();

//     $shareholderData = $data->map(function ($item) {
//         return ['id' => $item->shareholder_id, 'per_sales_incentive' => $item->shareholder_per_sales_incentive];
//     })->unique('id')->values();

//     $outsiderData = $data->map(function ($item) {
//         return ['id' => $item->outsider_id, 'per_sales_incentive' => $item->outsider_per_sales_incentive];
//     })->unique('id')->values();

//     $allData = [
//         ['role' => 'director', 'data' => $directorData],
//         ['role' => 'coordinator', 'data' => $coordinatorData],
//         ['role' => 'shareholder', 'data' => $shareholderData],
//         ['role' => 'outsider', 'data' => $outsiderData],
//     ];

//     $bankAccount = BankAccount::where('id', $model->account_id)->first();
//     if ($bankAccount) {
//         $bankAccount->current_balance += (float) $model->amount;
//         $bankAccount->update();

//         $bank_fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1])
//                                       ->where('bank_id', $model->bank_id)
//                                       ->first();
//         if ($bank_fund) {
//             $bank_fund->amount += $model->amount;
//             $bank_fund->updated_by = auth()->user()->id;
//             $bank_fund->update();
//         } else {
//             $fund_current_balance = new FundCurrentBalance();
//             $fund_current_balance->fund_id = $model->fund_id;
//             $fund_current_balance->bank_id = $model->bank_id;
//             $fund_current_balance->company_id = Session::get('company_id');
//             $fund_current_balance->amount = $model->amount;
//             $fund_current_balance->status = '1';
//             $fund_current_balance->created_by = auth()->user()->id;
//             $fund_current_balance->save();
//         }
//     } else {
//         $fund = FundCurrentBalance::where([
//             'fund_id' => $model->fund_id,
//             'company_id' => Session::get('company_id'),
//             'status' => 1,
//         ])->first();

//         if ($fund) {
//             $fund->amount += $model->amount;
//             $fund->updated_by = auth()->user()->id;
//             $fund->update();
//         } else {
//             $fund_current_balance = new FundCurrentBalance();
//             $fund_current_balance->fund_id = $model->fund_id;
//             $fund_current_balance->company_id = Session::get('company_id');
//             $fund_current_balance->amount = $model->amount;
//             $fund_current_balance->status = '1';
//             $fund_current_balance->created_by = auth()->user()->id;
//             $fund_current_balance->save();
//         }
//     }

//     return view('sales.general_payslip', compact('model'));
// }

public function store_land_sale_payment(Request $request)
{
    DB::beginTransaction();
    try {
        // প্রজেক্ট ডিটেইলস নিয়ে কাজ
        $project_id = LandPayment::with('landSale')->where('land_sale_id', $request->land_sale_id)->first();
        if (!$project_id) {
            throw new \Exception("Land sale payment not found");
        }

        $project = Project::where('id', $project_id->project_id)
                         ->where('company_id', Session::get('company_id'))
                         ->select('name')
                         ->first();

        if (!$project) {
            throw new \Exception("Project not found");
        }

        $project_name = $project->name;

        // ভাউচার নাম্বার জেনারেট করা
        $lastLandSalePayment = LandPayment::latest()->first();
        $lastNumber = ($lastLandSalePayment) ? $lastLandSalePayment->id : 0;
        $prefix = strtoupper(substr($project_name, 0, 5));
        $nextCustomerId = $lastNumber + 1;
        $voucherNumber = $prefix . '-' . $nextCustomerId;

        // নতুন পেমেন্ট ক্রিয়েট করা
        $model = new LandPayment();
        $model->voucher_no = $voucherNumber;
        $model->company_id = Session::get('company_id');
        $model->land_sale_id = $request->land_sale_id;
        $model->project_id = $project_id->project_id;
        $model->remarks = $request->remarks ?? '';
        $model->payment_type_id = $request->payment_type_id;
        $model->remaining_amount_cheque_no = $request->remaining_amount_cheque_no ?? '';
        $model->pay_date = $request->pay_date;
        $model->fund_id = $request->fund_id;
        $model->bank_id = $request->bank_id;
        $model->account_id = $request->account_id;
        $model->amount = $request->amount;
        $model->payment_option = $request->payment_option;
        $model->created_by = auth()->user()->id;
        $model->save();

        // ইন্সটলমেন্ট হ্যান্ডলিং
        if ($model->payment_option == 'notMade') {
            $installment = Installment::where('land_sale_id', $request->land_sale_id)
                                    ->where('company_id', Session::get('company_id'))
                                    ->first();

            if ($installment) {
                $installment->total_installment_number -= 1;
                $installment->update();
            }
        }

        // ল্যান্ড সেল রিমেইনিং অ্যামাউন্ট আপডেট
        if ($model->payment_option == 'initial') {
            $land = LandSale::where('id', $request->land_sale_id)
                           ->where('company_id', Session::get('company_id'))
                           ->first();

            if ($land) {
                $land->remaining_amount_date = $request->pay_date;
                $land->remaining_amount -= $request->amount;
                $land->update();
            }
        }

        // শুধুমাত্র প্লটের জন্য ইনসেনটিভ ক্যালকুলেশন
        $landSale = LandSale::find($model->land_sale_id);
        $isPlot = $landSale && $landSale->type == "Plot"; // শুধুমাত্র প্লট হলে ইনসেনটিভ ক্যালকুলেশন হবে

        if ($model->amount > 0 && $isPlot) {
            $baseIncentives = SalesIncentive::where('land_sale_id', $model->land_sale_id)
                                          ->whereNull('payment_id')
                                          ->get();

            foreach ($baseIncentives as $incentive) {
                $newIncentive = new SalesIncentive();
                $newIncentive->land_sale_id = $incentive->land_sale_id;
                $newIncentive->payment_id = $model->id;
                $newIncentive->director_id = $incentive->director_id;
                $newIncentive->coordinator_id = $incentive->coordinator_id;
                $newIncentive->shareholder_id = $incentive->shareholder_id;
                $newIncentive->outsider_id = $incentive->outsider_id;

                $newIncentive->directors_incentive = $incentive->directors_incentive ?? 0;
                $newIncentive->coordinators_incentive = $incentive->coordinators_incentive ?? 0;
                $newIncentive->shareholders_incentive = $incentive->shareholders_incentive ?? 0;
                $newIncentive->outsiders_incentive = $incentive->outsiders_incentive ?? 0;

                $paymentAmount = $model->amount;

                // শুধুমাত্র পার্সেন্টেজ হিসাবে ইনসেনটিভ ক্যালকুলেশন (প্লটের জন্য)
                $newIncentive->director_per_sales_incentive = (($incentive->directors_incentive ?? 0) / 100) * $paymentAmount;
                $newIncentive->coordinator_per_sales_incentive = (($incentive->coordinators_incentive ?? 0) / 100) * $paymentAmount;
                $newIncentive->shareholder_per_sales_incentive = (($incentive->shareholders_incentive ?? 0) / 100) * $paymentAmount;
                $newIncentive->outsider_per_sales_incentive = (($incentive->outsiders_incentive ?? 0) / 100) * $paymentAmount;

                $newIncentive->status = 'pending';
                $newIncentive->company_id = Session::get('company_id');
                $newIncentive->created_by = auth()->user()->id;
                $newIncentive->save();
            }
        }

        // ভাউচারের জন্য ডাটা প্রস্তুত
        $data = $isPlot ?
            SalesIncentive::where('land_sale_id', $model->land_sale_id)
                         ->where('payment_id', $model->id)
                         ->get() :
            collect();

        $directorData = $data->whereNotNull('director_id')->map(function ($item) {
            return [
                'id' => $item->director_id,
                'per_sales_incentive' => $item->director_per_sales_incentive,
            ];
        })->unique('id')->values()->all();

        $coordinatorData = $data->whereNotNull('coordinator_id')->map(function ($item) {
            return [
                'id' => $item->coordinator_id,
                'per_sales_incentive' => $item->coordinator_per_sales_incentive,
            ];
        })->unique('id')->values()->all();

        $shareholderData = $data->whereNotNull('shareholder_id')->map(function ($item) {
            return [
                'id' => $item->shareholder_id,
                'per_sales_incentive' => $item->shareholder_per_sales_incentive,
            ];
        })->unique('id')->values()->all();

        $outsiderData = $data->whereNotNull('outsider_id')->map(function ($item) {
            return [
                'id' => $item->outsider_id,
                'per_sales_incentive' => $item->outsider_per_sales_incentive,
            ];
        })->unique('id')->values()->all();

        $allData = [
            ['role' => 'director', 'data' => $directorData],
            ['role' => 'coordinator', 'data' => $coordinatorData],
            ['role' => 'shareholder', 'data' => $shareholderData],
            ['role' => 'outsider', 'data' => $outsiderData],
        ];

        // ব্যাংক একাউন্ট ব্যালেন্স আপডেট
        $bankAccount = BankAccount::find($model->account_id);
        if ($bankAccount) {
            $bankAccount->current_balance += (float) $model->amount;
            $bankAccount->update();

            $bank_fund = FundCurrentBalance::where([
                'fund_id' => $model->fund_id,
                'status' => 1,
                'bank_id' => $model->bank_id
            ])->first();

            if ($bank_fund) {
                $bank_fund->amount += $model->amount;
                $bank_fund->updated_by = auth()->user()->id;
                $bank_fund->update();
            } else {
                $fund_current_balance = new FundCurrentBalance();
                $fund_current_balance->fund_id = $model->fund_id;
                $fund_current_balance->bank_id = $model->bank_id;
                $fund_current_balance->company_id = Session::get('company_id');
                $fund_current_balance->amount = $model->amount;
                $fund_current_balance->status = '1';
                $fund_current_balance->created_by = auth()->user()->id;
                $fund_current_balance->save();
            }
        } else {
            // ফান্ড কারেন্ট ব্যালেন্স আপডেট
            $fund = FundCurrentBalance::where([
                'fund_id' => $model->fund_id,
                'company_id' => Session::get('company_id'),
                'status' => 1,
            ])->first();

            if ($fund) {
                $fund->amount += $model->amount;
                $fund->updated_by = auth()->user()->id;
                $fund->update();
            } else {
                $fund_current_balance = new FundCurrentBalance();
                $fund_current_balance->fund_id = $model->fund_id;
                $fund_current_balance->company_id = Session::get('company_id');
                $fund_current_balance->amount = $model->amount;
                $fund_current_balance->status = '1';
                $fund_current_balance->created_by = auth()->user()->id;
                $fund_current_balance->save();
            }
        }

        DB::commit();
        return view('sales.general_payslip', compact('model', 'allData'));

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withError($e->getMessage())->withInput();
    }
}



// development Payment only
// public function development_payment(Request $request)
// {
//     DB::beginTransaction();
//     try {
//         $request->validate([
//             'land_sale_id' => 'required|exists:land_sales,id',
//             'amount' => 'required|numeric|min:1',
//         ]);

//         $land = LandSale::where('id', $request->land_sale_id)
//                        ->where('company_id', Session::get('company_id'))
//                        ->firstOrFail();

//         if ($land->type == 'Flat') {
//             $land->flat_total_price += $request->amount;
//         } else {
//             $land->total_price += $request->amount;
//         }

//         // Update remaining amount
//         $land->remaining_amount += $request->amount;

//         $land->save();

//         DB::commit();

//         return redirect()->back()->with('success', 'Development Payment added successfully!');

//     } catch (\Exception $e) {
//         DB::rollBack();
//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }


// development Payment wiith table
public function development_payment(Request $request)
{
    DB::beginTransaction();
    try {
        $request->validate([
            'land_sale_id' => 'required|exists:land_sales,id',
            'amount' => 'required|numeric|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|file|max:5120',
            'note' => 'nullable|string',
        ]);

        $land = LandSale::where('id', $request->land_sale_id)
                        ->where('company_id', Session::get('company_id'))
                        ->firstOrFail();

        // Update total and remaining based on property type
        if ($land->type == 'Flat') {
            $land->flat_total_price += $request->amount;
        } elseif ($land->type == 'Land') {
            $land->land_total_price += $request->amount;
        } else {
            $land->total_price += $request->amount; // For Plot or other types
        }

        $land->remaining_amount += $request->amount;
        $land->save();

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_development.' . $request->image->extension();
            $request->image->move(public_path('upload_images/development_images'), $imageName);
        }

        // Save to development_payments table
        DevelopmentPayment::create([
            'land_sale_id' => $request->land_sale_id,
            'amount' => $request->amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'image' => $imageName,
            'note' => $request->note,
        ]);

        DB::commit();
        return redirect()->back()->with('success', 'Development Payment added successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


public function development_payment_list()
{
    $development_payments = DevelopmentPayment::with('land_sale.customer.project', 'land_payment')->latest()->get();
    return view('sales.development_payment_list', compact('development_payments'));
}


    public function store_incentive_withdraw(Request $request)
    {
        // dd($request->all());
        $incentive_stock = IncentiveStockPerSale::where('head_id', $request->head_id)
            ->where('land_sale_employee_id', $request->land_sale_employee_id)
            ->first();

        if (!$incentive_stock) {
            return redirect()->back()->withErrors(['error' => 'Incentive stock not found for the given head and employee.']);
        }

        $lastIncentivePayment = SalesIncentivePayment::latest()->first();
        $lastNumber = ($lastIncentivePayment) ? $lastIncentivePayment->id : 0;
        $nextIncentiveId = $lastNumber + 1;
        $voucherNumber = 'IV-' . $nextIncentiveId;

        $model = new SalesIncentivePayment();
        $model->voucher_no = $voucherNumber;
        $model->company_id = Session::get('company_id');
        $model->incentive_stock_per_sales_id = $incentive_stock->id;
        $model->payment_type_id = $request->payment_type_id;
        $model->cheque_no = $request->remaining_amount_cheque_no;
        $model->pay_date = $request->pay_date;
        $model->remarks = $request->remarks;
        $model->fund_id = $request->fund_id;
        $model->bank_id = $request->bank_id;
        $model->account_id = $request->account_id;
        $model->amount = $request->amount;
        $model->created_by = auth()->user()->id;
        $model->save();


        if ($incentive_stock->left_amount !== null && $incentive_stock->left_amount > 0) {
            $incentive_stock->left_amount = max(0, $incentive_stock->left_amount - $model->amount);
        } else {
            $incentive_stock->left_amount = max(0, $incentive_stock->incentive_amount - $model->amount);
        }

        $incentive_stock->update();


        $bankAccount = BankAccount::where('id', $model->account_id)->first();
        // dd($bankAccount);
        if ($bankAccount) {
            $bankAccount->current_balance += (float)$model->amount;
            $bankAccount->update();
            $bank_fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1])->where('bank_id', $model->bank_id)->first();
            if ($bank_fund) {
                $bank_fund->amount +=   $model->amount;
                $bank_fund->updated_by = auth()->user()->id;
                $bank_fund->update();
            } else {
                $fund_current_balance = new FundCurrentBalance();
                $fund_current_balance->fund_id = $model->fund_id;
                $fund_current_balance->bank_id = $model->bank_id;
                $fund_current_balance->company_id = Session::get('company_id');
                $fund_current_balance->amount = $model->amount;
                $fund_current_balance->status = '1';
                $fund_current_balance->created_by = auth()->user()->id;
                $fund_current_balance->save();
            }
        } else {
            $fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'company_id' => Session::get('company_id'), 'status' => 1])->first();
            if ($fund != null) {
                $fund->amount +=   $model->amount;
                $fund->updated_by = auth()->user()->id;
                // dd($fund);
                $fund->update();
            } else {
                $fund_current_balance             = new FundCurrentBalance();
                $fund_current_balance->fund_id    = $model->fund_id;
                $fund_current_balance->company_id = Session::get('company_id');
                $fund_current_balance->amount =   $model->amount;
                $fund_current_balance->status     = '1';
                $fund_current_balance->created_by = auth()->user()->id;
                $fund_current_balance->save();
            }
        }


        $msg = "Withdrawn Incentive Amount.";
        return redirect()->route('incentive_stock_list')->with('status', $msg);;
    }



    public function paid_installment_list()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'paid_installment_list';
        $data['fund_types'] = Fund::all();
        $data['banks'] = Bank::get();
        $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['payment_types'] = PaymentType::get();
        $data['company_name'] = Session::get('company_name');

        $data['paid_installment_list'] = LandPayment::where(['company_id' => Session::get('company_id')])->with('landSale', 'fund', 'bank', 'account', 'PaymentType')->orderBy('id', 'DESC')->get();

        return view('sales.pay_installment_list', $data);
    }

    public function payment_credit_voucher($id)
    {
        $land_sale_payment = LandPayment::where(['company_id' => Session::get('company_id')])->with('landSale', 'fund', 'bank', 'account', 'PaymentType')->where('id', $id)->first();
        // dd($land_sale_payment);
        return view('sales.payment_credit_voucher', compact('land_sale_payment'));
    }

    public function sale_payment_money_receipt($id)
    {
        $model = LandPayment::where(['company_id' => Session::get('company_id')])->with('landSale', 'fund', 'bank', 'account', 'PaymentType')->where('id', $id)->first();
        // dd($model);
        return view('sales.sale_payment_money_receipt', compact('model'));
    }

    public function installment_statement()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'installment_statement';
        $data['fund_types'] = Fund::all();
        $data['banks'] = Bank::get();
        $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['payment_types'] = PaymentType::get();
        $data['company_name'] = Session::get('company_name');
        $data['customers'] = Customer::where('company_id', Session::get('company_id'))->get();
        $data['projects'] = Project::where('company_id', Session::get('company_id'))->get();
        $data['plots'] = Plot::where('company_id', Session::get('company_id'))->get();

        $data['installment_statement'] = LandPayment::where(['company_id' => Session::get('company_id')])->with('landSale', 'fund', 'bank', 'account', 'PaymentType')->orderBy('id', 'DESC')->get();

        return view('sales.installment_statement', $data);
    }

    public function customerStatement(Request $request)
    {
        $customer_id = $request->customer_id;

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $customer = Customer::where('id', $request->customer_id)->first();

        $query = LandSale::where('customer_id', $customer_id)->where('company_id', Session::get('company_id'))->first();
        $installment = Installment::where('land_sale_id', $query->id)->first();
        // dd($installment);

        $installment_date = Carbon::parse($installment->installment_date);
        $installment_number = $installment->total_installment_number;

        $payments = LandPayment::where('land_sale_id', $query->id)->where('payment_option', 'installment')->get();
        $company = Company::where('id', Session::get('company_id'))->first();

        return view('sales.installment_statement_list', compact('query', 'installment_date', 'installment', 'installment_number', 'payments', 'company', 'customer'));
    }

    //Sector
    public function sector()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'sector';
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();
        // dd($data['project_data']);
        $data['sectors'] = Sector::with('project')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        return view('sales.sector', $data);
    }

    public function save_sector(Request $request)
    {
        $request->validate([
            'sector_name' => 'required',
            'project_id' => 'required',
        ]);

        $model = new Sector();
        $model->sector_name = $request->post('sector_name');
        $model->project_id = $request->post('project_id');
        $model->created_by = auth()->user()->id;
        $model->company_id = Session::get('company_id');
        $model->save();

        $msg = "Sector Inserted.";

        return redirect()->route('sector')->with('status', $msg);
    }

    public function update_sector(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'sector_name' => 'required',
            'project_id' => 'required',
        ]);

        $model = Sector::findOrFail($id);
        $model->sector_name = $request->sector_name;
        $model->project_id = $request->project_id;
        $model->updated_by = auth()->user()->id;
        $model->save();

        $msg = "Sector Updated.";
        return redirect()->route('sector')->with('status', $msg);
    }

    public function sector_delete($id)
    {
        $sector_id = Sector::find($id);
        // dd($sector_id);
        $sector_id->delete();
        return redirect()->route('sector')->with('status', 'Sector Deleted Successfully');
    }

    //Road
    public function road()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'road';
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();
        $data['sectors'] = Sector::with('project')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        $data['roads'] = Road::with('sector')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        return view('sales.road', $data);
    }

    public function save_road(Request $request)
    {
        $request->validate([
            'road_name' => 'required',
            'sector_id' => 'required',
            'road_size' => 'required',
        ]);

        $model = new Road();
        $model->road_name = $request->post('road_name');
        $model->road_size = $request->post('road_size');
        $model->sector_id = $request->post('sector_id');
        $model->created_by = auth()->user()->id;
        $model->company_id = Session::get('company_id');
        $model->save();

        $msg = "Road Inserted.";

        return redirect()->route('road')->with('status', $msg);
    }

    public function update_road(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'road_name' => 'required',
            'road_size' => 'required',
            'sector_id' => 'required',
        ]);

        $model = Road::findOrFail($id);
        $model->road_name = $request->road_name;
        $model->road_size = $request->road_size;
        $model->sector_id = $request->sector_id;
        $model->updated_by = auth()->user()->id;
        $model->save();

        $msg = "Road Updated.";
        return redirect()->route('road')->with('status', $msg);
    }

    public function road_delete($id)
    {
        $road_id = Road::find($id);
        // dd($road_id);
        $road_id->delete();
        return redirect()->route('road')->with('status', 'Road Deleted Successfully');
    }

    //Plot Type

    public function plotType(Request $request)
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'plot-type';
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();

        $plot_types = PlotType::where(['company_id' => Session::get('company_id')])->with('company', 'project');

        if ($request->project_id != null) {
            $plot_types->where('project_id', $request->project_id);
        }

        $data['plot_types'] = $plot_types->paginate(15);

        return view('sales.plot_type', $data);
    }

    public function plotTypeSave(Request $request)
    {
        $validatedData = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'plot_type' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = PlotType::where('project_id', $request->project_id)
                        ->where('plot_type', $value)
                        ->exists();
                    if ($exists) {
                        $fail('The plot type already exists under this project....');
                    }
                },
            ],
            'percentage' => 'required',
        ]);

        try {
            $plotType = new PlotType();
            $plotType->project_id = $validatedData['project_id'];
            $plotType->company_id = Session::get('company_id');
            $plotType->plot_type = $validatedData['plot_type'];
            $plotType->percentage = $validatedData['percentage'];
            $plotType->created_by = auth()->user()->id;
            $plotType->save();

            $msg = "Plot Type Inserted.";

            return redirect()->route('plot_type')->with('status', $msg);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create plot type. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function plotTypeUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'plot_type' => [
                'required',
                'string',
                'max:255'
            ],
            'percentage' => 'required',
        ]);

        try {
            $plotType = PlotType::find($request->type_id);
            $plotType->project_id = $validatedData['project_id'];
            $plotType->plot_type = $validatedData['plot_type'];
            $plotType->percentage = $validatedData['percentage'];
            $plotType->created_by = auth()->user()->id;
            $plotType->save();

            $msg = "Plot Type Updated.";

            return redirect()->route('plot_type')->with('status', $msg);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create plot type. ' . $e->getMessage(),
            ], 500);
        }
    }

    //Plot
    public function plot()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'plot';
        $data['sectors'] = Sector::with('project')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        $data['roads'] = Road::with('sector')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        $data['plots'] = Plot::with('road', 'project', 'sector')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();

        return view('sales.plot', $data);
    }

    public function createPlotForm()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'plot';
        $data['sectors'] = Sector::with('project')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        $data['roads'] = Road::with('sector')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        $data['plots'] = Plot::with('road')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();
        $data['plot_types'] = PlotType::where(['company_id' => Session::get('company_id')])->with('company', 'project')->get();

        return view('sales.create_plot', $data);
    }

    public function getSectorWiseRoad(Request $request)
    {
        $sectorId = $request->sector_id;
        $roads = Road::distinct()->where('company_id', Session::get('company_id'))->where('sector_id', $sectorId)->get();

        if ($roads) {
            return response()->json($roads);
        }

        return response()->json([]);
    }

    // public function save_plot(Request $request)
    // {
    //     $request->validate([
    //         'road_id'   => 'required',
    //         'sector_id' => 'required',
    //         'plot_no'   => [
    //             'required',
    //             'max:255',
    //             function ($attribute, $value, $fail) use ($request) {
    //                 $exists = Plot::where('project_id', $request->project_id)
    //                     ->where('plot_no', $value)
    //                     ->exists();
    //                 if ($exists) {
    //                     $fail('The plot No. already exists under this project....');
    //                 }
    //             },
    //         ],
    //     ]);

    //     $plots = $request->post('plot_no');
    //     $road_ids = $request->post('road_id');
    //     $sector_ids = $request->post('sector_id');
    //     $block_nos = $request->post('block_no');
    //     $measurements = $request->post('measurement');
    //     $facings = $request->post('facing');
    //     $plot_size = $request->post('plot_size');
    //     $plot_types = $request->post('plot_type');
    //     $project_id = $request->post('project_id');
    //     $type_id    = $request->post('type_id');

    //     if (!is_array($plots) || empty($plots)) {
    //         return redirect()->route('plot')->with('status', 'No plots provided.');
    //     }

    //     foreach ($plots as $index => $plot_no) {
    //         $model = new Plot();
    //         $model->project_id = $project_id;
    //         $model->type_id = $type_id;
    //         $model->plot_no = $plot_no;
    //         $model->road_id = $road_ids[$index] ?? null;
    //         $model->sector_id = $sector_ids[$index] ?? null;
    //         $model->block_no = $block_nos[$index] ?? null;
    //         $model->measurement = $measurements[$index] ?? null;
    //         $model->facing = $facings[$index] ?? null;
    //         $model->plot_size = $plot_size ?? null;
    //         $model->plot_type = $plot_types[$index] ?? null;
    //         $model->company_id = Session::get('company_id');
    //         $model->created_by = auth()->user()->id;
    //         $model->save();
    //     }

    //     // $type = PlotType::find($type_id);
    //     // $total_plot_percentage = $type->percentage * count($plots);
    //     // $stock = LandStock::where(['project_id'=>$model->project_id,'company_id'=>Session::get('company_id')])->first();

    //     //     if(!empty($stock)){
    //     //         $stock->total_stock_land -=  $total_plot_percentage;
    //     //         $stock->save();
    //     //     }


    //     $msg = "Plots Inserted Successfully.";
    //     return redirect()->route('plot')->with('status', $msg);
    // }
    
        public function save_plot(Request $request)
{
    $plots = $request->post('plot_no');
    $road_ids = $request->post('road_id');
    $sector_ids = $request->post('sector_id');
    $block_nos = $request->post('block_no');
    $measurements = $request->post('measurement');
    $facings = $request->post('facing');
    $plot_size = $request->post('plot_size');
    $plot_types = $request->post('plot_type');
    $project_id = $request->post('project_id');
    $type_id    = $request->post('type_id');

    if (!is_array($plots) || empty($plots)) {
        return redirect()->route('plot')->with('status', 'No plots provided.');
    }

    foreach ($plots as $index => $plot_no) {
        $road_id = $road_ids[$index] ?? null;
        $sector_id = $sector_ids[$index] ?? null;
        $block_no = $block_nos[$index] ?? null;
        $measurement = $measurements[$index] ?? null;
        $facing = $facings[$index] ?? null;
        $type = $plot_types[$index] ?? null;

        // ✅ Check for existing plot with same combination
        $exists = Plot::where('project_id', $project_id)
            ->where('plot_no', $plot_no)
            ->where('road_id', $road_id)
            ->where('sector_id', $sector_id)
            ->where('plot_size', $plot_size)
            ->exists();

        if ($exists) {
            return redirect()->route('plot')
                ->with('status', "The plot No. $plot_no already exists under this project....");
        }

        // ✅ Save if not exists
        $model = new Plot();
        $model->project_id = $project_id;
        $model->type_id = $type_id;
        $model->plot_no = $plot_no;
        $model->road_id = $road_id;
        $model->sector_id = $sector_id;
        $model->block_no = $block_no;
        $model->measurement = $measurement;
        $model->facing = $facing;
        $model->plot_size = $plot_size;
        $model->plot_type = $type;
        $model->company_id = Session::get('company_id');
        $model->created_by = auth()->user()->id;
        $model->save();
    }

    $msg = "Plots Inserted Successfully.";
    return redirect()->route('plot')->with('status', $msg);
}

    public function update_plot(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'plot_no' => 'required',
            'road_id' => 'required',
            'sector_id' => 'required',
        ]);

        $model = Plot::findOrFail($id);
        $model->plot_no = $request->post('plot_no');
        $model->road_id = $request->post('road_id');
        $model->sector_id = $request->post('sector_id');
        $model->block_no = $request->post('block_no');
        $model->measurement = $request->post('measurement');
        $model->facing = $request->post('facing');
        $model->plot_size = $request->post('type_id');
        $model->plot_type = $request->post('plot_type');
        $model->company_id = Session::get('company_id');
        $model->updated_by = auth()->user()->id;
        $model->save();

        $msg = "Plot Updated.";
        return redirect()->route('plot')->with('status', $msg);
    }

    public function plot_delete($id)
    {
        $plot_id = Plot::find($id);
        // dd($road_id);
        $plot_id->delete();
        return redirect()->route('plot')->with('status', 'Plot Deleted Successfully');
    }

    //Floor
    public function flat_floor()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'flat_floor';
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->get();
        $data['flat_floors'] = FlatFloor::with('project')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        return view('sales.flat_floor', $data);
    }

    // public function getSectorWiseRoad(Request $request)
    // {
    //     $sectorId = $request->sector_id;
    //     $roads = Road::distinct()->where('company_id', Session::get('company_id'))->where('sector_id', $sectorId)->get();

    //     if ($roads) {
    //         return response()->json($roads);
    //     }

    //     return response()->json([]);
    // }

    public function save_flat_floor(Request $request)
    {
        $request->validate([
            'floor_no' => 'required',
            'project_id' => 'required',
        ]);

        $model = new FlatFloor();
        $model->floor_no = $request->post('floor_no');
        $model->project_id = $request->post('project_id');
        $model->company_id = Session::get('company_id');
        $model->created_by = auth()->user()->id;
        $model->save();

        $msg = "Floor Inserted.";

        return redirect()->route('flat_floor')->with('status', $msg);
    }

    public function update_flat_floor(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'floor_no' => 'required',
            'project_id' => 'required',
        ]);

        $model = FlatFloor::findOrFail($id);
        $model->floor_no = $request->post('floor_no');
        $model->project_id = $request->post('project_id');
        $model->company_id = Session::get('company_id');
        $model->updated_by = auth()->user()->id;
        $model->update();

        $msg = "Floor Updated.";
        return redirect()->route('flat_floor')->with('status', $msg);
    }

    //Flat
    public function flat()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'flat';
        $data['flat_floors'] = FlatFloor::with('project')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        $data['flats'] = Flat::with('flat_floor')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        return view('sales.flats', $data);
    }

    public function save_flat(Request $request)
    {
        $request->validate([
            'flat_no' => 'required',
            'flat_size' => 'required',
            'flat_floor_id' => 'required',
        ]);

        $model = new Flat();
        $model->flat_no = $request->post('flat_no');
        $model->flat_floor_id = $request->post('flat_floor_id');
        $model->flat_size = $request->post('flat_size');
        $model->company_id = Session::get('company_id');
        $model->created_by = auth()->user()->id;
        $model->save();

        $msg = "Flat Inserted.";

        return redirect()->route('flat')->with('status', $msg);
    }

    public function update_flat(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'flat_no' => 'required',
            'flat_size' => 'required',
            'flat_floor_id' => 'required',
        ]);

        $model = Flat::findOrFail($id);
        $model->flat_no = $request->post('flat_no');
        $model->flat_floor_id = $request->post('flat_floor_id');
        $model->flat_size = $request->post('flat_size');
        $model->company_id = Session::get('company_id');
        $model->updated_by = auth()->user()->id;
        $model->update();

        $msg = "Flat Updated.";
        return redirect()->route('flat')->with('status', $msg);
    }

    public function print(Request $request)
    {
        $sales = Sales::where(['company_id' => Session::get('company_id')])->with('company', 'project', 'sales_details', 'sales_details.item', 'sales_payment', 'sales_payment.payment_details', 'sales_payment.fund')->orderBy('id', 'DESC')->first();
        //dd($sales->sales_payment);
        $where = array();
        if ($request->project_id != null) {
            $where['project_id'] = $request->project_id;
            $sales->where('project_id', '=', $request->project_id);
        }
        if ($request->start_date != null) {
            $where['start_date'] = $request->start_date;
            $sales->where('sales_date', '>=', $request->start_date);
        }
        if ($request->end_date != null) {
            $where['end_date'] = $request->end_date;
            $sales->where('sales_date', '<=', $request->end_date);
        }
        $sales = $sales->get();
        $data['sales'] = $sales;
        return view('sales.invoice_list_print', $data);
    }

    public function pdf(Request $request)
    {
        $sales = Sales::where(['company_id' => Session::get('company_id')])->with('company', 'project', 'sales_details', 'sales_details.item', 'sales_payment', 'sales_payment.payment_details', 'sales_payment.fund')->orderBy('id', 'DESC')->first();
        //dd($sales->sales_payment);
        $where = array();
        if ($request->project_id != null) {
            $where['project_id'] = $request->project_id;
            $sales->where('project_id', '=', $request->project_id);
        }
        if ($request->start_date != null) {
            $where['start_date'] = $request->start_date;
            $sales->where('sales_date', '>=', $request->start_date);
        }
        if ($request->end_date != null) {
            $where['end_date'] = $request->end_date;
            $sales->where('sales_date', '<=', $request->end_date);
        }
        $sales = $sales->get();
        $data['sales'] = $sales;

        $pdf = PDF::loadView('sales.invoice_list_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('invoice-list_' . $string . '.pdf');
    }

    public function create()
    {
        $data['main_menu'] = 'sales';
        $data['child_menu'] = 'create-invoice';
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();
        $data['item_data'] = Item::where(['company_id' => Session::get('company_id'), 'status' => 1])->with('company')->get();
        $data['fund_data'] = Fund::where(['status' => 1])->get();

        return view('sales.create', $data);
    }
    public function getProjectWisePloat(Request $request)
    {
        $projectId = $request->input('projectId');
        $sectors = Sector::where('project_id', $projectId)->get();
        $option = '<option>Select Sector</option>';
        foreach ($sectors as $sector) {
            $option .= '<option value="' . $sector->id . '">' . $sector->sector_name . '</option>';
        }
        return response()->json($option);
    }
}
