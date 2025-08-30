<?php

namespace App\Http\Controllers;

use App\Models\VendorDue;
use App\Models\Vendor;
use App\Models\Project;
use Illuminate\Http\Request;
use Session;
Use PDF;

class VendorDueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['main_menu']              = 'vendor';
        $data['child_menu']             = 'vendor-due-list';
        $data['vendor_due_data']        = VendorDue::where(['company_id'=>Session::get('company_id')])->with('vendor','project')->get();

        return view('vendor.vendor_due',$data);
    }

    function print(){
        $data['title']                  = 'Vendor Due List || '.Session::get('company_name');
        $data['vendor_due_data']        = VendorDue::where(['company_id'=>Session::get('company_id')])->with('vendor','project')->get();
        return view('vendor.vendor_due_print',$data);
    }

    function pdf(){
        $data['title']                  = 'Vendor Due List || '.Session::get('company_name');
        $data['vendor_due_data']        = VendorDue::where(['company_id'=>Session::get('company_id')])->with('vendor','project')->get();

        $pdf = PDF::loadView('vendor.vendor_due_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('vendor-due_'.$string.'.pdf');
    }
}
