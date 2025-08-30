<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;

class LoginController extends Controller
{
    //
    function logout(){
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }
}
