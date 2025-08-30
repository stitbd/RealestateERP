<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Fund;
use App\Models\Asset;
use App\Models\Company;
use App\Models\FundLog;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\AssetLost;
use App\Models\AssetGroup;
use App\Models\AssetStock;
use App\Models\AssetAssign;
use App\Models\AssetDamage;
use App\Models\AssetRevoke;
use App\Models\BankAccount;
use App\Models\CurrentAsset;
use App\Models\SupplierDue;
use Illuminate\Http\Request;
use App\Models\AssetCategory;
use App\Models\AssetPurchase;
use App\Models\LostActivityLog;
use Illuminate\Validation\Rule;
use App\Models\AssetLiquidation;
use App\Models\DamageActivityLog;
use App\Models\FundCurrentBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AssetPurchaseDetails;
use App\Models\LiquidationActivityLog;
use Illuminate\Support\Facades\Session;

class AssetController extends Controller
{
    public function assetIndex(){
        $data['main_menu']  = 'asset_management';
        $data['child_menu'] = 'current_asset';
        $data['asset']      = CurrentAsset::all();
        $asset              = CurrentAsset::latest()->first();
        if($asset){
            $data['asset_id'] = $asset->id;
        }
        return view('asset.current_asset',$data);
    }



    public function storeCurrentAsset(Request $request) {

        $request->validate([
            'name'             => 'required',
            'code_no'          => 'required|unique:current_assets',
        ]);

        $asset            = new CurrentAsset;
        $asset->name      = $request->name;
        $asset->code_no   = $request->code_no;
        $asset->location  = $request->location;
        $asset->owner     = $request->owner;
        $asset->date      = $request->date;
        $asset->status    = 1;
        $asset->created_by = auth()->user()->id;
        $asset->save();

        $msg = "Asset Created Successfully";
        return redirect()->back()->with('status',$msg);
    }



    public function updateCurrentAsset(Request $request,$id) {
        $request->validate([
            'name'             => 'required',
            'code_no'          => 'required|unique:current_assets',
        ]);

        $asset            =  CurrentAsset::find($id);
        $asset->name      = $request->name;
        $asset->code_no   = $request->code_no;
        $asset->location  = $request->location;
        $asset->owner     = $request->owner;
        $asset->date      = $request->date;
        $asset->status    = 1;
        $asset->created_by = auth()->user()->id;
        $asset->update();

        $msg = "Asset Updated Successfully";
        return redirect()->back()->with('status',$msg);
    }



