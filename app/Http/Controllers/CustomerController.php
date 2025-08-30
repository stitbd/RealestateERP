<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LandSale;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CustomerController extends Controller
{
    public function customer_info(){
        $customerId = session('customer_id');
        $customerdata = Customer::find($customerId );
        $landSaledata = LandSale::with('plot')->where('customer_id',$customerId)->first();
        $installmentdata = Installment::with('landSale')->where('land_sale_id',$landSaledata->id)->first();
        $installmentDate = Carbon::parse($installmentdata->first_installment_date);
        //  dd($installmentdata);
        return view('auth.customer_info',compact('customerdata','landSaledata','installmentdata','installmentDate'));
    }
}
