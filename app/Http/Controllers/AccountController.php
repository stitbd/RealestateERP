<?php
namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Fund;
use App\Models\FundLog;
use App\Models\Project;
use App\Models\VendorDue;
use App\Models\SupplierDue;
use App\Models\RequisitionPayment;
use Illuminate\Http\Request;
use Session;
use PDF;

class AccountController extends Controller
{
    public function daily_status(Request $request)
    {
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'daily-status';
        
        $fund_log = FundLog::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company','sapplier_payment','vendor_payment','salary_payment','requisition_payment','diposit','fund','expense');
        
        
        $where = array();
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $fund_log->where('fund_id','=',$request->fund_id);
        }
        
        if($request->type != null){
            $where['type'] = $request->type;
            $fund_log->where('payment_type','=',$request->type);
        }
        if($request->start_date && $request->end_date){
            if($request->start_date != null){
                $where['start_date'] = $request->start_date;
                $fund_log->where('transection_date','>=',$request->start_date);
            }
            if($request->end_date != null){
                $where['end_date'] = $request->end_date;
                $fund_log->where('transection_date','<=',$request->end_date);
            }
        }
        else{
            $fund_log->where('transection_date','=',date('Y-m-d'));
        }
        $fund_log = $fund_log->paginate(20);
        $fund_log->appends($where);
        $data['fund_log']               = $fund_log;
        $data['fund_data']              = Fund::where(['status'=>1])->get();

