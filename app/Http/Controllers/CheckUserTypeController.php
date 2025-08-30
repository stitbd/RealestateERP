<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserTypeController extends Controller
{
    public function customerLogin()
    {
        return view('auth.customer_login');
    }

    public function submitLogin(Request $request)
    {
        $customer = Customer::where('email', $request->input('email'))->first();
        // dd($customer);
        if ($customer && $customer->password === $request->input('password')) {
            $request->session()->put('customer_id', $customer->id);
            return redirect()->route('customer_info');
        } else {
            return view('auth.customer_login');
        }
    }

    public function logout()
    {
        Auth::logout();
        $msg = "Logged Out Successfully.";
        return view('auth.customer_login')->with('status', $msg);
    }
}
