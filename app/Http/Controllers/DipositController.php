<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Fund;
use App\Models\Diposit;
use App\Models\FundLog;
use Illuminate\Http\Request;
use App\Models\DipositDetails; 
use App\Models\FundCurrentBalance;
use Illuminate\Support\Facades\Session;

class DipositController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'deposit-list';
        $data['fund_data']              = Fund::where(['status'=>1])->get();
        $deposit_data                   = Diposit::with('company','fund','diposit_details')->where(['status'=>1,'company_id'=>Session::get('company_id')])->orderBy('id','DESC');
        if($request->fund_id){
            $where['fund_id'] = $request->fund_id;
            $deposit_data->where('fund_id','=',$request->fund_id);
        }
        if($request->start_date){
            $where['start_date'] = $request->start_date;
            $deposit_data->whereDate('payment_date','>=',$request->start_date);
        }
        if($request->end_date){
            $where['end_date'] = $request->end_date;
            $deposit_data->whereDate('payment_date','<=',$request->end_date);
        }
        $deposit_data                   = $deposit_data->paginate(20);
        $data['deposit_data']           = $deposit_data;
        return view('account.diposit.index',$data);
    }

    public function print(Request $request)
    {
        $deposit_data                   = Diposit::with('company','fund','diposit_details')->where(['status'=>1,'company_id'=>Session::get('company_id')])->orderBy('id','DESC');
        if($request->fund_id){
            $where['fund_id'] = $request->fund_id;
            $deposit_data->where('fund_id','=',$request->fund_id);
        }
        if($request->start_date){
            $where['start_date'] = $request->start_date;
            $deposit_data->whereDate('payment_date','>=',$request->start_date);
        }
        if($request->end_date){
            $where['end_date'] = $request->end_date;
            $deposit_data->whereDate('payment_date','<=',$request->end_date);
        }
        $deposit_data                   = $deposit_data->get();
        $data['deposit_data']           = $deposit_data;
        return view('account.diposit.print',$data);
    }

    public function pdf(Request $request)
    {
        $deposit_data                   = Diposit::with('company','fund','diposit_details')->where(['status'=>1,'company_id'=>Session::get('company_id')])->orderBy('id','DESC');
        if($request->fund_id){
            $where['fund_id'] = $request->fund_id;
            $deposit_data->where('fund_id','=',$request->fund_id);
        }
        if($request->start_date){
            $where['start_date'] = $request->start_date;
            $deposit_data->whereDate('payment_date','>=',$request->start_date);
        }
        if($request->end_date){
            $where['end_date'] = $request->end_date;
            $deposit_data->whereDate('payment_date','<=',$request->end_date);
        }
        $deposit_data                   = $deposit_data->get();
        $data['deposit_data']           = $deposit_data;

        $pdf = PDF::loadView('account.diposit.print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('deposit-list_'.$string.'.pdf');
    }

    public function create()
    {
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'deposit-entry';
        $data['fund_data']              = Fund::where(['status'=>1])->get();

        return view('account.diposit.create',$data);
    }

    public function store(Request $request)
    {
        if($request->fund_id && $request->amount){
            $model                      = new Diposit();
            $model->company_id          = Session::get('company_id');
            $model->fund_id             = $request->fund_id;
            $model->payment_date        = $request->payment_date;
            $model->payment_type        = $request->payment_type;
            $model->amount              = $request->amount;$model->status          = '1';
            $model->created_by          = auth()->user()->id;
            $model->save();

            $diposit_id = $model->id;

            $details                        = new DipositDetails();
            $details->diposit_id            = $diposit_id;
            $details->receiver_name         = $request->receiver_name;
            $details->mobile_no             = $request->mobile_no;
            $details->nid                   = $request->nid;
            $details->address               = $request->address;
            $details->check_number          = $request->check_number;
            $details->check_issue_date      = $request->check_issue_date;
            $details->bank_name             = $request->bank_name;
            $details->bank_account_no       = $request->bank_account_no;
            $details->account_holder_name   = $request->account_holder_name;
            $details->payment_note          = $request->payment_note;
            $details->remarks               = $request->remarks;
            if($request->attachment != null){
                $newImageName = time().'_diposit.'.$request->attachment->extension();
                $request->attachment->move(public_path('attachment'),$newImageName);

                $details->attachment = public_path('attachment/'.$newImageName);
            }

            $details->status                = '1';
            $details->created_by            = auth()->user()->id;
            $details->save();

            $fund_log                       = new FundLog();
            $fund_log->company_id           = Session::get('company_id');
            $fund_log->fund_id              = $request->fund_id;
            $fund_log->type                 = '1';
            $fund_log->amount               = $request->amount;
            $fund_log->transection_type     = 'deposit';
            $fund_log->transection_id       = $diposit_id;
            $fund_log->transection_date     = $request->payment_date;
            $fund_log->status               = '1';
            $fund_log->created_by           = auth()->user()->id;
            $fund_log->save();

            $fund = FundCurrentBalance::where(['fund_id'=>$request->fund_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();
            if($fund != null){
                $fund->amount = $fund->amount+$request->amount;
                $fund->updated_by = auth()->user()->id;
                $fund->update();
            }
            else{
                $fund = new FundCurrentBalance();
                $fund->fund_id = $request->fund_id;
                $fund->company_id = Session::get('company_id');
                $fund->amount = $request->amount;
                $fund->status = '1';
                $fund->created_by = auth()->user()->id;
                $fund->save();
            }

            $msg="Deposit Updated.";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Deposit Not Updated.";
            $request->session()->flash('warning',$msg);
        }
        return redirect('deposit-list')->with('status', $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diposit  $diposit
     * @return \Illuminate\Http\Response
     */
    public function show(Diposit $diposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diposit  $diposit
     * @return \Illuminate\Http\Response
     */
    public function edit(Diposit $diposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diposit  $diposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diposit $diposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diposit  $diposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diposit $diposit)
    {
        //
    }
}
