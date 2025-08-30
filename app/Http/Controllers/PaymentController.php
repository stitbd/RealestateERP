<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Fund;
use App\Models\Company;
use App\Models\FundLog;
use App\Models\Payment;
use App\Models\Project; 
use App\Models\Purchase;
use App\Models\Supplier; 
use App\Models\WorkOrder;
use App\Models\AccountHead;
use App\Models\BankAccount;
use App\Models\SupplierDue;
use Illuminate\Http\Request; 
use App\Models\PaymentDetails;
use App\Models\AccountCategory;
use App\Models\FundCurrentBalance;
use Illuminate\Support\Facades\Session;
use Faker\Provider\Payment as ProviderPayment;
Use PDF;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'supplier';
        $data['child_menu']             = 'supplier-payment-list';
        $requisitions = Payment::where(['company_id'=>Session::get('company_id')])->with('company','project','supplier','fund','payment_details');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $requisitions->where('fund_id','=',$request->fund_id);
        }
        if($request->supplier_id != null){
            $where['supplier_id'] = $request->supplier_id;
            $requisitions->where('supplier_id','=',$request->supplier_id);
        }

        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('payment_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('payment_date','<=',$request->end_date);
        }

        $requisitions = $requisitions->paginate(20);

        $requisitions->appends($where);

        $data['payments']               = $requisitions;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['supplier_data']          = Supplier::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();
        $data['fund_data']              = Fund::where(['status'=>1])->get();

        return view('purchase.payment_list',$data);
    }

    public function print(Request $request)
    {
        $requisitions = Payment::where(['company_id'=>Session::get('company_id')])->with('company','project','supplier','fund','payment_details','order');
        $where = array();

        // if($request->project_id != null){
        //     $where['project_id'] = $request->project_id;
        //     $requisitions->where('project_id','=',$request->project_id);
        // }
        
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $requisitions->where('fund_id','=',$request->fund_id);
        }
        if($request->supplier_id != null){
            $where['supplier_id'] = $request->supplier_id;
            $requisitions->where('supplier_id','=',$request->supplier_id);
        }

        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('payment_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('payment_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->get();
        $data['payments']               = $requisitions;
        return view('purchase.payment_list_print',$data);
    }

    public function pdf(Request $request)
    {
        $requisitions = Payment::where(['company_id'=>Session::get('company_id')])->with('company','project','supplier','fund','payment_details');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $requisitions->where('fund_id','=',$request->fund_id);
        }
        if($request->supplier_id != null){
            $where['supplier_id'] = $request->supplier_id;
            $requisitions->where('supplier_id','=',$request->supplier_id);
        }

        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('payment_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('payment_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->get();
        $data['payments']               = $requisitions;

        $pdf = PDF::loadView('purchase.payment_list_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('supplier-payment_'.$string.'.pdf');
    }

    public function supplier_payment(){
        $data['main_menu']              = 'supplier';
        $data['child_menu']             = 'supplier-payment';
        $data['project_data']           = Project::where(['company_id'  => Session::get('company_id')])->with('company')->get();
        $data['supplier_data']          = Supplier::where(['company_id' => Session::get('company_id'),'status'=>1])->with('company')->get();
        $data['work_orders']            = WorkOrder::where(['company_id' => Session::get('company_id')])->whereIn('payment_status',[1,2])->get();
        $data['banks']                  = Bank::get();
        $data['bank_accounts']          = BankAccount::where(['company_id' => Session::get('company_id')])->get();
        $data['company']                = Company::get();
        $data['fund_data']              = Fund::where(['status'=>1])->get();
        $data['categories']             = AccountCategory::get();
        $data['head']                   = AccountHead::get();

        return view('purchase.supplier_payment',$data);
    }


    public function filterOrder(Request $request){
        $orders = WorkOrder::where('supplier_id',$request->supplier_id)->whereIn('payment_status',[1,2])->get();
        if(count($orders) >0 ){
            return response()->json($orders);
        }
    }

    public function supplier_credit_voucher(){
        return view('purchase.supplier_credit_voucher');
    }

    public function readyVoucher(Request $request)
    {    
        try{   

                $lastVoucher = Payment::latest()->first();
                $lastNumber = ($lastVoucher) ? $lastVoucher->id : 0;
                $date = now()->format('Y');
                $voucherNumber = "PAY-" .$date. $lastNumber + 1;
                $code          = "P-" . $date . $lastNumber + 1;
                
                $category = '';
                $head = '';

                $model                      = new Payment();
                $model->company_id          = session()->get('company_id');

                if($request->category_name){
                    $category_type           = json_encode($request->category_type);
                    $category                = new AccountCategory;
                    $category->category_name = $request->category_name;
                    $category->category_type = $category_type;
                }else{
                    $model->category_id         = $request->category;
                }

                if($request->head_name){
                    $head            = new AccountHead;
                    $head->head_name = $request->head_name;
                }else{
                    $model->head_id             = $request->head;
                }

                $model->supplier_id         = $request->supplier_id;
                $model->work_order_id       = $request->order_id;
                $model->code_no             = $code;
                $model->voucher_no          = $voucherNumber;
                $model->fund_id             = $request->fund_id;
                $model->payment_date        = $request->payment_date;
                $model->payment_type        = $request->payment_type;
                $model->amount              = $request->amount;
                $model->remarks             = $request->remarks;
                $model->status              = '1';
                $model->created_by          = auth()->user()->id;


                $details = new PaymentDetails();
                $details->receiver_name          =  $request->receiver_name;
                $details->mobile_no              =  $request->mobile_no;
                $details->bank_id                =  $request->bank_id;
                $details->account_id             =  $request->account_id;
                $details->account_holder_name    =  $request->account_holder_name;
                $details->cheque_number          =  $request->cheque_no;
                $details->cheque_issue_date      =  $request->cheque_issue_date;
                $details->payment_note           =  $request->payment_note;


                if($request->attachment != null){
                    $newImageName =  time().'_payment.'.$request->attachment->extension();
                    $request->attachment->move(public_path('attachment'),$newImageName);
                    $details->attachment = $newImageName;
                }

                $details->status          = '1';
                $details->created_by      = auth()->user()->id;

                
                $fund_log                       = new FundLog();
                $fund_log->company_id           = Session::get('company_id');
                $fund_log->fund_id              = $request->fund_id;
                $fund_log->type                 = '2';
                $fund_log->amount               = $request->amount;
                $fund_log->transection_type     = 'supplier-payment';
                $fund_log->transection_date     = $request->payment_date;
                $fund_log->payment_type         = $request->payment_type;
                $fund_log->status               = '1';
                $fund_log->created_by           = auth()->user()->id;
                
        
                session([
                    'model'                     => $model,
                    'details'                   => $details,
                    'fund_log'                  => $fund_log,
                    'category'                  => $category,
                    'head'                      => $head,
                ]);

            $work_order = WorkOrder::where('id',$request->order_id)->first();

            if($work_order && $work_order->total_price >= $request->amount){
                return view('purchase.supplier_debit_voucher',compact('model','fund_log','category','head','details'));

            }else{
                $msg="Payment Amount Is Greater Then Bill Amount.";
                return redirect('supplier-payment')->with('status', $msg);
            }


            
        }catch (\Exception $e) {
            return $e->getMessage();
        }
            
        
    }
    public function store(Request $request)
    {
        try{
            $model              = Session::get('model');
            $details            = Session::get('details');
            $fund_log           = Session::get('fund_log');
            $category           = Session::get('category');
            $head               = Session::get('head');

             
            $work_order = WorkOrder::where('id',$model->work_order_id)->first();

            if($model && $model->amount && $work_order->total_price >= $model->amount){

                $work_order->total_price      -=  $model->amount;
                
                if($work_order->total_price > $model->amount){
                    $work_order->payment_status    =  2;
                }
                
                if($work_order->total_price ==  $model->amount){
                    $work_order->payment_status  =  3;
                }

                $work_order->update();

                $lastVoucher         = Payment::latest()->first();
                $lastNumber          = ($lastVoucher) ? $lastVoucher->id : 0;
                $date                = now()->format('Y');
                $codeNumber          = "P-".$date. $lastNumber + 1;
                $voucherNumber       = "PAY-" .$date. $lastNumber + 1;
                $model->voucher_no   = $voucherNumber;
                $model->code_no      = $codeNumber;
 
                if($head != ""){
                    if($category != ""){
                        $category->save();
                        $model->category_id    =  $category->id;
                        $head->category_id     =  $category->id;
                    }else{
                        $head->category_id     =  $model->category_id;
                    }
                    $head->save();
                    $model->head_id =  $head->id;
                }

                $model->save();
                $payment_id = $model->id;
                $details->payment_id = $payment_id;
                $details->save();

                $supplier_due = SupplierDue::where('supplier_id',$model->supplier_id)->first();

                if($supplier_due){
                    $supplier_due->due_amount -=  $model->amount;
                    $supplier_due->update();
                }

                // dd($details ->bank_id);
                if($details ->bank_id){
                    $bankAccount = BankAccount::where('id',$details->account_id)->where('company_id',session()->get('company_id'))->first();
                        if($bankAccount){
                            $bankAccount->current_balance -= $model->amount;
                            $bankAccount->update();
                            $bank_fund    = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status'=>1, 'company_id'=>session()->get('company_id')])->where('bank_id' , $details->bank_id)->first();
                            if($bank_fund){
                            $bank_fund->amount  -=  $model->amount; 
                            $bank_fund->update();
                        }
                    }
                        $fund_log->transection_id  =  $payment_id;
                        $fund_log->save();
                }else{
                    $fund = FundCurrentBalance::where(['fund_id' => $model->fund_id,'status'=>1,'company_id'=>session()->get('company_id')])->first();
                    
                    if($fund != null){
                        $fund->amount -=  $model->amount;
                        $fund->updated_by = auth()->user()->id; 
                        $fund->update();
                    }
                    else{
                        $fund_current_balance               = new FundCurrentBalance();
                        $fund_current_balance->fund_id      = $model->fund_id;
                        $fund_current_balance->company_id   = session()->get('company_id');
                        $fund_current_balance->amount       = -$model->amount;
                        $fund_current_balance->status       = '1';
                        $fund_current_balance->created_by   = auth()->user()->id;
                        $fund_current_balance->save();
                    }
                    
                    $fund_log->transection_id  =  $payment_id;
                    $fund_log->save();
                }    

                $msg="Payment Inserted.";
                $request->session()->flash('message',$msg);
            }
            else{
                $msg="Payment Not Inserted.";
                $request->session()->flash('warning',$msg);
            }
            
            return redirect('supplier-payment')->with('status', $msg);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // function save_supplier_payment(Request $request){
    //     $project_id = $request->project_id;
    //     $supplier_id = $request->supplier_id;
    //     $payment_date = $request->payment_date;
    //     $fund_id = $request->fund_id;
    //     $payment_type = $request->payment_type;
    //     $amount = $request->amount;
    //     $receiver_name = $request->receiver_name;
    //     $mobile_no = $request->mobile_no;
    //     $nid = $request->nid;
    //     $address = $request->address;
    //     $check_number = $request->check_number;
    //     $check_issue_date = $request->check_issue_date;
    //     $bank_name = $request->bank_name;
    //     $bank_account_no = $request->bank_account_no;
    //     $account_holder_name = $request->account_holder_name;
    //     $payment_note = $request->payment_note;
    //     $remarks = $request->remarks;
    //     if($project_id != null && $amount != null && $supplier_id != null && $payment_date != null && $fund_id != null){
    //         $model = new Payment();
    //         $model->company_id      = Session::get('company_id');
    //         $model->project_id      = $project_id;
    //         $model->supplier_id     = $supplier_id;
    //         $model->payment_date    = $payment_date;
    //         $model->fund_id         = $fund_id;
    //         $model->payment_type    = $payment_type;
    //         $model->amount          = $amount;
    //         $model->status          = '1';
    //         $model->created_by      = auth()->user()->id;
    //         $model->save();
    //         $supplier = Supplier::where('id',$supplier_id)->first();
    //         $supplier_name = $supplier->name;
    //         $payment_id = $model->id;

    //         $details = new PaymentDetails();
    //         $details->payment_id = $payment_id;
    //         $details->receiver_name = $receiver_name;
    //         $details->mobile_no = $mobile_no;
    //         $details->nid = $nid;
    //         $details->address = $address;
    //         $details->check_number = $check_number;
    //         $details->check_issue_date = $check_issue_date;
    //         $details->bank_name = $bank_name;
    //         $details->bank_account_no = $bank_account_no;
    //         $details->account_holder_name = $account_holder_name;
    //         $details->payment_note = $payment_note;
    //         $details->remarks = $remarks;
    //         if($request->attachment != null){
    //             $newImageName = time().'_supplier_payment.'.$request->attachment->extension();
    //             $request->attachment->move(public_path('attachment'),$newImageName);

    //             $details->attachment = public_path('attachment/'.$newImageName);
    //         }

    //         $details->status          = '1';
    //         $details->created_by      = auth()->user()->id;
    //         $details->save();
            

    //         $supplier_due = SupplierDue::where(['company_id'=>Session::get('company_id'),'supplier_id'=>$request->supplier_id,'project_id'=>$project_id])->first();
    //         if($supplier_due != null){
    //             $supplier_due->due_amount = $supplier_due->due_amount-$amount;
    //             $supplier_due->save();
    //         }
    //         else{
    //             $supplier_due               = new SupplierDue();
    //             $supplier_due->company_id   = Session::get('company_id');
    //             $supplier_due->project_id   = $request->project_id;
    //             $supplier_due->supplier_id  = $request->supplier_id;
    //             $supplier_due->due_amount   = -$amount;
    //             $supplier_due->save();
    //         }

    //         $fund_log                       = new FundLog();
    //         $fund_log->company_id           = Session::get('company_id');
    //         $fund_log->fund_id              = $request->fund_id;
    //         $fund_log->type                 = '2';
    //         $fund_log->amount               = $amount;
    //         $fund_log->transection_type     = 'supplier_payment';
    //         $fund_log->transection_id       = $payment_id;
    //         $fund_log->transection_date     = $payment_date;
    //         $fund_log->status               = '1';
    //         $fund_log->created_by           = auth()->user()->id;
    //         $fund_log->save();

    //         $fund = FundCurrentBalance::where(['fund_id'=>$fund_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();
            
    //         if($fund != null){
    //             $fund->amount = $fund->amount - $amount;
    //             $fund->updated_by = auth()->user()->id;
    //             $fund->update();
    //         }else{
    //             $fund = new FundCurrentBalance();
    //             $fund->fund_id = $request->fund_id;
    //             $fund->company_id = Session::get('company_id');
    //             $fund->amount = $request->amount;
    //             $fund->status = '1';
    //             $fund->created_by = auth()->user()->id;
    //             $fund->save();
    //         }

    //         $msg="Payment Updated.";
    //         $request->session()->flash('message',$msg);
    //     }
    //     else{
    //         $msg="Payment Not Updated.";
    //         $request->session()->flash('warning',$msg);
    //     }
        
    //     return view('purchase.supplier_credit_voucher',compact('model','details','supplier_name'));
    // }

    function load_supplier_due($order_id=''){
        $order = WorkOrder::where(['company_id'=>Session::get('company_id'),'id'=>$order_id])->first();
        if($order != null){
            echo $order->total_price;
        }
        else{
            echo 0;
        }
    }

    function due_print(){
        $data['title']                  = 'Supplier Due List || '.Session::get('company_name');
        $data['due_data']               = SupplierDue::with('company','project','supplier')->where('due_amount','!=',0)->where(['company_id'=>Session::get('company_id')])->get();
        return view('purchase.due_print',$data);
    }

    function due_pdf(){
        $data['title']                  = 'Supplier Due List || '.Session::get('company_name');
        $data['due_data']               = SupplierDue::with('company','project','supplier')->where('due_amount','!=',0)->where(['company_id'=>Session::get('company_id')])->get();
        
        $pdf = PDF::loadView('purchase.due_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('supplier-due_'.$string.'.pdf');
    }

    function supplier_due(){
        $data['main_menu']              = 'supplier';
        $data['child_menu']             = 'supplier-due';
        $data['due_data']               = SupplierDue::with('company','project','supplier')->where('due_amount','!=',0)->where(['company_id'=>Session::get('company_id')])->get();
        return view('purchase.supplier_due',$data);
    }

    function supplier_log($supplier_id){
        $data['main_menu']              = 'supplier';
        $data['child_menu']             = 'supplier-due';
        $data['due_amount']             = SupplierDue::with('company','project','supplier')->where('due_amount','!=',0)->where(['company_id'=>Session::get('company_id'),'supplier_id'=>$supplier_id])->get()->first();
        $data['purchase']               = WorkOrder::with('supplier')->where(['supplier_id'=>$supplier_id,'company_id'=>Session::get('company_id')])->get();
        $data['payments']               = Payment::with('company','supplier')->where(['supplier_id'=>$supplier_id,'company_id'=>Session::get('company_id')])->get();
        return view('purchase.supplier_log',$data);
    }

 
}
