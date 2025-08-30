<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\PettyCash;
use Illuminate\Http\Request;

class PettyCashController extends Controller
{
    public function index(){
        $data['main_menu'] = 'accounts';
        $data['child_menu'] = 'petty-cash';
        $company = Company::all();
        $user = User::all();
        $petty_cash = PettyCash::all();
        return view('petty_cash.manage_petty_cash',compact('company','user','petty_cash'),$data);
    }

    public function companywiseUser(Request $request){
        $company = $request->company;
        $users = User::where('company_id',$company)->get();
        return response()->json($users);
    }
    public function store(Request $request){
        $perv_cash = PettyCash::where('user_id',$request->user_id)->where('given_date','<=',$request->date)->orderByDesc('id')->first();
        $total = 0; 
        if($perv_cash){
            $total = $perv_cash->remain_amount + $request->amount;
        }
        else{
            $total = $request->amount;
        }
        
        $petty_cash = new PettyCash();
        $petty_cash->company_id = $request->company_id;
        $petty_cash->user_id = $request->user_id;
        $petty_cash->amount = $request->amount;
        $petty_cash->remain_amount = $total;
        $petty_cash->given_date = $request->date;
        $petty_cash->created_by = auth()->user()->id;
        $petty_cash->save();
        $msg="Petty Cash Added Successfully.";
        $request->session()->flash('message',$msg);
        return redirect()->back();
    }
    public function update(){

    }
}
