<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Supplier;
use App\Models\Company;
use App\Models\Fund;
use App\Models\FundCurrentBalance;
use App\Models\Investment;
use App\Models\SupplierDue;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Session;
use PDF;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['main_menu']              = 'supplier';
        $data['child_menu']             = 'supplier-list';
        $data['supplier_data']          = Supplier::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['company_data']           = Company::where(['status'=>1])->get();
// dd($data['supplier_data']);
        return view('basic_settings.supplier',$data);
    }

    function print(){
        $data['title']                  = 'Supplier List || '.Session::get('company_name');
        $data['supplier_data']           = Supplier::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        return view('basic_settings.supplier_print',$data);
    }

    function pdf(){
        $data['title']                  = 'Supplier List || '.Session::get('company_name');
        $data['supplier_data']           = Supplier::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        $pdf = PDF::loadView('basic_settings.supplier_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('supplier-list_'.$string.'.pdf');
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required',
            'contact_person_name'   => 'required',
            'contact_person_mobile' => 'required',
            'mobile'                => 'required'
        ]);
        
        
        $model = new Supplier();
        $model->name                = $request->post('name');
        $model->contact_person_name  = $request->post('contact_person_name');
        $model->contact_person_mobile  = $request->post('contact_person_mobile');
        $model->company_id          = Session::get('company_id');
        $model->mobile                = $request->post('mobile');
        $model->address                = $request->post('address');
        $model->email                = $request->post('email');
        $model->note                = $request->post('note');
        $model->other_details          = $request->post('other_details');
        $model->created_by             = auth()->user()->id;
        $model->save();

        $msg = "Supplier Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('supplier-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Supplier::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Vendor Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('supplier-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required',
            'contact_person_name'   => 'required',
            'contact_person_mobile'   => 'required',
            'mobile'                => 'required'
        ]);
        //dd($request->post());
        
        $model = Supplier::where('id', '=', $request->post('id'))->first();
        $model->name                = $request->post('name');
        $model->contact_person_name	  = $request->post('contact_person_name');
        $model->contact_person_mobile  = $request->post('contact_person_mobile');
        $model->mobile                = $request->post('mobile');
        $model->address               = $request->post('address');
        $model->email                 = $request->post('email');
        $model->note                  = $request->post('note');
        $model->other_details         = $request->post('other_details');
        $model->updated_by            = auth()->user()->id;
        
        $model->save();

        $msg="Supplier Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('supplier-list')->with('status', $msg);
    }

    //Work Order
    public function work_order_index(Request $request)
    {
        $data['main_menu']      = 'supplier';
        $data['child_menu']     = 'work-order-list';
        $data['suppliers']      = Supplier::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $bank_garanties         = WorkOrder::with('supplier');
        $where = array();
        if ($request->supplier_id) {
            $where['supplier_id'] = $request->supplier_id;
            $bank_garanties = $bank_garanties->where('supplier_id', '=', $request->supplier_id);
        }
        if ($request->type) {
            $where['type'] = $request->type;
            $bank_garanties = $bank_garanties->where('type', '=', $request->type);
        }
       
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '<=', $request->end_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id', 'desc')->whereNotIn('status',[4])->paginate(20);
        $bank_garanties->appends($where);
        $data['work_orders'] = $bank_garanties;

        return view('basic_settings.work_order_list', $data);
    }

    public function create_work_order()
    {
        $data['main_menu']           = 'supplier';
        $data['child_menu']          = 'work-order-create';
        $data['suppliers']      = Supplier::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        return view('basic_settings.work_order_create', $data);
    }

    public function getTypeWiseName(Request $request)
    {
        $type = $request->input('type');
        $typeName = '';
        
        if ($type === 'Service') {
            $typeName = 'Service Name';
        } elseif ($type === 'Product') {
            $typeName = 'Product Name';
        }
    
        return response()->json(['typeName' => $typeName]);
    }


    public function store_work_order(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'supplier_id' => 'required',
        ]);

        $order = WorkOrder::latest()->first();

        $year = date('Y');

        if($order){
            $invoice = 'WO-'.$year.'-'.$order->id + 1;
        }else{
            $invoice = 'WO-'.$year.'-'.'001';
        }

        $work_order_data = [
            'company_id'  => session()->get('company_id'),
            'supplier_id' => $request->supplier_id,
            'invoice_no'  => $invoice,
            'date'        => $request->date,
            'type'        => $request->type,
            'name'        => $request->service_name ? $request->service_name : $request->product_name ,
            'unit_price'  => $request->unit_price,
            'total_price' => $request->total_price,
            'amount'      => $request->total_price,
            'note'        => $request->note,
            
        ];
        
        if ($request->type == 'Product') {
            $work_order_data['p_quantity'] = $request->p_quantity;
            $work_order_data['s_quantity'] = null;
        } else {
            $work_order_data['s_quantity'] = $request->s_quantity;
        }
        
        $work_order = WorkOrder::create($work_order_data);
        

        $supplierDue = SupplierDue::where('company_id',session()->get('company_id'))->where('supplier_id',$request->supplier_id)->first();

        if($supplierDue){
            $supplierDue->due_amount += $request->total_price;
            $supplierDue->updated_by = auth()->user()->id;
            $supplierDue->update();
        }else{
            $supplierDue = new SupplierDue;
            $supplierDue->company_id = session()->get('company_id');
            $supplierDue->supplier_id = $request->supplier_id;
            $supplierDue->due_amount = $request->total_price;
            $supplierDue->status     = '1';
            $supplierDue->created_by = auth()->user()->id;
            $supplierDue->save();
        }

        if ($work_order) {
            $msg = "Work Order Inserted.";
            $request->session()->flash('message', $msg);
            return redirect('work-order-list')->with('status', $msg);
        } else {
            $msg = "Work Order Insertion failed.";
            $request->session()->flash('warning', $msg);
            return redirect()->back()->with('warning', $msg);
        }

       

    }

    public function editWorkOrder($id){
        $data['main_menu']           = 'supplier';
        $data['child_menu']          = 'work-order-list';
        $data['suppliers']           = Supplier::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['work_order']          = WorkOrder::findOrFail($id);
        return view('basic_settings.work_order_edit',$data);
    }


    public function updateWorkOrder(Request $request, $id){
        $request->validate([
            'supplier_id' => 'required',
        ]);


        $work_order = WorkOrder::findOrFail($id);

        $work_order_data = [
            'supplier_id' => $request->supplier_id,
            'date' => $request->date,
            'type' => $request->type,
            'name' => $request->service_name ?? $request->product_name ,
            'unit_price' => $request->unit_price,
            'total_price' => $request->total_price,
            'amount'      => $request->total_price,
            'note' => $request->note,
        ];
        
        if ($request->type == 'Product') {
            $work_order_data['p_quantity'] = $request->p_quantity;
            $work_order_data['s_quantity'] = null;
        } else {
            $work_order_data['s_quantity'] = $request->s_quantity;
        }

        $supplierDue = SupplierDue::where('company_id',session()->get('company_id'))->where('supplier_id',$request->supplier_id)->first();
        // dd($supplierDue);

        if($supplierDue != null){
            if($work_order->total_price  < $request->total_price){
                $supplierDue->due_amount += ($request->total_price - $work_order->total_price);
            }else{
                $supplierDue->due_amount -= ($work_order->total_price - $request->total_price);
            }
            $supplierDue->updated_by = auth()->user()->id;
            $supplierDue->update();
        }else{
            $due = new SupplierDue;
            $due->company_id = session()->get('company_id');
            $due->supplier_id = $request->supplier_id;
            $due->due_amount = $request->total_price;
            $due->status     = '1';
            $due->created_by = auth()->user()->id;
            $due->save();
        }
        
        $work_order->update($work_order_data);
        

        if ($work_order) {
            $msg = "Work Order Updated.";
            return redirect('work-order-list')->with('status', $msg);
        } else {
            $msg = "Work Order Update failed.";
            return redirect()->back()->with('warning', $msg);
        } 
    }

    public function work_order_print(Request $request)
    {
        $data['suppliers']      = Supplier::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $bank_garanties         = WorkOrder::with('supplier');
        $where = array();
        if ($request->supplier_id) {
            $where['supplier_id'] = $request->supplier_id;
            $bank_garanties = $bank_garanties->where('supplier_id', '=', $request->supplier_id);
        }
        if ($request->type) {
            $where['type'] = $request->type;
            $bank_garanties = $bank_garanties->where('type', '=', $request->type);
        }
       
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '<=', $request->end_date);
        }
        $bank_garanties = $bank_garanties->orderBy('id', 'desc')->get();
        $data['work_orders'] = $bank_garanties;

        return view('basic_settings.work_order_print', $data);
    }


    public function orderInvoice($id){
        $work_order = WorkOrder::where('company_id',session()->get('company_id'))->where('id',$id)->first();
        return view('basic_settings.work_order_details', compact('work_order'));
    }


    function change_purchase_status(Request $request,$id='',$status='1'){
        if(!empty($id) && !empty($status)){
            $model = WorkOrder::find($id);
            $model->status = $status;
            $model->updated_by = auth()->user()->id;
            $model->save();

            WorkOrder::where(['id'=>$id])->update(['status'=>$status,'updated_by'=>auth()->user()->id]);

            $msg="Order Status Updated..!";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Invalid Request..!";
            $request->session()->flash('warning',$msg);
        }

        return redirect('work-order-list');
    }

}
