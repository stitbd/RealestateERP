<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Company;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Session;
use PDF;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function categoryIndex(){
        $data['main_menu']  = 'inventory';
        $data['child_menu'] = 'item-category';
        $data['categories'] = ItemCategory::all();
        return view('basic_settings.item_category',$data);
    }

    public function storeCategory(Request $request) {
        $category = new ItemCategory;
        $category->category_name = $request->category_name;
        $category->created_by  = auth()->user()->id;
        $category->company_id  = Session::get('company_id');
        $category->save();
        $msg = "Category Created Successfully";
        return redirect()->back()->with('status',$msg);
    }

    public function updateCategory(Request $request,$id) {
        $category = ItemCategory::find($id);
        $category->category_name = $request->edit_category_name;
        $category->update();
        $msg = "Category Updated Successfully";
        return redirect()->back()->with('status',$msg);
    }

    public function statusChange($id){
        $model                  = ItemCategory::find($id);
        if($model->status == 1){
            $status = 2;
            $model->update(['status' => $status]);
        }else{
            $status = 1;
            $model->update(['status' => $status]);
        }
        $msg="Item Status Updated.";

        return redirect()->route('item-category')->with('status', $msg);
    }

    public function index()
    {
        $data['main_menu']              = 'inventory';
        $data['child_menu']             = 'item-list';
        $data['item_data']              = Item::with('category')->where(['company_id'=>Session::get('company_id')])->with('company')->paginate(20);
        $data['item_category']          = ItemCategory::where(['company_id'=>Session::get('company_id')])->paginate(20);
        $item                           = Item::where(['company_id'=>Session::get('company_id')])->latest()->first();
        if($item){
           $data['item_code']  = $item->id;
        }
        $data['company_data']           = Company::where(['status'=>1])->get();

        return view('basic_settings.item',$data);
    }

    function print(){
        $data['title']                  = 'Item List || '.Session::get('company_name');
        $data['item_data']              = Item::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        return view('basic_settings.item_print',$data);
    }

    function pdf(){
        $data['title']                  = 'Item List || '.Session::get('company_name');
        $data['item_data']              = Item::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        
        $pdf = PDF::loadView('basic_settings.item_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('item-list_'.$string.'.pdf');
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required'
        ]);
        
        
        $model = new Item();
        $model->name                = $request->post('name');
        $model->category_id         = $request->post('category_id');
        $model->code_no             = $request->post('code_no');
        $model->company_id          = Session::get('company_id');
        $model->size_type           = $request->post('size_type');
        $model->unit                = $request->post('unit');
        $model->created_by          = auth()->user()->id;
        // dd($model);
        $model->save();

        $msg = "Item Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('item-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Item::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Item Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('item-list')->with('status', $msg);
    }

    function update(Request $request, $id){
    // dd($request->all());
        
        $model = Item::with('category')->find($id);
        $model->name                = $request->name;
        $model->size_type           = $request->size_type;
        $model->category_id         = $request->category;
        $model->code_no             = $request->code_no;
        $model->unit                = $request->unit;
        // $model->asset_type          = $request->post('asset_type');
        $model->updated_by          = auth()->user()->id;
        
        $model->save();

        $msg="Item Updated.";
        //$request->session()->flash('message',$msg);

        return redirect()->route('item-list')->with('status', $msg);
    }



}