        return view('account.report.daily_status',$data);
    }

    public function daily_status_print (Request $request)
    {
        $fund_log = FundLog::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company','sapplier_payment','vendor_payment','salary_payment','requisition_payment','diposit','fund','expense');
        
        
        $where = array();
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $fund_log->where('fund_id','=',$request->fund_id);
        }
        
        if($request->type != null){
            $where['type'] = $request->type;
            $fund_log->where('payment_type','=',$request->type);
        }
        if($request->start_date && $request->end_date){
            if($request->start_date != null){
                $where['start_date'] = $request->start_date;
                $fund_log->where('transection_date','>=',$request->start_date);
            }
            if($request->end_date != null){
                $where['end_date'] = $request->end_date;
                $fund_log->where('transection_date','<=',$request->end_date);
            }
        }
        else{
            $fund_log->where('transection_date','=',date('Y-m-d'));
        }
        $fund_log = $fund_log->get();
        $data['fund_log']               = $fund_log;
        return view('account.report.daily_status_print',$data);
    }

    function daily_status_pdf(Request $request){
        $fund_log = FundLog::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company','sapplier_payment','vendor_payment','salary_payment','requisition_payment','diposit','fund','expense');
        
        
        $where = array();
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $fund_log->where('fund_id','=',$request->fund_id);
        }
        
        if($request->type != null){
            $where['type'] = $request->type;
            $fund_log->where('payment_type','=',$request->type);
        }
        if($request->start_date && $request->end_date){
            if($request->start_date != null){
                $where['start_date'] = $request->start_date;
                $fund_log->where('transection_date','>=',$request->start_date);
            }
            if($request->end_date != null){
                $where['end_date'] = $request->end_date;
                $fund_log->where('transection_date','<=',$request->end_date);
            }
        }
        else{
            $fund_log->where('transection_date','=',date('Y-m-d'));
        }
        $fund_log = $fund_log->get();
        $data['fund_log']               = $fund_log;

        $pdf = PDF::loadView('account.report.daily_status_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('daily-status_'.$string.'.pdf');
    }

    public function project_received(Request $request)
    {
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'project-received';
        $data['fund_data']              = Fund::where(['status'=>1])->get();
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->get();

        $requisition_payment  = RequisitionPayment::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('details','project','fund','company');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisition_payment->where('project_id','=',$request->project_id);
        }
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $requisition_payment->where('fund_id','=',$request->fund_id);
        }
        if($request->start_date && $request->end_date){
            if($request->start_date != null){
                $where['start_date'] = $request->start_date;
                $requisition_payment->where('payment_date','>=',$request->start_date);
            }
            if($request->end_date != null){
                $where['end_date'] = $request->end_date;
                $requisition_payment->where('payment_date','<=',$request->end_date);
            }
        }
        $requisition_payment = $requisition_payment->paginate(20);
        $requisition_payment = $requisition_payment->appends($where);
        $data['requisition_payment'] = $requisition_payment;
        //dd($requisition_payment);
        return view('account.report.project_received',$data);
    }

    function project_received_print(Request $request){
        $requisition_payment  = RequisitionPayment::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('details','project','fund','company');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisition_payment->where('project_id','=',$request->project_id);
        }
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $requisition_payment->where('fund_id','=',$request->fund_id);
        }
        if($request->start_date && $request->end_date){
            if($request->start_date != null){
                $where['start_date'] = $request->start_date;
                $requisition_payment->where('payment_date','>=',$request->start_date);
            }
            if($request->end_date != null){
                $where['end_date'] = $request->end_date;
                $requisition_payment->where('payment_date','<=',$request->end_date);
            }
        }
        $requisition_payment = $requisition_payment->get();
        $data['requisition_payment'] = $requisition_payment;

        return view('account.report.project_received_print',$data);
    }
    
    function project_received_pdf(Request $request){
        $fund_log = FundLog::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company','sapplier_payment','vendor_payment','salary_payment','requisition_payment','diposit','fund','expense');
        
        
        $where = array();
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $fund_log->where('fund_id','=',$request->fund_id);
        }
        
        if($request->type != null){
            $where['type'] = $request->type;
            $fund_log->where('payment_type','=',$request->type);
        }
        if($request->start_date && $request->end_date){
            if($request->start_date != null){
                $where['start_date'] = $request->start_date;
                $fund_log->where('transection_date','>=',$request->start_date);
            }
            if($request->end_date != null){
                $where['end_date'] = $request->end_date;
                $fund_log->where('transection_date','<=',$request->end_date);
            }
        }
        else{
            $fund_log->where('transection_date','=',date('Y-m-d'));
        }
        $fund_log = $fund_log->get();
        $data['fund_log']               = $fund_log;

        $pdf = PDF::loadView('account.report.project_received_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('project-received_'.$string.'.pdf');
    }


    function completion_project_received(Request $request){
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'completion-project-received';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->get();

        $requisition_payment  = Bill::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('project','company');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisition_payment->where('project_id','=',$request->project_id);
        }
        
        if($request->start_date && $request->end_date){
            if($request->start_date != null){
                $where['start_date'] = $request->start_date;
                $requisition_payment->where('bill_date','>=',$request->start_date);
            }
            if($request->end_date != null){
                $where['end_date'] = $request->end_date;
                $requisition_payment->where('bill_date','<=',$request->end_date);
            }
        }
        $requisition_payment = $requisition_payment->paginate(20);
        $requisition_payment = $requisition_payment->appends($where);
        $data['bills'] = $requisition_payment;
        //dd($requisition_payment);
        return view('account.report.completion_project_received',$data);
    }


    function completion_project_received_print(Request $request){
        $requisition_payment  = Bill::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('project','company');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisition_payment->where('project_id','=',$request->project_id);
        }
        
        if($request->start_date && $request->end_date){
            if($request->start_date != null){
                $where['start_date'] = $request->start_date;
                $requisition_payment->where('bill_date','>=',$request->start_date);
            }
            if($request->end_date != null){
                $where['end_date'] = $request->end_date;
                $requisition_payment->where('bill_date','<=',$request->end_date);
            }
        }
        $requisition_payment = $requisition_payment->get();
        $data['bills'] = $requisition_payment;

        return view('account.report.completion_project_received_print',$data);
    }

    function completion_project_received_pdf(Request $request){
        $requisition_payment  = Bill::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('project','company');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisition_payment->where('project_id','=',$request->project_id);
        }
        
        if($request->start_date && $request->end_date){
            if($request->start_date != null){
                $where['start_date'] = $request->start_date;
                $requisition_payment->where('bill_date','>=',$request->start_date);
            }
            if($request->end_date != null){
                $where['end_date'] = $request->end_date;
                $requisition_payment->where('bill_date','<=',$request->end_date);
            }
        }
        $requisition_payment = $requisition_payment->get();
        $data['bills'] = $requisition_payment;


        $pdf = PDF::loadView('account.report.completion_project_received_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('completion_project_received_'.$string.'.pdf');
    }

    function payable_due_amount(){
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'payable-due-amount';
        
        $data['vendor_due_data']        = VendorDue::where(['company_id'=>Session::get('company_id')])->with('vendor','project')->get();
        $data['due_data']               = SupplierDue::with('company','project','supplier')->where('due_amount','!=',0)->where(['company_id'=>Session::get('company_id')])->get();
        return view('account.report.payable_due_amount',$data);
    }

    function payable_due_amount_print(){
        $data['vendor_due_data']        = VendorDue::where(['company_id'=>Session::get('company_id')])->with('vendor','project')->get();
        $data['due_data']               = SupplierDue::with('company','project','supplier')->where('due_amount','!=',0)->where(['company_id'=>Session::get('company_id')])->get();
        //return view('account.report.payable_due_amount',$data);
        return view('account.report.payable_due_amount_print',$data);
    }
    function payable_due_amount_pdf(){
        $data['vendor_due_data']        = VendorDue::where(['company_id'=>Session::get('company_id')])->with('vendor','project')->get();
        $data['due_data']               = SupplierDue::with('company','project','supplier')->where('due_amount','!=',0)->where(['company_id'=>Session::get('company_id')])->get();
    
        $pdf = PDF::loadView('account.report.payable_due_amount_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('payable_due_amount_'.$string.'.pdf');
    }
}