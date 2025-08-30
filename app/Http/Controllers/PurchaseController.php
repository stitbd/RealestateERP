<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\Models\Bank;
use App\Models\Fund;
use App\Models\Item;
use App\Models\Stock;
use App\Models\FundLog;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\BankAccount;
use App\Models\SupplierDue;
use Illuminate\Http\Request;
use App\Models\PaymentDetails;
use App\Models\PurchaseDetails;
use App\Models\FundCurrentBalance;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{

    public function index(Request $request)
    {
        $data['main_menu']              = 'purchase';
        $data['child_menu']             = 'purchase';
        $purchase = Purchase::where(['company_id'=>Session::get('company_id')])->with('company','project','supplier');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $purchase->where('project_id','=',$request->project_id);
        }
        if($request->supplier_id != null){
            $where['supplier_id'] = $request->supplier_id;
            $purchase->where('supplier_id','=',$request->supplier_id);
        }
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $purchase->where('purchase_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $purchase->where('purchase_date','<=',$request->end_date);
        }
        $purchase = $purchase->paginate(20);
        $purchase->appends($where);
        $data['purchase']               = $purchase;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['supplier_data']          = Supplier::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();

        return view('purchase.purchase_list',$data);
    }

    public function print(Request $request)
    {
        $purchase = Purchase::where(['company_id'=>Session::get('company_id')])->with('company','project','supplier');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $purchase->where('project_id','=',$request->project_id);
        }
        if($request->supplier_id != null){
            $where['supplier_id'] = $request->supplier_id;
            $purchase->where('supplier_id','=',$request->supplier_id);
        }
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $purchase->where('purchase_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $purchase->where('purchase_date','<=',$request->end_date);
        }
        $purchase = $purchase->get();
        $data['purchase']               = $purchase;
        return view('purchase.purchase_list_print',$data);
    }

    public function pdf(Request $request)
    {
        $purchase = Purchase::where(['company_id'=>Session::get('company_id')])->with('company','project','supplier');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $purchase->where('project_id','=',$request->project_id);
        }
        if($request->supplier_id != null){
            $where['supplier_id'] = $request->supplier_id;
            $purchase->where('supplier_id','=',$request->supplier_id);
        }
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $purchase->where('purchase_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $purchase->where('purchase_date','<=',$request->end_date);
        }
        $purchase = $purchase->get();
        $data['purchase']               = $purchase;

        $pdf = PDF::loadView('purchase.purchase_list_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('purchase-list_'.$string.'.pdf');
    }

    public function create()
    {
        $data['main_menu']              = 'purchase';
        $data['child_menu']             = 'add-purchase';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['item_data']              = Item::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();
        $data['supplier_data']          = Supplier::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();
        $data['fund_data']              = Fund::where(['status'=>1])->get();
        $data['banks']                  = Bank::get();
        $data['accounts']               = BankAccount::where('company_id',session()->get('company_id'))->get();


        return view('purchase.add_purchase',$data);
    }


    public function store(Request $request)
    {
        //dd($request);
        $project_id         = $request->project_id;
        $supplier_id        = $request->supplier_id;
        $purchase_date      = $request->purchase_date;
        $item_id            = $request->item_id;
        $qty                = $request->qty;
        $unit_price         = $request->unit_price;
        $discount_per       = $request->discount_per;
        $discount_flat      = $request->discount_flat;
        $vat                = $request->vat;
        $tax                = $request->tax;
        $att                = $request->att;
        $shipping_cost      = $request->shipping_cost;

        if($supplier_id != null && $purchase_date != null){
            $date = date("Y");
            $randomNumber = rand(100, 999);
            $voucherNumber = "VHR-" . $date . "-" . $randomNumber;
            $refNo         = "PO-" . $date . "-" . $randomNumber;
            $model = new Purchase();
            $model->voucher_no      = $voucherNumber;
            $model->reference_no    = $refNo;
            $model->company_id      = Session::get('company_id');
            $model->project_id      = $project_id;
            $model->supplier_id     = $supplier_id;
            $model->purchase_date   = $purchase_date;
            $model->invoice_amount  = 0;
            $model->status          = 2;
            $model->created_by      = auth()->user()->id;
            $model->save();

            $purchase_id = $model->id;
            $invoice_amount = 0;
            for($i=0; count($item_id)>$i; $i++){
                if($item_id[$i] != null && $qty[$i] != null && $unit_price[$i] != null){
                    $details  = new PurchaseDetails();
                    $details->purchase_id   = $purchase_id;
                    $details->item_id       = $item_id[$i];
                    $details->unit_price    = $unit_price[$i];
                    $details->qty           = $qty[$i];
                    $details->discount_per  = $discount_per[$i];
                    $details->discount_flat = $discount_flat[$i];
                    $discount = 0;
                    if($discount_per[$i] != null){
                        $discount += (((($unit_price[$i]*$qty[$i])/100)*$discount_per[$i]));
                    }
                    if($discount_flat[$i] != null){
                        $discount += $discount_flat[$i];
                    }
                    $details->discount      = $discount;
                    $details->vat           = $vat[$i];
                    $details->tax           = $tax[$i];
                    $details->att           = $att[$i];
                    $details->shipping_cost = $shipping_cost[$i];
                    $amount = ($unit_price[$i]*$qty[$i])-$discount+$vat[$i]+$tax[$i]+$att[$i]+$shipping_cost[$i];
                    $details->amount        = $amount;
                    $details->created_by    = auth()->user()->id;
                    $details->status        = 2;
                    $details->save();

                    $invoice_amount         += $amount;

                    $stock = Stock::where(['status'=>1,'company_id'=>Session::get('company_id'), 'project_id'=>$project_id, 'item_id'=>$item_id[$i]])->first();
                    if($stock){
                        $stock->qty = $stock->qty + $qty[$i];
                        $stock->save();
                    }
                    else{
                        $stock = new Stock();
                        $stock->company_id          = Session::get('company_id');
                        $stock->project_id          = $project_id;
                        $stock->item_id             = $item_id[$i];
                        $stock->qty                 = $qty[$i];
                        $stock->status              = '1';
                        $stock->created_by          = auth()->user()->id;
                        $stock->save();
                    }
                }
            }

            $update_model = Purchase::find($purchase_id);
            $update_model->invoice_amount = $invoice_amount;
            $update_model->save();

            $supplier_due = SupplierDue::where(['company_id'=>Session::get('company_id'),'supplier_id'=>$request->supplier_id,'project_id'=>$project_id])->first();
            if($supplier_due != null){
                $supplier_due->due_amount = $supplier_due->due_amount+$invoice_amount;
                $supplier_due->save();
            }
            else{
                $supplier_due = new SupplierDue();
                $supplier_due->company_id = Session::get('company_id');
                $supplier_due->project_id = $request->project_id;
                $supplier_due->supplier_id = $request->supplier_id;
                $supplier_due->due_amount = $invoice_amount;
                $supplier_due->save();
            }

            $msg="Purchase Updated.";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Invalid Request.";
            $request->session()->flash('warning',$msg);
        }

        return redirect('purchase')->with('status', $msg);
    }


    public function purchase_details($id)
    {
        $data['purchase_info'] = Purchase::where(['id'=>$id])->with('company','project','supplier')->get()->first();
        $data['purchase_details'] = PurchaseDetails::where(['purchase_id'=>$id])->with('item')->get();
        //dd($data['purchase_details']);
        return view('purchase.purchase_details',$data);
    }

    function change_purchase_status(Request $request,$id='',$status='1'){
        if(!empty($id) && !empty($status)){
            $model = Purchase::find($id);
            $model->status = $status;
            $model->updated_by = auth()->user()->id;
            $model->save();

            PurchaseDetails::where(['purchase_id'=>$id])->update(['status'=>$status,'updated_by'=>auth()->user()->id]);

            $msg="Purchase Updated..!";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Invalid Request..!";
            $request->session()->flash('warning',$msg);
        }

        return redirect('purchase');
    }

    public function generateSupplierPayment($id){
        $data['purchase_info']    = Purchase::where(['id'=>$id])->with('company','project','supplier')->get()->first();
        $data['purchase_details'] = PurchaseDetails::where(['purchase_id'=>$id])->with('item')->get();
        $data['fund']             = Fund::all();
        $data['banks']            = Bank::all();
        $data['accounts']         = BankAccount::where(['company_id'=>session()->get('comapny_id')])->get();
        $payment                  = Payment::latest()->first();
        $purchase_payment         = Payment::where('purchase_id',$id)->sum('amount');
        if($payment){
            $data['payment_id'] = $payment->id;
        }
        if($purchase_payment >0){
            $data['due'] = $data['purchase_info']->invoice_amount - $purchase_payment;
        }else{
            $data['due'] = $data['purchase_info']->invoice_amount;
        }
        return view('purchase.generate_payment',$data);
    }


    public function generalPaymentStore(Request $request){
        $payment = Payment::latest()->first();
        $date = date('Y');
        if($request->amount <= $request->purchase_amount){

            $payment_data = ([
                'purchase_id'   => $request->purchase_id,
                'supplier_id'   => $request->supplier_id,
                'company_id'    => session()->get('company_id'),
                'code_no'       => $request->code_no,
                'voucher_no'    => $payment?'PAY-'.$date.$payment->id+1:'PAY-'.$date.'1',
                'fund_id'       => $request->fund_id,
                'payment_date'  => $request->date,
                'payment_type'  => $request->payment_type,
                'amount'        => $request->amount,
                'remarks'       => $request->remarks,
                'status'        => 1,
                'created_by'    => Auth::user()->id,
            ]);



            $bankAccount = BankAccount::where(['id'=>$request->account_id,'company_id'=> Session::get('company_id')])->first();

            if($bankAccount){
                    $bankAccount->current_balance -= (float)$request->amount;
                    $bankAccount->update();

                    $bank_fund = FundCurrentBalance::where(['fund_id'=>$request->fund_id,'status'=>1,'company_id'=> Session::get('company_id')])->where('bank_id',$request->bank_id)->first();
                    if($bank_fund){
                        $bank_fund->amount -=   $request->amount;
                        $bank_fund->updated_by = auth()->user()->id;
                        $bank_fund->update();
                    }else{
                        $fund_current_balance = new FundCurrentBalance();
                        $fund_current_balance->fund_id = $request->fund_id;
                        $fund_current_balance->bank_id = $request->bank_id;
                        $fund_current_balance->company_id = Session::get('company_id');
                        $fund_current_balance->amount = $request->amount;
                        $fund_current_balance->status = '1';
                        $fund_current_balance->created_by = auth()->user()->id;
                        $fund_current_balance->save();
                    }
            }else{
                $fund = FundCurrentBalance::where(['fund_id'=>$request->fund_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();
                if($fund != null){
                    $fund->amount -= $request->amount;
                    $fund->updated_by = auth()->user()->id;
                    // dd($fund);
                    $fund->update();
                }
                else{
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $request->fund_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $request->amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }

            $payment = Payment::create($payment_data);
            // dd($payment);

            $details = new PaymentDetails();

            if($request->attachment != null){
                $newImageName =  time().'_income.'.$request->attachment->extension();
                $request->attachment->move(public_path('attachment'),$newImageName);
                $details->attachment = $newImageName;
            }

            $details->payment_id             =  $payment->id;
            $details->bank_id                =  $request->bank_id;
            $details->account_id             =  $request->account_id;
            $details->cheque_number          =  $request->cheque_no;
            $details->mobile_no              =  $request->mobile_no;
            $details->save();

            $fund_log                       = new FundLog();
            $fund_log->company_id           = session()->get('company_id');
            $fund_log->fund_id              = $request->fund_id;
            $fund_log->type                 = '2';
            $fund_log->amount               = $request->amount;
            $fund_log->transection_type     = 'purchase_payment';
            $fund_log->transection_id       = $payment->id;
            $fund_log->transection_date     = $request->date;
            $fund_log->payment_type         = $request->payment_type;
            $fund_log->status               = '1';
            $fund_log->created_by           = auth()->user()->id;
            $fund_log->save();

            $supplier_due = SupplierDue::where('supplier_id',$request->supplier_id)->first();

            if($supplier_due){
                $supplier_due->due_amount -=  $request->amount;
                $supplier_due->update();
            }

            $msg="Payment Generated.";
            $request->session()->flash('message',$msg);
        }else{
            $msg="Payment Not Generated.";
            $request->session()->flash('message',$msg);
        }
        return redirect()->back()->with('status', $msg);
    }

    public function purchasePaymentDetails($id){
        $payment = Payment::where('purchase_id',$id)->with('company','supplier','payment_details','purchase')->get();
        return view('purchase.payment_details',compact('payment'));
    }

}
