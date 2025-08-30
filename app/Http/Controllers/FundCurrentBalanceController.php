<?php

namespace App\Http\Controllers;

use App\Models\FundCurrentBalance;
use Illuminate\Http\Request;

class FundCurrentBalanceController extends Controller
{
    
    public function index()
    {
        $data['main_menu']      = 'fund';
        $data['child_menu']     = 'balance-list';
        $data['fund_data']      = FundCurrentBalance::where('company_id',session()->get('company_id'))->get();
        return view('fund.fund_current_balance',$data);
        
    }


}