    public function asset_category()
    {
        $data['main_menu']      = 'asset_management';
        $data['child_menu']     = 'asset_category_list';
        $data['asset_category']      = AssetCategory::where('company_id', Session::get('company_id'))->orderByDesc('id')->get();

        return view('asset.asset_category', $data);
    }





    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|unique:asset_categories',
        ]);


        $model = new AssetCategory();
        $model->name                = $request->post('name');
        $model->company_id           = Session::get('company_id');
        $model->save();

        $msg = "Asset Category Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect()->route('asset_category_list')->with('status', $msg);
    }




    function update(Request $request)
    {
        $request->validate([
            'name'          => 'required|unique:asset_categories',
        ]);

        $model = AssetCategory::find($request->post('id'));
        $model->name                = $request->post('name');
        $model->save();

        $msg = "Asset Category Updated.";
        //$request->session()->flash('message',$msg);

        return redirect()->route('asset_category_list')->with('status', $msg);
    }



    public function delete($id)
    {
        $asset_category_id = AssetCategory::find($id);
        $asset_category_id->delete();
        return redirect()->route('asset_category_list')->with('status', 'Asset Category Deleted Successfully');
    }

    //Asset Group



    public function asset_group()
    {
        $data['main_menu']      = 'asset_management';
        $data['child_menu']     = 'asset_group_list';
        $data['asset_category']      = AssetCategory::where('company_id', Session::get('company_id'))->get();
        $data['asset_groups']      = AssetGroup::with('asset_category')->where('company_id', Session::get('company_id'))->orderByDesc('id')->get();

        return view('asset.asset_group', $data);
    }



    public function asset_group_store(Request $request)
    {
        $request->validate([
            'name'          => 'required|unique:asset_groups',
            'category_id'         => 'required',
        ]);


        $model = new AssetGroup();
        $model->name                = $request->post('name');
        $model->category_id                = $request->post('category_id');
        $model->company_id           = Session::get('company_id');
        $model->save();

        $msg = "Asset Group Inserted.";

        return redirect()->route('asset_group_list')->with('status', $msg);
    }





    function update_asset_group(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required',
            'category_id'         => 'required',
        ]);

        $model = AssetGroup::find($id);
        $model->name                = $request->name;
        $model->category_id                = $request->category_id;
        $model->save();

        $msg = "Asset Group Updated.";
        //$request->session()->flash('message',$msg);

        return redirect()->route('asset_group_list')->with('status', $msg);
    }





    public function asset_group_delete($id)
    {
        $asset_group_id = AssetGroup::find($id);
        $asset_group_id->delete();
        return redirect()->route('asset_group_list')->with('status', 'Asset Group Deleted Successfully');
    }

    


  //======================== Asset ===========================//
    public function asset(Request $request)
    {
        // dd($request->all());
        $data['main_menu']         = 'asset_management';
        $data['child_menu']        = 'asset-list';
        $data['asset_category']    = AssetCategory::where('company_id', Session::get('company_id'))->get();
        $data['asset_groups']      = AssetGroup::with('asset_category')->where('company_id', Session::get('company_id'))->get();
        $data['assets_name']       = Asset::where('company_id', Session::get('company_id'))->get();
        $data['supplier']          = Supplier::where('company_id', Session::get('company_id'))->get();
        $data['fund']              = Fund::all();
        $data['banks']             = Bank::all();
        $data['accounts']          = BankAccount::where('company_id', Session::get('company_id'))->get();


        $lastAsset = Asset::latest()->first();

        if($lastAsset){
            $data['asset_id'] = $lastAsset->id;
        }

        $asset_data = Asset::with('asset_group')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->name) {
            $where['name'] = $request->name;
            $asset_data->where('name', '=', $request->name);
        }
        if ($request->group_id) {
            $where['group_id'] = $request->group_id;
            $asset_data->where('group_id', '=', $request->group_id);
        }
        if ($request->asset_type) {
            $where['asset_type'] = $request->asset_type;
            $asset_data->where('asset_type', '=', $request->asset_type);
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_data->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_data->whereDate('created_at', '<=', $request->end_date);
        }
        $asset_data                   = $asset_data->paginate(10);
        $asset_data->appends($where);
        $data['assets']           = $asset_data;

        return view('asset.assets', $data);
    }



    public function asset_store(Request $request){

        // return $request->all();
        $request->validate([
            'name'          => 'required',
            'supplier_id'   => 'required',
            'code'          => 'required',
            'group_id'      => 'required',
            'life_time'     => 'required',
            'date'          => 'required', 
        ]);

        $asset = new Asset;

        if ($request->hasFile('image')) {
            $newImageName = 'asset_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('upload_images/asset'), $newImageName);
            $asset->image = $newImageName;
        } else {
            $asset->image = 'default_image.jpg';
        }

        $asset->company_id           =  Session::get('company_id');
        $asset->name                 =  $request->name;
        $asset->asset_code           =  $request->code;
        $asset->asset_series         =  $request->series;
        $asset->description          =  $request->description;
        $asset->group_id             =  $request->group_id;
        $asset->life_time            =  $request->life_time;
        $asset->quantity             =  $request->quantity;
        $asset->purchase_date        =  $request->date;
        $asset->unit                 =  $request->unit;
        $asset->unit_price           =  $request->unit_price;
        $asset->total_price          =  $request->total_price;
        $asset->warranty_yr          =  $request->warranty_yr;
        $asset->warranty_mnth        =  $request->warranty_mnth;
        $asset->supplier_id          =  $request->supplier_id;
        $asset->created_by           =  auth()->user()->id;
        $asset->status               =  1;

        $asset->save();


        AssetStock::create([
            'asset_id' => $asset->id,
            'quantity' => $request->quantity,
            'asset_group_id' => $request->input('group_id'),
            'company_id'     => Session::get('company_id')
        ]);

        $due  = SupplierDue::where(['supplier_id'=>$request->supplier_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();
        if($due){
            $due->due_amount += $request->total_price;
            $due->updated_by = auth()->user()->id;
            $due->update();
        }else{
            $due                =  new SupplierDue;
            $due->company_id    =  Session::get('company_id');
            $due->supplier_id   =  $request->supplier_id;
            $due->due_amount    =  $request->total_price;
            $due->status        =  1;
            $due->created_by    =  auth()->user()->id;
            $due->save();
        }
        
        $msg = "Asset Inserted.";
        $request->session()->flash('message',$msg);

        return redirect()->route('asset_list')->with('status', $msg);
      
    }

   
    function asset_update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required',
            'supplier_id'   => 'required',
            'asset_code'          => 'required',
            'group_id'      => 'required',
            'life_time'     => 'required',
            'purchase_date'          => 'required', 
        ]);

        $asset = Asset::findOrFail($id);


        $due  = SupplierDue::where(['supplier_id'=>$request->supplier_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();

        if($due){
            if($asset->total_price < $request->total_price){
                $due->due_amount += ($request->total_price - $asset->total_price);
                $due->updated_by = auth()->user()->id;
                $due->update();
            }else{
                $due->due_amount -= ($asset->total_price - $request->total_price);
                $due->updated_by = auth()->user()->id;
                $due->update();
            }
            
        }else{
            $due                =  new SupplierDue;
            $due->company_id    =  Session::get('company_id');
            $due->supplier_id   =  $request->supplier_id;
            $due->due_amount    =  $request->total_price;
            $due->status        =  1;
            $due->created_by    =  auth()->user()->id;
            $due->save();
        }

        if ($request->hasFile('image')) {
            $newImageName = 'asset_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('upload_images/asset'), $newImageName);
            $asset->image = $newImageName;
        } 

        $asset->company_id           =  Session::get('company_id');
        $asset->name                 =  $request->name;
        $asset->asset_code           =  $request->asset_code;
        $asset->asset_series         =  $request->asset_series;
        $asset->description          =  $request->description;
        $asset->group_id             =  $request->group_id;
        $asset->life_time            =  $request->life_time;
        $asset->quantity             =  $request->quantity;
        $asset->purchase_date        =  $request->purchase_date;
        $asset->unit                 =  $request->unit;
        $asset->unit_price           =  $request->unit_price;
        $asset->total_price          =  $request->total_price;
        $asset->warranty_yr          =  $request->warranty_yr;
        $asset->warranty_mnth        =  $request->warranty_mnth;
        $asset->supplier_id          =  $request->supplier_id;
        $asset->created_by           =  auth()->user()->id;
        $asset->status               =  1;

        

        $stock = AssetStock::where('asset_id',$id)->first();

        if($stock){
            $stock->quantity        = $asset->quantity;
            $stock->asset_group_id  =  $request->input('group_id');
            $stock->update();
        }

        $asset->update();
        
        $msg = "Asset Data Updated.";
        $request->session()->flash('message',$msg);

        return redirect()->route('asset_list')->with('status', $msg);
    }

    public function asset_delete($id)
    {
        $asset_id = Asset::find($id);
        $asset_id->delete();
        return redirect()->route('asset_list')->with('success', 'Asset Deleted Successfully');
    }


    //========================== Asset Functionality End =================================//


    //asset purchase
    // public function asset_purchase(Request $request)
    // {
    //     $data['main_menu']      = 'asset_management';
    //     $data['child_menu']     = 'asset_purchase_list';
    //     // $data['asset_category']      = AssetCategory::all();
    //     $data['fund_types'] = Fund::all();
    //     $data['companies'] = Company::where('status', 1)->get();
    //     $data['banks'] = Bank::get();
    //     $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
    //     $data['asset_groups']      = AssetGroup::with('asset_category')->where('company_id', Session::get('company_id'))->get();
    //     $data['assets']      = Asset::with('asset_group')->where('company_id', Session::get('company_id'))->get();
    //     $asset_purchase = AssetPurchase::with('asset', 'fund', 'company', 'bank', 'account')->where('company_id', Session::get('company_id'))->orderByDesc('id');
    //     $where = array();
    //     if ($request->asset_id) {
    //         $asset_purchase->whereHas('asset_details.asset', function ($query) use ($request) {
    //             $query->where('asset_id', $request->asset_id);
    //         });
    //     }
    //     // dd($request->asset_group_id);
    //     if ($request->asset_group_id) {
    //         $asset_purchase->whereHas('asset_details', function ($query) use ($request) {
    //             $query->where('asset_group_id', $request->asset_group_id);
    //         });
    //     }
    //     if ($request->p_code) {
    //         $where['p_code'] = $request->p_code;
    //         $asset_purchase->where('p_code', '=', $request->p_code);
    //     }
    //     if ($request->start_date) {
    //         $where['start_date'] = $request->start_date;
    //         $asset_purchase->whereDate('created_at', '>=', $request->start_date);
    //     }
    //     if ($request->end_date) {
    //         $where['end_date'] = $request->end_date;
    //         $asset_purchase->whereDate('created_at', '<=', $request->end_date);
    //     }

    //     $asset_purchase      = $asset_purchase->paginate(10);
    //     $asset_purchase->appends($where);
    //     $data['asset_purchased']      = $asset_purchase;
    //     // dd($data['asset_purchase']);

    //     return view('asset.asset_purchase', $data);
    // }

    // public function print_asset_purchase(Request $request)
    // {
    //     $asset_purchase = AssetPurchase::with('asset')->where('company_id', Session::get('company_id'))->orderByDesc('id');
    //     $where = array();
    //     if ($request->asset_id) {
    //         $asset_purchase->whereHas('asset_details.asset', function ($query) use ($request) {
    //             $query->where('asset_id', $request->asset_id);
    //         });
    //     }
    //     // dd($request->asset_group_id);
    //     if ($request->asset_group_id) {
    //         $asset_purchase->whereHas('asset_details', function ($query) use ($request) {
    //             $query->where('asset_group_id', $request->asset_group_id);
    //         });
    //     }
    //     if ($request->p_code) {
    //         $where['p_code'] = $request->p_code;
    //         $asset_purchase->where('p_code', '=', $request->p_code);
    //     }
    //     if ($request->start_date) {
    //         $where['start_date'] = $request->start_date;
    //         $asset_purchase->whereDate('created_at', '>=', $request->start_date);
    //     }
    //     if ($request->end_date) {
    //         $where['end_date'] = $request->end_date;
    //         $asset_purchase->whereDate('created_at', '<=', $request->end_date);
    //     }
    //     $asset_purchase                   = $asset_purchase->get();
    //     // dd($asset_purchase);
    //     $data['asset_purchased']      = $asset_purchase;

    //     return view('asset.asset_purchase_print', $data);
    // }

    // public function getGroupWiseAsset(Request $request)
    // {
    //     $groupId = $request->input('asset_group_id');
    //     // dd($groupId);
    //     $assets = Asset::where('company_id', Session::get('company_id'))->where('group_id', $groupId)->get(['id', 'name', 'asset_type']);
    //     // dd($assets);
    //     return response()->json($assets);
    // }

    public function assetPurchaseInvoice($id)
    {
        $purchase_asset = AssetPurchase::with('asset_details', 'company', 'fund')->where('company_id', session()->get('company_id'))->where('id', $id)->first();
        // dd($purchase_asset);
        return view('asset.asset_purchase_invoice', compact('purchase_asset'));
    }

    public function asset_purchase_store(Request $request)
    {
        $request->validate([
            // 'p_code'             => 'unique|purchase_asset',
            'purchase_date'      => 'required',
            'quantity'           => 'required',
            'unit_price'         => 'required',
            'asset_id'           => 'required',
            'asset_group_id'     => 'required',
        ]);

        $lastAssetPurchase = AssetPurchase::latest()->first();
        $lastNumber = ($lastAssetPurchase) ? $lastAssetPurchase->id : 0;
        $date = now()->format('Y');
        $assetPurchaseCode = "ASTP-" . $date . ($lastNumber + 1);

        $lastInvoice = AssetPurchase::latest()->first();
        $lastNumber = ($lastInvoice) ? $lastInvoice->id : 0;
        // $date = now()->format('Y');
        $invoiceNumber = "AINV-" . $lastNumber + 1;

        $purchase = new AssetPurchase();
        $purchase->purchase_date = $request->input('purchase_date');
        $purchase->p_code = $assetPurchaseCode;
        $purchase->invoice_no = $invoiceNumber;
        $purchase->purchase_note = $request->input('purchase_note');
        $purchase->fund_id = $request->input('fund_id');
        $purchase->bank_id = $request->input('bank_id');
        $purchase->account_id = $request->input('account_id');
        $purchase->company_id = Session::get('company_id');
        $purchase->payment_type = $request->input('payment_type');
        $purchase->check_no = $request->input('check_no');
        $purchase->check_issue_date = $request->input('check_issue_date');
        $purchase->total_amount = $request->input('total_amount');
        $purchase->company_id           = Session::get('company_id');

        $purchase->save();

        $purchaseDetail = new AssetPurchaseDetails();
        $purchaseDetail->purchase_asset_id = $purchase->id;
        $purchaseDetail->asset_id = $request->input('asset_id');
        $purchaseDetail->asset_group_id = $request->input('asset_group_id');
        $purchaseDetail->quantity = $request->input('quantity');
        $purchaseDetail->unit_price = $request->input('unit_price');
        $purchaseDetail->amount = $request->input('quantity') * $request->input('unit_price');
        $purchaseDetail->company_id           = Session::get('company_id');
        $purchaseDetail->save();

        $fund_log                       = new FundLog();
        $fund_log->company_id           = Session::get('company_id');
        $fund_log->fund_id              = $request->fund_id;
        $fund_log->type                 = '2';
        $fund_log->amount               = $request->total_amount;
        $fund_log->transection_type     = 'asset_purchase';
        $fund_log->transection_id       = $purchase->id;
        $fund_log->transection_date     = $request->purchase_date;
        $fund_log->payment_type         = $request->payment_type;
        $fund_log->status               = '1';
        $fund_log->created_by           = auth()->user()->id;
        $fund_log->save();

        $asset_id = $request->input('asset_id');
        $quantity = $request->input('quantity');

        // $existingStock = AssetStock::where('company_id', Session::get('company_id'))->where('asset_id', $asset_id)->first();

        // if ($existingStock) {
        //     $existingStock->quantity += $quantity;
        //     $existingStock->save();
        // } else {
            AssetStock::create([
                'asset_id' => $asset_id,
                'quantity' => $quantity,
                'asset_group_id' => $request->input('asset_group_id'),
                'company_id'     => Session::get('company_id')
            ]);
        // }

        $bankAccount = BankAccount::where('id', $purchase->account_id)->first();
        if ($bankAccount) {
            $bankAccount->current_balance -= (float)$purchase->total_amount;
            $bankAccount->update();
            $bank_fund = FundCurrentBalance::where(['fund_id' => $purchase->fund_id, 'status' => 1])->where('bank_id', $purchase->bank_id)->first();
            if ($bank_fund) {
                $bank_fund->amount -=   $purchase->total_amount;
                $bank_fund->updated_by = auth()->user()->id;
                $bank_fund->update();
            } else {
                $fund_current_balance = new FundCurrentBalance();
                $fund_current_balance->fund_id = $purchase->fund_id;
                $fund_current_balance->bank_id = $purchase->bank_id;
                $fund_current_balance->company_id = Session::get('company_id');
                $fund_current_balance->amount = $purchase->total_amount;
                $fund_current_balance->status = '1';
                $fund_current_balance->created_by = auth()->user()->id;
                $fund_current_balance->save();
            }
        } else {
            $fund = FundCurrentBalance::where(['fund_id' => $purchase->fund_id, 'company_id' => Session::get('company_id'), 'status' => 1])->first();
            if ($fund != null) {
                $fund->amount -= $purchase->total_amount;
                $fund->updated_by = auth()->user()->id;
                $fund->update();
            } else {
                $fund_current_balance = new FundCurrentBalance();
                $fund_current_balance->fund_id = $purchase->fund_id;
                $fund_current_balance->company_id = Session::get('company_id');
                $fund_current_balance->amount = $purchase->total_amount;
                $fund_current_balance->status = '1';
                $fund_current_balance->created_by = auth()->user()->id;
                $fund_current_balance->save();
            }
        }

        $msg = "Asset Purchased.";
        return redirect()->route('asset_purchase_list')->with('status', $msg);
    }



    public function asset_purchase_update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'purchase_date'      => 'required',
            'quantity'           => 'required',
            'unit_price'         => 'required',
        ]);

        $purchase = AssetPurchase::findOrFail($id);

        $old_amount = $purchase->total_amount;
        // dd($old_amount);

        $purchase->purchase_date = $request->purchase_date;
        $purchase->purchase_note = $request->purchase_note;
        $purchase->total_amount = $request->total_amount;
        $purchase->update();

        $purchaseDetail = AssetPurchaseDetails::where('purchase_asset_id', $id)->firstOrFail();

        $old_quantity = $purchaseDetail->quantity;

        $purchaseDetail->asset_id = $request->asset_id;
        $purchaseDetail->asset_group_id = $request->asset_group_id;
        $purchaseDetail->quantity = $request->quantity;
        $purchaseDetail->unit_price = $request->unit_price;
        $purchaseDetail->amount = $request->quantity * $request->unit_price;
        $purchaseDetail->update();

        $fund_log = FundLog::with('purchase')->where('transection_id', $id)->where('transection_type', 'asset_purchase')->firstOrFail();
        $fund_log->transection_date = $request->purchase_date;
        $fund_log->amount = $request->total_amount;
        $fund_log->update();

        $difference = $request->quantity - $old_quantity;

        $asset_id = $request->input('asset_id');

        $existingStock = AssetStock::where('asset_id', $asset_id)->first();

        if ($existingStock) {
            $existingStock->quantity += $difference;
            $existingStock->save();
        } else {
            AssetStock::create([
                'asset_id' => $asset_id,
                'quantity' => $difference,
                'asset_group_id' => $request->input('asset_group_id'),
            ]);
        }

        $bankAccount = BankAccount::where('id', $purchase->account_id)->first();
        if ($bankAccount) {
            $old_acc_balance = $bankAccount->current_balance;
            $sum = $old_amount + $old_acc_balance;
            $bankAccount->current_balance = $sum - (float)$request->total_amount;
            $bankAccount->update();
            $bank_fund = FundCurrentBalance::where(['fund_id' => $purchase->fund_id, 'status' => 1])->where('bank_id', $purchase->bank_id)->first();

            if ($bank_fund) {
                $old_fund_bank = $bank_fund->amount;
                $dif = $old_amount + $old_fund_bank;
                $bank_fund->amount = $dif - $request->total_amount;
                $bank_fund->updated_by = auth()->user()->id;
                $bank_fund->update();
            } else {
                $fund_current_balance = new FundCurrentBalance();
                $fund_current_balance->fund_id = $purchase->fund_id;
                $fund_current_balance->bank_id = $purchase->bank_id;
                $fund_current_balance->company_id = Session::get('company_id');
                $fund_current_balance->amount = $purchase->total_amount;
                $fund_current_balance->status = '1';
                $fund_current_balance->created_by = auth()->user()->id;
                $fund_current_balance->save();
            }
        } else {
            $fund = FundCurrentBalance::where(['fund_id' => $purchase->fund_id, 'company_id' => Session::get('company_id'), 'status' => 1])->first();

            if ($fund) {
                $old_fund = $fund->amount;
                $dif_fund = $old_amount + $old_fund;
                $fund->amount = $dif_fund - $request->total_amount;
                $fund->updated_by = auth()->user()->id;
                $fund->update();
            } else {
                $fund_current_balance = new FundCurrentBalance();
                $fund_current_balance->fund_id = $purchase->fund_id;
                $fund_current_balance->company_id = Session::get('company_id');
                $fund_current_balance->amount = $purchase->total_amount;
                $fund_current_balance->status = '1';
                $fund_current_balance->created_by = auth()->user()->id;
                $fund_current_balance->save();
            }
        }

        $msg = "Asset Purchase updated successfully.";
        return redirect()->route('asset_purchase_list')->with('status', $msg);
    }


    //asset stock
    public function asset_stock(Request $request)
    {
        $data['main_menu']      = 'asset_management';
        $data['child_menu']     = 'asset_stock';
        $data['fund_types']     = Fund::all();
        $data['companies']      = Company::where('status', 1)->get();
        $data['banks']          = Bank::get();
        $data['accounts']       = BankAccount::where('company_id', Session::get('company_id'))->get();
        // $employees              = Employee::join('asset_assign', function ($join) {
        //     $join->on('employees.id', '=', 'asset_assign.employee_id');
        // })
        //     ->join('asset_stocks', function ($join) {
        //         $join->on('asset_stocks.asset_id', '=', 'asset_assign.asset_id')
        //             ->on('asset_stocks.asset_group_id', '=', 'asset_assign.asset_group_id');
        //     })
        //     ->where('asset_assign.asset_type', 'Fixed')
        //     ->where('employees.company_id', Session::get('company_id'))
        //     ->pluck('employees.id')
        //     ->toArray();

        // // dd($employees);
        // $data['employees'] = Employee::whereIn('id', $employees)->get();
        // dd($data['employees']);
        $data['asset_groups']      = AssetGroup::with('asset_category')->where('company_id', Session::get('company_id'))->get();
        $data['assets']      = Asset::with('asset_group')->where('company_id', Session::get('company_id'))->get();
        $asset_stock = AssetStock::with('asset', 'group')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->asset_id) {
            $where['asset_id'] = $request->asset_id;
            $asset_stock->where('asset_id', $request->asset_id);
        }
        if ($request->asset_group_id) {
            $where['asset_group_id'] = $request->asset_group_id;
            $asset_stock->where('asset_group_id', $request->asset_group_id);
        }
        if ($request->asset_code) {
            $asset_stock->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_stock->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_stock->whereDate('created_at', '<=', $request->end_date);
        }
        $asset_stock                   = $asset_stock->paginate(10);
        $asset_stock->appends($where);
        $data['asset_stocks']      =  $asset_stock;
        return view('asset.asset_stock', $data);
    }



    public function asset_stock_print(Request $request)
    {
        $data['asset_groups']      = AssetGroup::with('asset_category')->where('company_id', Session::get('company_id'))->get();
        $data['assets']      = Asset::with('asset_group')->where('company_id', Session::get('company_id'))->get();
        $asset_stock = AssetStock::with('asset', 'group')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->asset_id) {
            $where['asset_id'] = $request->asset_id;
            $asset_stock->where('asset_id', $request->asset_id);
        }
        if ($request->asset_group_id) {
            $where['asset_group_id'] = $request->asset_group_id;
            $asset_stock->where('asset_group_id', $request->asset_group_id);
        }
        if ($request->asset_code) {
            $asset_stock->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_stock->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_stock->whereDate('created_at', '<=', $request->end_date);
        }
        $asset_stock = $asset_stock->get();
        $data['asset_stocks']      = $asset_stock;

        return view('asset.asset_stock_print', $data);
    }



    //asset expense
    public function asset_expense(Request $request)
    {
        $data['main_menu']      = 'asset_management';
        $data['child_menu']     = 'asset_expense';
        $data['asset_groups']   = AssetGroup::with('asset_category')->where('company_id', Session::get('company_id'))->get();
        $data['assets']         = Asset::with('asset_group')->where('company_id', Session::get('company_id'))->get();
        $asset_expense          = AssetPurchase::with('asset_details')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->asset_code) {
            $asset_expense->whereHas('asset_details.asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->asset_id) {
            $asset_expense->whereHas('asset_details.asset', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            });
        }
        if ($request->asset_group_id) {
            $asset_expense->whereHas('asset_details', function ($query) use ($request) {
                $query->where('asset_group_id', $request->asset_group_id);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_expense->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_expense->whereDate('created_at', '<=', $request->end_date);
        }

        $asset_expense = $asset_expense->paginate(10);
        $asset_expense->appends($where);

        $data['asset_purchase']      = $asset_expense;
        return view('asset.asset_expense', $data);
    }



    public function asset_expense_print(Request $request)
    {
        $asset_expense = AssetPurchase::with('asset_details')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->asset_code) {
            $asset_expense->whereHas('asset_details.asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->asset_id) {
            $asset_expense->whereHas('asset_details.asset', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            });
        }
        if ($request->asset_group_id) {
            $asset_expense->whereHas('asset_details', function ($query) use ($request) {
                $query->where('asset_group_id', $request->asset_group_id);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_expense->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_expense->whereDate('created_at', '<=', $request->end_date);
        }
        $asset_expense = $asset_expense->get();
        $data['asset_purchase']      = $asset_expense;

        return view('asset.asset_expense_print', $data);
    }



    //asset assign
    public function asset_assign(Request $request)
    {
        $data['main_menu']      = 'asset_management';
        $data['child_menu']     = 'asset_assign_list';
        $groups                 = AssetStock::where('company_id', Session::get('company_id'))->pluck('asset_group_id')->toArray();
        $data['asset_group']    = AssetGroup::where('company_id', Session::get('company_id'))->get();
        $assets                 = AssetStock::where('company_id', Session::get('company_id'))->pluck('asset_id')->toArray();
        $data['assets']         = Asset::whereIn('id', $assets)->where('company_id', Session::get('company_id'))->get();
        $data['employees']      = Employee::with('department', 'designation')->where('company_id', Session::get('company_id'))->get();
        $asset_assign           = AssetAssign::with('employee', 'asset', 'asset_group')->where('company_id', Session::get('company_id'))->orderByDesc('id');


        $where = array();
        if ($request->employee_id) {
            $where['employee_id'] = $request->employee_id;
            $asset_assign->where('employee_id', '=', $request->employee_id);
        }
        if ($request->asset_id) {
            $where['asset_id'] = $request->asset_id;
            $asset_assign->where('asset_id', '=', $request->asset_id);
        }
        if ($request->asset_code) {
            $asset_assign->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->asset_group_id) {
            $where['asset_group_id'] = $request->asset_group_id;
            $asset_assign->where('asset_group_id', '=', $request->asset_group_id);
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_assign->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_assign->whereDate('created_at', '<=', $request->end_date);
        }
        $asset_assign                   = $asset_assign->paginate(10);
        $asset_assign->appends($where);
        $data['asset_assigned']      = $asset_assign;

        return view('asset.asset_assign', $data);
    }



    public function asset_assign_print(Request $request)
    {
        $groups = AssetStock::whereHas('asset', function ($query) {
            $query->where('asset_type', 'Fixed');
        })->where('company_id', Session::get('company_id'))->pluck('asset_group_id')->toArray();
        $data['asset_group'] = AssetGroup::whereIn('id', $groups)->where('company_id', Session::get('company_id'))->get();
        $assets = AssetStock::where('company_id', Session::get('company_id'))->pluck('asset_id')->toArray();
        $data['assets'] = Asset::where('company_id', Session::get('company_id'))->whereIn('id', $assets)->get();
        $data['employees'] = Employee::with('department', 'designation')->where('company_id', Session::get('company_id'))->get();
        $asset_assign = AssetAssign::with('employee', 'asset', 'asset_group')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->employee_id) {
            $where['employee_id'] = $request->employee_id;
            $asset_assign->where('employee_id', '=', $request->employee_id);
        }
        if ($request->asset_id) {
            $where['asset_id'] = $request->asset_id;
            $asset_assign->where('asset_id', '=', $request->asset_id);
        }
        if ($request->asset_code) {
            $asset_assign->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->asset_group_id) {
            $where['asset_group_id'] = $request->asset_group_id;
            $asset_assign->where('asset_group_id', '=', $request->asset_group_id);
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_assign->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_assign->whereDate('created_at', '<=', $request->end_date);
        }
        $asset_assign = $asset_assign->get();
        $data['asset_assigned']      = $asset_assign;

        return view('asset.asset_assign_print', $data);
    }



    public function getStockAsset(Request $request)
    {
        // dd($request->all());
        $group_id = $request->asset_group_id;
        // dd($group_id);
        $asset_ids = AssetStock::where('asset_group_id',  $request->asset_group_id)
                        ->where('company_id', Session::get('company_id'))
                        ->pluck('asset_id')
                        ->toArray();

        // dd($asset_ids);

        $assets = Asset::whereIn('id', $asset_ids)
                    ->where('company_id', Session::get('company_id'))
                    ->orderByDesc('id')
                    ->get();

        // dd($assets);
        return response()->json($assets);
    }

    public function getStockQuantity(Request $request)
    {
        $assetId = $request->asset_id;
        $assetStock = AssetStock::where('company_id', Session::get('company_id'))->where('asset_id', $assetId)->first();
        $assignedQuantity = AssetAssign::where('asset_id', $assetId)->where('company_id', Session::get('company_id'))->sum('quantity');

        if ($assetStock) {
            $quantity = $assetStock->quantity - $assignedQuantity;
            return response()->json(['success' => true, 'quantity' => $quantity]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getAssignedQuantity(Request $request)
    {
        $assetId = $request->asset_id;
        // dd($request->asset_id);
        $assignedQuantity = AssetAssign::where('asset_id', $assetId)
            ->where('employee_id', $request->employee_id)
            ->where('company_id', Session::get('company_id'))
            ->sum('quantity');
        // dd($assignedQuantity);

        $damageQuantity = AssetDamage::where('employee_id', $request->employee_id)
            ->where('company_id', Session::get('company_id'))
            ->where('asset_id', $assetId)
            ->first();

        $totalDamageQuantity = 0;
        if ($damageQuantity) {
            $totalDamageQuantity = DamageActivityLog::where(
                'damage_id',
                $damageQuantity->id
            )->where('company_id', Session::get('company_id'))->sum('quantity');
        }

        $lostQuantity = AssetLost::where('employee_id', $request->employee_id)
            ->where('company_id', Session::get('company_id'))
            ->where('asset_id', $assetId)
            ->first();

        $totalLostQuantity = 0;
        if ($lostQuantity) {
            $totalLostQuantity = LostActivityLog::where(
                'lost_id',
                $lostQuantity->id
            )->where('company_id', Session::get('company_id'))
                ->sum('quantity');
        }

        if ($assignedQuantity) {
            $remainingQuantity = $assignedQuantity - $totalDamageQuantity - $totalLostQuantity;
            // dd($remainingQuantity);
            return response()->json(['success' => true, 'quantity' => $remainingQuantity . '/' . $assignedQuantity]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function asset_assign_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'user_name'            => 'required',
            'asset_group_id'       => 'required',
            'asset_id'             => 'required',
            'assign_date'          => 'required',

        ]);

        $assetId = $request->asset_id;
        $asset = Asset::where('id', $assetId)->where('company_id', Session::get('company_id'))->first();
        // $assetType = $asset->asset_type;

        $model = new AssetAssign();
        $model->user_name        = $request->post('user_name');
        $model->asset_group_id = $request->post('asset_group_id');
        $model->quantity = $request->post('quantity');
        $model->company_id           = Session::get('company_id');
        $model->asset_id = $assetId;
        // $model->asset_type  = $assetType;
        $model->assign_date  = $request->post('assign_date');

        $model->save();

        $msg = "Asset Assigned.";
        //$request->session()->flash('message',$msg);

        return redirect()->route('asset_assign_list')->with('status', $msg);
    }

    function asset_assign_update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'employee_id'          => 'required',
            'asset_group_id'       => 'required',
            'asset_id'             => 'required',
            'assign_date'          => 'required',
        ]);

        $assetId = $request->asset_id;
        $asset = Asset::findOrFail($assetId);
        $assetType = $asset->asset_type;

        $model = AssetAssign::findOrFail($id);
        $model->employee_id        = $request->employee_id;
        $model->asset_group_id = $request->asset_group_id;
        $model->quantity = $request->quantity;
        $model->asset_id = $assetId;
        $model->asset_type  = $assetType;
        $model->assign_date = $request->assign_date;
        $model->update();

        $msg = "Asset Assign Updated.";

        return redirect()->route('asset_assign_list')->with('status', $msg);
    }

    public function asset_assign_delete($id)
    {
        $asset_assign_id = AssetAssign::find($id);
        $asset_assign_id->delete();
        return redirect()->route('asset_assign_list')->with('success', 'Asset Assign Deleted Successfully');
    }

    //asset damage
    public function damage_asset_list(Request $request)
    {
        $data['main_menu']      = 'asset_management';
        $data['child_menu']     = 'damage_asset_list';
        $groups                 = AssetStock::with('group')->where('company_id', Session::get('company_id'))->pluck('asset_group_id')->toArray();
        $data['asset_groups']   = AssetGroup::with('asset_category')->where('company_id', Session::get('company_id'))->whereIn('id', $groups)->get();
        $assets                 = AssetStock::with('asset')->where('company_id', Session::get('company_id'))->pluck('asset_id')->toArray();
        $data['asset']          = Asset::whereIn('id', $assets)->where('company_id', Session::get('company_id'))->get();

        //Fixed

        $groups = AssetAssign::where('company_id', Session::get('company_id'))->pluck('asset_group_id')->toArray();
        $data['asset_group'] = AssetGroup::whereIn('id', $groups)->distinct()->get();

        $assets = AssetAssign::where('company_id', Session::get('company_id'))->pluck('asset_id')->toArray();
        $data['assets'] = Asset::whereIn('id', $assets)->where('company_id', Session::get('company_id'))->distinct()->get();

        //Usable
        $groups_usable = AssetStock::where('company_id', Session::get('company_id'))->pluck('asset_group_id')->toArray();
        $data['asset_groupU'] = AssetGroup::whereIn('id', $groups_usable)->where('company_id', Session::get('company_id'))->distinct()->get();

        $assetsU = AssetStock::where('company_id', Session::get('company_id'))->pluck('asset_id')->toArray();
        $data['assetsU'] = Asset::whereIn('id', $assetsU)->where('company_id', Session::get('company_id'))->distinct()->get();

        $asset_damage = AssetDamage::with('asset', 'asset_group', 'employee')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        // dd($asset_damage);
        $where = array();
       
        if ($request->asset_id) {
            $asset_damage->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            });
        }
        if ($request->asset_code) {
            $asset_damage->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->asset_group_id) {
            $asset_damage->whereHas('asset_group', function ($query) use ($request) {
                $query->where('asset_group_id', $request->asset_group_id);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_damage->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_damage->whereDate('date', '<=', $request->end_date);
        }
        $asset_damage                   = $asset_damage->paginate(10);
        $asset_damage->appends($where);
        $data['asset_damages']           = $asset_damage;
        return view('asset.asset_damage', $data);
    }

    public function asset_damage_print(Request $request)
    {
        $asset_damage = AssetDamage::with('asset', 'asset_group', 'employee')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->employee_id) {
            $asset_damage->whereHas('employee', function ($query) use ($request) {
                $query->where('employee_id', $request->employee_id);
            });
        }
        if ($request->name) {
            $asset_damage->whereHas('asset', function ($query) use ($request) {
                $query->where('name', $request->name);
            });
        }
        if ($request->asset_code) {
            $asset_damage->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->asset_group_id) {
            $asset_damage->whereHas('asset_group', function ($query) use ($request) {
                $query->where('asset_group_id', $request->asset_group_id);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_damage->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_damage->whereDate('date', '<=', $request->end_date);
        }
        $asset_damage                   = $asset_damage->get();
        $data['asset_damages']      = $asset_damage;

        return view('asset.asset_damage_print', $data);
    }

    public function getEmployeeWiseGroup(Request $request)
    {
        $employee_id = $request->employee_id;
        $groups = AssetAssign::select('asset_id', 'asset_group_id', 'asset_type')
            ->distinct()
            ->with('asset_group.asset_category', 'asset')
            ->where('employee_id',  $employee_id)
            ->where('company_id', Session::get('company_id'))
            ->where('asset_type', 'Fixed')
            ->get();
        // dd($groups);
        return response()->json($groups);
    }

    public function getEmployeeWiseGroupAsset(Request $request)
    {
        // dd($request->all());
        $employee_id = $request->employee_id;
        $group_id = $request->group_id;
        // dd($group_id);
        $assets = AssetAssign::select('asset_id', 'asset_group_id', 'asset_type')
            ->distinct()
            ->with('asset', 'asset_group')
            ->where('employee_id',  $employee_id)
            ->where('company_id', Session::get('company_id'))
            ->where('asset_group_id', $group_id)
            ->where('asset_type', 'Fixed')
            ->get();

        return response()->json($assets);
    }
    public function getGroupWiseAssetUsable(Request $request)
    {
        // dd($request->all());
        $group_id = $request->group_id;
        // dd($group_id);
        $assets = AssetStock::select('asset_id', 'asset_group_id')
            ->distinct()
            ->with('asset')
            ->where('company_id', Session::get('company_id'))
            ->whereHas('asset', function ($query) {
                $query->where('asset_type', 'Usable');
            })
            ->where('asset_group_id', $group_id)
            ->get();
        // dd($assets);
        return response()->json($assets);
    }

    public function fixed_asset_damage_store(Request $request)
    {
        $request->validate([
            'employee_id'    => 'required',
            'asset_group_id' => 'required',
            'asset_id'       => 'required',
            'quantity'       => 'required|integer',
        ]);

        $current_stock = AssetStock::where('company_id', Session::get('company_id'))->where('asset_id', $request->asset_id)->first();
        if (!$current_stock || $current_stock->quantity < $request->quantity) {
            return redirect()->back()->with('message', 'Insufficient stock quantity.');
        }

        $assignedQuantity = AssetAssign::where('asset_id', $request->asset_id)
            ->where('employee_id', $request->employee_id)
            ->where('company_id', Session::get('company_id'))
            ->sum('quantity');

        $totalDamageQuantity = DamageActivityLog::whereHas('damage', function ($query) use ($request) {
            $query->where('employee_id', $request->employee_id)
                ->where('asset_id', $request->asset_id);
        })->where('company_id', Session::get('company_id'))
            ->sum('quantity');
        // dd($totalDamageQuantity);

        if ($totalDamageQuantity + $request->quantity > $assignedQuantity) {
            return redirect()->back()->with('message', 'Damage quantity exceeds assigned quantity.');
        }

        $damage = AssetDamage::where('asset_id', $request->asset_id)
            ->where('employee_id', $request->employee_id)
            ->where('company_id', Session::get('company_id'))
            ->first();

        if ($damage) {
            $damage->update([
                'quantity' => $request->quantity,
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'damage_note' => $request->damage_note,
                'asset_group_id' => $request->asset_group_id,
            ]);

            DamageActivityLog::create([
                'damage_id' => $damage->id,
                'date' => $request->date,
                'company_id' => Session::get('company_id'),
                'quantity' => $request->quantity,
                'damage_note' => $request->damage_note,
            ]);

            $msg = "Damage Fixed Asset Updated.";
        } else {
            $damage = AssetDamage::create([
                'employee_id' => $request->employee_id,
                'asset_id' => $request->asset_id,
                'asset_group_id' => $request->asset_group_id,
                'company_id' => Session::get('company_id'),
                'date' => $request->date,
                'damage_note' => $request->damage_note,
                'quantity' => $request->quantity,
            ]);

            DamageActivityLog::create([
                'damage_id' => $damage->id,
                'date' => $request->date,
                'company_id' => Session::get('company_id'),
                'quantity' => $request->quantity,
                'damage_note' => $request->damage_note,
            ]);

            $msg = "Damage Fixed Asset Added.";
        }

        $current_stock->decrement('quantity', $request->quantity);

        return redirect()->route('damage_asset_list')->with('status', $msg);
    }



    public function usable_asset_damage_store(Request $request)
    {
        $request->validate([
            'asset_group_id' => 'required',
            'asset_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $current_stock = AssetStock::where('asset_id', $request->asset_id)->where('company_id', Session::get('company_id'))->first();
        if (!$current_stock || $current_stock->quantity < $request->quantity) {
            return redirect()->back()->with('message', 'Insufficient stock quantity.');
        }

        $damage = AssetDamage::where('asset_id', $request->asset_id)->where('company_id', Session::get('company_id'))->first();

        if ($damage) {
            $damage->update([
                'asset_group_id' => $request->asset_group_id,
                'date' => $request->date,
                'damage_note' => $request->damage_note,
                'quantity' => $request->quantity,
            ]);

            DamageActivityLog::create([
                'damage_id' => $damage->id,
                'date' => $request->date,
                'quantity' => $request->quantity,
                'damage_note' => $request->damage_note,
                'company_id' => Session::get('company_id'),
            ]);

            $msg = "Damage Usable Asset Updated.";
        } else {

            $damage = AssetDamage::create([
                'asset_id' => $request->asset_id,
                'asset_group_id' => $request->asset_group_id,
                'date' => $request->date,
                'damage_note' => $request->damage_note,
                'quantity' => $request->quantity,
                'company_id' => Session::get('company_id'),
            ]);

            DamageActivityLog::create([
                'damage_id' => $damage->id,
                'date' => $request->date,
                'quantity' => $request->quantity,
                'damage_note' => $request->damage_note,
                'company_id' => Session::get('company_id'),
            ]);

            $msg = "Damage Usable Asset Added.";
        }

        $current_stock->decrement('quantity', $request->quantity);

        return redirect()->route('damage_asset_list')->with('status', $msg);
    }


    public function update_damage_asset(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'asset_group_id' => 'required',
            'asset_id' => 'required',
            'quantity' => 'required|integer',
        ]);

        $assetId = $request->asset_id;
        $damage_quantity = $request->quantity;

        $total_damage_quantity = AssetDamage::where('asset_id', $assetId)->where('company_id', Session::get('company_id'))->where('employee_id', $request->employee_id)->sum('quantity');
        // dd($total_damage_quantity);
        $current_stock = AssetStock::where('asset_id', $assetId)->where('company_id', Session::get('company_id'))->first();
        // dd($current_stock);

        if (!$current_stock || ($current_stock->quantity + $total_damage_quantity) < $damage_quantity) {
            return redirect()->back()->with('message', 'Insufficient stock quantity.');
        }

        try {
            $damage = AssetDamage::find($id);
            $damageLog = DamageActivityLog::where('id', $request->id)->where('company_id', Session::get('company_id'))->first();
            if (!$damage) {
                return redirect()->back()->with('error', 'Damage record not found.');
            }
            if ($damage->employee_id) {
                $damage->update([
                    'employee_id' => $request->employee_id,
                    'asset_id' => $assetId,
                    'asset_group_id' => $request->asset_group_id,
                    'date' => $request->date,
                    'damage_note' => $request->damage_note,
                    'quantity' => $damage_quantity,
                ]);
            } else {
                $damage->update([
                    'asset_id' => $assetId,
                    'asset_group_id' => $request->asset_group_id,
                    'date' => $request->date,
                    'damage_note' => $request->damage_note,
                    'quantity' => $damage_quantity,
                ]);
            }


            $updated_stock_quantity = $current_stock->quantity + $total_damage_quantity - $damage_quantity;
            $current_stock->update(['quantity' => $updated_stock_quantity]);

            DamageActivityLog::where('company_id', Session::get('company_id'))->where('damage_id', $id)->where('id', $damageLog->id)->update([
                'date' => $request->date,
                'quantity' => $damage_quantity,
                'damage_note' => $request->damage_note,
            ]);

            return redirect()->route('damage_asset_list')->with('status', 'Damage asset updated.');
        } catch (\Exception $e) {
            Log::error('Error updating damage asset: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update damage asset.');
        }
    }


    //Asset Lost Report
    public function save_report_lost_asset(Request $request, $id)
    {
        // dd($request->all());
        $assetGroupId = $request->asset_group_id;
        $reportCode = $this->generateReportCode();

        $assetId = $request->asset_id;
        $lost_quantity = $request->quantity;
        $current_stock = AssetStock::where('company_id', Session::get('company_id'))->where('asset_id', $assetId)->first();

        if (!$current_stock || $current_stock->quantity < $lost_quantity) {
            return redirect()->back()->with('message', 'Insufficient stock quantity.');
        }

        if ($request->employee_id) {
            $assignedQuantity = AssetAssign::where('asset_id', $request->asset_id)
                ->where('company_id', Session::get('company_id'))
                ->where('employee_id', $request->employee_id)
                ->sum('quantity');
            // dd($assignedQuantity);
            $totalLostQuantity = LostActivityLog::whereHas('lost', function ($query) use ($request) {
                $query->where('employee_id', $request->employee_id)
                    ->where('asset_id', $request->asset_id);
            })->where('company_id', Session::get('company_id'))
                ->sum('quantity');
            // dd($totalLostQuantity);

            if ($totalLostQuantity + $request->quantity > $assignedQuantity) {
                return redirect()->back()->with('message', 'Lost quantity exceeds assigned quantity.');
            }
        }

        $lostAsset = AssetLost::where('company_id', Session::get('company_id'))->where('employee_id', $request->employee_id)
            ->where('asset_id', $assetId)
            ->first();

        if ($lostAsset) {
            $lostAsset->update([
                'asset_group_id' => $assetGroupId,
                'quantity' => $lost_quantity,
                'description' => $request->description,
                'report_date' => $request->report_date,
                'fine' => $request->fine,
            ]);

            LostActivityLog::create([
                'lost_id' => $lostAsset->id,
                'report_date' => $request->report_date,
                'quantity' => $lost_quantity,
                'fine' => $request->fine,
                'description' => $request->description,
                'company_id' => Session::get('company_id'),
            ]);

            $msg = "Lost Asset Reported.";
        } else {
            $lostAsset = AssetLost::create([
                'employee_id' => $request->employee_id,
                'asset_id' => $assetId,
                'asset_group_id' => $assetGroupId,
                'quantity' => $lost_quantity,
                'report_code' => $reportCode,
                'description' => $request->description,
                'report_date' => $request->report_date,
                'fine' => $request->fine,
                'company_id' => Session::get('company_id'),
            ]);

            LostActivityLog::create([
                'lost_id' => $lostAsset->id,
                'report_date' => $request->report_date,
                'quantity' => $lost_quantity,
                'fine' => $request->fine,
                'description' => $request->description,
                'company_id' => Session::get('company_id'),
            ]);

            $msg = "Lost Asset Reported.";
        }
        $current_stock->decrement('quantity', $lost_quantity);
        // dd($current_stock);

        return redirect()->route('asset_stock')->with('status', $msg);
    }

    private function generateReportCode()
    {
        $lastLostAsset = AssetLost::latest()->first();
        $lastNumber = ($lastLostAsset) ? $lastLostAsset->id : 0;
        $date = now()->format('Y');
        return "RPT-AST-" . $date . ($lastNumber + 1);
    }


    public function asset_lost_list(Request $request)
    {
        $data['main_menu']      = 'asset_management';
        $data['child_menu']     = 'asset_lost_list';
        // $employees = Employee::join('asset_assign', function ($join) {
        //     $join->on('employees.id', '=', 'asset_assign.employee_id');
        // })
        //     ->join('asset_stocks', function ($join) {
        //         $join->on('asset_stocks.asset_id', '=', 'asset_assign.asset_id')
        //             ->on('asset_stocks.asset_group_id', '=', 'asset_assign.asset_group_id');
        //     })
        //     ->where('employees.company_id', Session::get('company_id'))
        //     ->where('asset_assign.asset_type', 'Fixed')
        //     ->pluck('employees.id')
        //     ->toArray();

        // dd($employees);
        // $data['employees'] = Employee::whereIn('id', $employees)->where('company_id', Session::get('company_id'))->get();
        // dd($data['employees']);
        $data['asset_groups']      = AssetGroup::with('asset_category')->where('company_id', Session::get('company_id'))->get();
        $data['asset']              = Asset::with('asset_group')->where('company_id', Session::get('company_id'))->get();
        $asset_lost                 = AssetLost::with('asset', 'asset_group', 'employee')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        // if ($request->employee_id) {
        //     $asset_lost->whereHas('employee', function ($query) use ($request) {
        //         $query->where('employee_id', $request->employee_id);
        //     });
        // }
        if ($request->report_code) {
            $where['report_code'] = $request->report_code;
            $asset_lost->where('report_code', '=', $request->report_code);
        }
        if ($request->asset_id) {
            $asset_lost->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            });
        }
        if ($request->asset_code) {
            $asset_lost->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->asset_group_id) {
            $asset_lost->whereHas('asset_group', function ($query) use ($request) {
                $query->where('asset_group_id', $request->asset_group_id);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_lost->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_lost->whereDate('created_at', '<=', $request->end_date);
        }
        $asset_lost                   = $asset_lost->paginate(10);
        $asset_lost->appends($where);
        $data['assets_lost']     = $asset_lost;
        return view('asset.asset_lost', $data);
    }

    public function asset_lost_print(Request $request)
    {
        $asset_lost = AssetLost::with('asset', 'asset_group', 'employee')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->employee_id) {
            $asset_lost->whereHas('employee', function ($query) use ($request) {
                $query->where('employee_id', $request->employee_id);
            });
        }
        if ($request->report_code) {
            $where['report_code'] = $request->report_code;
            $asset_lost->where('report_code', '=', $request->report_code);
        }
        if ($request->asset_id) {
            $asset_lost->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            });
        }
        if ($request->asset_code) {
            $asset_lost->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->asset_group_id) {
            $asset_lost->whereHas('asset_group', function ($query) use ($request) {
                $query->where('asset_group_id', $request->asset_group_id);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_lost->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_lost->whereDate('created_at', '<=', $request->end_date);
        }
        $asset_lost                   = $asset_lost->get();
        $data['assets_lost']      = $asset_lost;

        return view('asset.asset_lost_print', $data);
    }

    public function update_lost_asset(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'quantity'           => 'required|integer',
        ]);

        $assetId = $request->asset_id;
        $lost_quantity = $request->quantity;

        $total_lost_quantity = AssetLost::where('asset_id', $assetId)->where('company_id', Session::get('company_id'))->where('employee_id', $request->employee_id)->sum('quantity');
        // dd($total_lost_quantity);
        $current_stock = AssetStock::where('asset_id', $assetId)->where('company_id', Session::get('company_id'))->first();
        // dd($current_stock);

        if (!$current_stock || ($current_stock->quantity + $total_lost_quantity) < $lost_quantity) {
            return redirect()->back()->with('message', 'Insufficient stock quantity.');
        }

        try {
            $lost = AssetLost::find($id);
            $lostLog = LostActivityLog::where('id', $request->id)->where('company_id', Session::get('company_id'))->first();

            if (!$lost) {
                return redirect()->back()->with('error', 'Lost record not found.');
            }


            $lost->update([
                'asset_group_id'    => $request->asset_group_id,
                'asset_id' => $assetId,
                'fine' => $request->fine,
                'report_date' => $request->report_date,
                'description' => $request->description,
                'quantity' => $lost_quantity,
            ]);


            $updated_stock_quantity = $current_stock->quantity + $total_lost_quantity - $lost_quantity;
            // dd($updated_stock_quantity);
            $current_stock->update(['quantity' => $updated_stock_quantity]);

            LostActivityLog::where('lost_id', $id)->where('id', $lostLog->id)->where('company_id', Session::get('company_id'))->update([
                'report_date' => $request->report_date,
                'quantity' => $lost_quantity,
                'fine' => $request->fine,
                'description' => $request->description,
                'company_id' => Session::get('company_id'),
            ]);
            return redirect()->route('asset_lost_list')->with('status', 'Lost asset updated.');
        } catch (\Exception $e) {
            Log::error('Error updating lost asset: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update lost asset.');
        }
    }

    //Liquidation Asset
    public function save_liquidation_asset(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'quantity' => 'required|integer',
            'asset_id' => 'required',
        ]);
        $current_stock = AssetStock::where('asset_id', $request->asset_id)->where('company_id', Session::get('company_id'))->first();
        if (!$current_stock || $current_stock->quantity < $request->quantity) {
            return redirect()->back()->with('message', 'Insufficient stock quantity.');
        }

        $liquidation = AssetLiquidation::where('asset_id', $request->asset_id)->where('company_id', Session::get('company_id'))->first();
        // dd($liquidation);
        $old_amount = $liquidation ? $liquidation->liquidation_amount : 0;
        // dd($old_amount);
        if ($liquidation) {
            $liquidation->update([
                'asset_group_id' => $request->asset_group_id,
                'liquidation_date' => $request->liquidation_date,
                'liquidation_amount' => $request->liquidation_amount + $old_amount,
                'fund_id'             => $request->fund_id,
                'bank_id'             => $request->bank_id,
                'account_id'          => $request->account_id,
                'payment_type'        => $request->payment_type,
                'check_no'            => $request->check_no,
                'check_issue_date'    => $request->check_issue_date,
                'description' => $request->description,
                'quantity' => $request->quantity + $liquidation->quantity,
            ]);


            $li_a_log = LiquidationActivityLog::create([
                'liquidation_id' => $liquidation->id,
                'liquidation_date' => $request->liquidation_date,
                'quantity' => $request->quantity,
                'liquidation_amount' => $request->liquidation_amount,
                'fund_id'             => $request->fund_id,
                'bank_id'             => $request->bank_id,
                'account_id'          => $request->account_id,
                'payment_type'        => $request->payment_type,
                'check_no'            => $request->check_no,
                'check_issue_date'    => $request->check_issue_date,
                'description' => $request->description,
                'company_id' => Session::get('company_id'),
            ]);

            // $fund_log = FundLog::with('liquidation')->where('transection_type', 'asset_liquidation')->firstOrFail();
            FundLog::create([
                'company_id' => Session::get('company_id'),
                'fund_id' => $request->fund_id,
                'type' => '1',
                'amount' => $request->liquidation_amount,
                'transection_type' => 'asset_liquidation',
                'transection_id' => $li_a_log->id,
                'transection_date' => $request->liquidation_date,
                'payment_type' => $request->payment_type,
                'status' => '1',
                'created_by' => auth()->user()->id,
            ]);

            $bankAccount = BankAccount::where('id', $liquidation->account_id)->first();
            if ($bankAccount) {
                $old_acc_balance = $bankAccount->current_balance;
                $diff = $old_acc_balance - $old_amount;
                $bankAccount->current_balance = $diff + (float)$request->liquidation_amount;
                $bankAccount->update();
                $bank_fund = FundCurrentBalance::where(['fund_id' => $liquidation->fund_id, 'status' => 1])->where('bank_id', $liquidation->bank_id)->first();

                if ($bank_fund) {
                    $old_fund_bank = $bank_fund->amount;
                    $dif = $old_fund_bank - $old_amount;
                    $bank_fund->amount = $dif + $request->liquidation_amount;
                    $bank_fund->updated_by = auth()->user()->id;
                    $bank_fund->update();
                } else {
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $liquidation->fund_id;
                    $fund_current_balance->bank_id = $liquidation->bank_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $liquidation->liquidation_amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            } else {
                $fund = FundCurrentBalance::where(['fund_id' => $liquidation->fund_id, 'company_id' => Session::get('company_id'), 'status' => 1])->first();

                if ($fund) {
                    $old_fund = $fund->amount;
                    $dif_fund = $old_fund - $old_amount;
                    $fund->amount = $dif_fund + $request->liquidation_amount;
                    $fund->updated_by = auth()->user()->id;
                    $fund->update();
                } else {
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $liquidation->fund_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $liquidation->liquidation_amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }

            $msg = "Liquidation Asset Updated.";
        } else {
            $liquidation = AssetLiquidation::create([
                'asset_id'     => $request->asset_id,
                'asset_group_id'    => $request->asset_group_id,
                'quantity'     => $request->quantity,
                'description'  => $request->description,
                'liquidation_date'  => $request->liquidation_date,
                'fund_id'             => $request->fund_id,
                'bank_id'             => $request->bank_id,
                'account_id'          => $request->account_id,
                'payment_type'        => $request->payment_type,
                'check_no'            => $request->check_no,
                'check_issue_date'    => $request->check_issue_date,
                'liquidation_amount'  => $request->liquidation_amount,
                'company_id' => Session::get('company_id'),
            ]);

            $li_log = LiquidationActivityLog::create([
                'liquidation_id' => $liquidation->id,
                'liquidation_date' => $request->liquidation_date,
                'quantity' => $request->quantity,
                'liquidation_amount' => $request->liquidation_amount,
                'fund_id'             => $request->fund_id,
                'bank_id'             => $request->bank_id,
                'account_id'          => $request->account_id,
                'payment_type'        => $request->payment_type,
                'check_no'            => $request->check_no,
                'check_issue_date'    => $request->check_issue_date,
                'description' => $request->description,
                'company_id' => Session::get('company_id'),
            ]);

            $fund_log                       = new FundLog();
            $fund_log->company_id           = Session::get('company_id');
            $fund_log->fund_id              = $request->fund_id;
            $fund_log->type                 = '1';
            $fund_log->amount               = $request->liquidation_amount;
            $fund_log->transection_type     = 'asset_liquidation';
            $fund_log->transection_id       = $li_log->id;
            $fund_log->transection_date     = $request->liquidation_date;
            $fund_log->payment_type         = $request->payment_type;
            $fund_log->status               = '1';
            $fund_log->created_by           = auth()->user()->id;
            $fund_log->save();


            $bankAccount = BankAccount::where('id', $liquidation->account_id)->first();
            if ($bankAccount) {
                $bankAccount->current_balance += (float)$liquidation->liquidation_amount;
                $bankAccount->update();
                $bank_fund = FundCurrentBalance::where(['fund_id' => $liquidation->fund_id, 'status' => 1])->where('bank_id', $liquidation->bank_id)->first();
                if ($bank_fund) {
                    $bank_fund->amount +=   $liquidation->liquidation_amount;
                    $bank_fund->updated_by = auth()->user()->id;
                    $bank_fund->update();
                } else {
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $liquidation->fund_id;
                    $fund_current_balance->bank_id = $liquidation->bank_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $liquidation->liquidation_amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            } else {
                $fund = FundCurrentBalance::where(['fund_id' => $liquidation->fund_id, 'company_id' => Session::get('company_id'), 'status' => 1])->first();
                if ($fund != null) {
                    $fund->amount += $liquidation->liquidation_amount;
                    $fund->updated_by = auth()->user()->id;
                    $fund->update();
                } else {
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $liquidation->fund_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $liquidation->liquidation_amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }

            $msg = "Asset Liquidation Done.";
        }

        $current_stock->decrement('quantity', $request->quantity);

        return redirect()->route('asset_stock')->with('status', $msg);
    }

    public function asset_liquidation_list(Request $request)
    {
        $data['main_menu']      = 'asset_management';
        $data['child_menu']     = 'asset_liquidation_list';
        $data['fund_types'] = Fund::all();
        $data['companies'] = Company::where('status', 1)->get();
        $data['banks'] = Bank::get();
        $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();
        $groups = AssetStock::with('group')->where('company_id', Session::get('company_id'))->pluck('asset_group_id')->toArray();
        $data['asset_groups']     = AssetGroup::with('asset_category')->where('company_id', Session::get('company_id'))->whereIn('id', $groups)->get();
        $assets = AssetStock::with('asset')->where('company_id', Session::get('company_id'))->pluck('asset_id')->toArray();
        $data['asset']     = Asset::whereIn('id', $assets)->where('company_id', Session::get('company_id'))->get();
        $asset_liquidation = AssetLiquidation::with('asset', 'asset_group', 'fund', 'bank', 'company', 'account')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->asset_id) {
            $asset_liquidation->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            });
        }
        if ($request->asset_group_id) {
            $asset_liquidation->whereHas('asset_group', function ($query) use ($request) {
                $query->where('asset_group_id', $request->asset_group_id);
            });
        }
        if ($request->asset_code) {
            $asset_liquidation->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_liquidation->whereDate('liquidation_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_liquidation->whereDate('liquidation_date', '<=', $request->end_date);
        }
        $asset_liquidation                   = $asset_liquidation->paginate(10);
        $asset_liquidation->appends($where);
        $data['assets_liquidation']     = $asset_liquidation;
        return view('asset.asset_liquidation', $data);
    }

    public function asset_liquidation_print(Request $request)
    {
        $asset_liquidation = AssetLiquidation::with('asset', 'asset_group')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->asset_id) {
            $asset_liquidation->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            });
        }
        if ($request->asset_group_id) {
            $asset_liquidation->whereHas('asset_group', function ($query) use ($request) {
                $query->where('asset_group_id', $request->asset_group_id);
            });
        }
        if ($request->asset_code) {
            $asset_liquidation->whereHas('asset', function ($query) use ($request) {
                $query->where('asset_code', $request->asset_code);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $asset_liquidation->whereDate('liquidation_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $asset_liquidation->whereDate('liquidation_date', '<=', $request->end_date);
        }
        $asset_liquidation                   = $asset_liquidation->get();
        $data['assets_liquidation']      = $asset_liquidation;

        return view('asset.asset_liquidation_print', $data);
    }

    public function update_liquidation_asset(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'quantity'           => 'required|integer|min:0',
            'liquidation_amount' => 'nullable|numeric|min:0',
            'liquidation_date'   => 'nullable|date',
        ]);

        $assetId = $request->asset_id;
        $liquidation_quantity = $request->quantity;

        $total_liquidation_quantities = AssetLiquidation::where('asset_id', $assetId)
            ->where('company_id', Session::get('company_id'))
            ->groupBy('asset_id')
            ->selectRaw('MIN(id) as id, asset_id, sum(quantity) as total_quantity')
            ->get();
        $total_liquidation_amounts = AssetLiquidation::where('asset_id', $assetId)
            ->where('company_id', Session::get('company_id'))
            ->groupBy('asset_id')
            ->selectRaw('MIN(id) as id, asset_id, sum(liquidation_amount) as total_amount')
            ->get();
        // dd($total_liquidation_amount);
        $li_a_log_id = LiquidationActivityLog::with('liquidation')->where('liquidation_id', $id)->where('company_id', Session::get('company_id'))->orderByDesc('id')->limit(1)->firstOrFail();

        $current_stock = AssetStock::where('asset_id', $assetId)->where('company_id', Session::get('company_id'))->first();
        $current_stock_quantity = $current_stock->quantity;
        $total_liquidation_quantity = $total_liquidation_quantities->first()['total_quantity'] ?? 0;
        $total_liquidation_amount = $total_liquidation_amounts->first()['total_amount'] ?? 0;
        // dd($total_liquidation_amount);

        if (!$current_stock || ($current_stock_quantity + $total_liquidation_quantity) < ($total_liquidation_quantity - $li_a_log_id->quantity + $liquidation_quantity)) {
            return redirect()->back()->with('error', 'Insufficient stock quantity.');
        }

        try {
            $liquidation = AssetLiquidation::find($id);
            $old_amount = $liquidation->liquidation_amount;
            $liquidationLog = LiquidationActivityLog::where('company_id', Session::get('company_id'))->where('liquidation_id', $liquidation->id)->orderByDesc('id')->limit(1)->first();
            // dd($liquidationLog);
            // dd($liquidation);
            $liquidation->update([
                'asset_id'          => $assetId,
                'asset_group_id'    => $request->asset_group_id,
                'liquidation_amount' => $total_liquidation_amount - $li_a_log_id->liquidation_amount + $request->liquidation_amount,
                'liquidation_date'  => $request->liquidation_date,
                'description'       => $request->description,
                'quantity'          => $total_liquidation_quantity - $li_a_log_id->quantity + $liquidation_quantity,
            ]);

            // dd($liquidation);

            $updated_stock_quantity = $current_stock->quantity + $total_liquidation_quantity - $liquidation->quantity;
            $current_stock->update(['quantity' => $updated_stock_quantity]);

            LiquidationActivityLog::where('liquidation_id', $liquidation->id)->where('company_id', Session::get('company_id'))->where('id', $liquidationLog->id)->update([
                'liquidation_date' => $request->liquidation_date,
                'quantity' => $liquidation_quantity,
                'liquidation_amount' => $request->liquidation_amount,
                'description' => $request->description,
            ]);

            $fund_log = FundLog::with('liquidation')->where('transection_id', $li_a_log_id->id)->where('transection_type', 'asset_liquidation')->firstOrFail();
            $fund_log->transection_date = $request->liquidation_date;
            $fund_log->amount = $request->liquidation_amount;
            $fund_log->update();

            $bankAccount = BankAccount::where('id', $liquidation->account_id)->first();
            if ($bankAccount) {
                $old_acc_balance = $bankAccount->current_balance;
                $diff = $old_acc_balance - $old_amount;
                $bankAccount->current_balance = $diff + (float)$liquidation->liquidation_amount;
                $bankAccount->update();
                $bank_fund = FundCurrentBalance::where(['fund_id' => $liquidation->fund_id, 'status' => 1])->where('bank_id', $liquidation->bank_id)->first();

                if ($bank_fund) {
                    $old_fund_bank = $bank_fund->amount;
                    $dif = $old_fund_bank - $old_amount;
                    $bank_fund->amount = $dif + $liquidation->liquidation_amount;
                    $bank_fund->updated_by = auth()->user()->id;
                    $bank_fund->update();
                } else {
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $liquidation->fund_id;
                    $fund_current_balance->bank_id = $liquidation->bank_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $liquidation->liquidation_amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            } else {
                $fund = FundCurrentBalance::where(['fund_id' => $liquidation->fund_id, 'company_id' => Session::get('company_id'), 'status' => 1])->first();

                if ($fund) {
                    $old_fund = $fund->amount;
                    $dif_fund = $old_fund - $old_amount;
                    $fund->amount = $dif_fund + $liquidation->liquidation_amount;
                    $fund->updated_by = auth()->user()->id;
                    $fund->update();
                } else {
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $liquidation->fund_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $liquidation->liquidation_amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }

            return redirect()->route('asset_liquidation_list')->with('status', 'Liquidation asset updated successfully.');
        } catch (\Exception $e) {
            // dd($e);
            Log::error('Error updating liquidation asset: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update liquidation asset.');
        }
    }


    public function getAssetTypeWiseLifeTime(Request $request)
    {
        $asset_type = $request->input('asset_type');
        return response()->json(['asset_type' => $asset_type]);
    }

    //Revoke Asset
    public function save_revoke_asset(Request $request, $id)
    {
        $request->validate([
            'quantity'           => 'required|integer|min:1',
        ], [
            'quantity.required' => 'Please provide the quantity.',
        ]);

        $assetId = $request->asset_id;
        $revoke_quantity = $request->quantity;;

        $assignedAsset = AssetAssign::where('asset_id', $assetId)
            ->where('employee_id', $request->employee_id)
            ->where('company_id', Session::get('company_id'))
            ->first();
        if ($assignedAsset) {
            $assignedAsset->quantity -= $revoke_quantity;
            $assignedAsset->status = 'revoke';
            $assignedAsset->save();
            // $assignedAsset->delete();

        }

        $revoke = AssetRevoke::create([
            'asset_id'     => $assetId,
            'quantity'     => $revoke_quantity,
            'asset_group_id'  => $request->asset_group_id,
            'reason'  => $request->input('reason'),
            'revoke_date'  => $request->input('revoke_date'),
            'employee_id'  => $request->input('employee_id'),
            'company_id' => Session::get('company_id'),
        ]);

        $revokeDamageEmployee = AssetDamage::where('asset_id', $assetId)
            ->where('company_id', Session::get('company_id'))
            ->where('employee_id', $request->employee_id)
            ->first();
        if ($revokeDamageEmployee) {
            $revokeDamageEmployee->status = 'revoke';
            $revokeDamageEmployee->save();
        }

        $revokeLostEmployee = AssetLost::where('asset_id', $assetId)
            ->where('employee_id', $request->employee_id)
            ->where('company_id', Session::get('company_id'))
            ->first();
        if ($revokeLostEmployee) {
            $revokeLostEmployee->status = 'revoke';
            $revokeLostEmployee->save();
        }

        if ($revoke) {
            $msg = "Asset Revoked.";
        } else {
            return redirect()->route('asset_assign_list')->with('message', 'Asset Revoke Failed.');
        }
        return redirect()->route('asset_assign_list')->with('status', $msg);
    }

    public function revoke_list(Request $request)
    {
        $data['main_menu']      = 'asset_management';
        $data['child_menu']     = 'asset_revoke_list';
        // $employees = Employee::join('asset_assign', function ($join) {
        //     $join->on('employees.id', '=', 'asset_assign.employee_id');
        // })
        //     ->join('asset_stocks', function ($join) {
        //         $join->on('asset_stocks.asset_id', '=', 'asset_assign.asset_id')
        //             ->on('asset_stocks.asset_group_id', '=', 'asset_assign.asset_group_id');
        //     })
        //     ->where('employees.company_id', Session::get('company_id'))
        //     ->where('asset_assign.asset_type', 'Fixed')
        //     ->pluck('employees.id')
        //     ->toArray();

        // $data['employees'] = Employee::whereIn('id', $employees)->where('company_id', Session::get('company_id'))->get();

        $revoke = AssetRevoke::with('asset', 'employee')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        // if ($request->employee_id) {
        //     $revoke->whereHas('employee', function ($query) use ($request) {
        //         $query->where('employee_id', $request->employee_id);
        //     });
        // }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $revoke->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $revoke->whereDate('created_at', '<=', $request->end_date);
        }
        $revoke                   = $revoke->paginate(10);
        $revoke->appends($where);
        $data['revokes']     = $revoke;
        return view('asset.revoke', $data);
    }

    public function print_revoke_list(Request $request)
    {
        $revoke = AssetRevoke::with('asset', 'employee')->where('company_id', Session::get('company_id'))->orderByDesc('id');
        $where = array();
        if ($request->employee_id) {
            $revoke->whereHas('employee', function ($query) use ($request) {
                $query->where('employee_id', $request->employee_id);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $revoke->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $revoke->whereDate('created_at', '<=', $request->end_date);
        }
        $revoke                   = $revoke->get();
        $data['revokes']      = $revoke;

        return view('asset.revoke_print', $data);
    }
}
