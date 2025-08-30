<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use App\Models\AccountHead;
use App\Models\HeadOpeningBalance;
use Illuminate\Http\Request;
use Session;

class AccountHeadController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'account_head';
        $data['categories'] = AccountCategory::all();
        $head                     = AccountHead::with('category')->select();

        if ($request->search) {
            $searchQuery = trim($request->query('search'));

            $head->WhereHas('category', function ($query) use ($searchQuery) {
                $query->where('category_name', 'like', "%$searchQuery%");
            })->orWhere('head_name', 'like', "%$searchQuery%");

            $data['serachText'] = $searchQuery;
        }
        $head                     = $head->paginate(20);
        $data['head']             = $head;
        return view('account.head.manage_head', $data);
    }


    public function store(Request $request)
    {
        $data = [
            'head_name' => $request->head_name,
            'category_id' => $request->category,
            // 'only_head_office' => $request->only_head_office 
        ];
        AccountHead::create($data);
        $msg = "Head Created Successfully";
        $request->session()->flash('message', $msg);
        return redirect()->back();
    }
    public function update(Request $request, $id)
    {
        $head = AccountHead::where('id', $id)->first();
        // dd($request->only_head_office);
        $data = [
            'head_name' => $request->head_name,
            'category_id' => $request->category,
            // 'only_head_office' => $request->only_head_office??0
        ];
        $head->update($data);
        $msg = "Data Updated Successfully";
        $request->session()->flash('message', $msg);
        return redirect()->back();
    }

    public function delete($id)
    {
        $category_id = AccountHead::find($id);
        $category_id->delete();
        return redirect()->route('account-head')->with('success', 'Deleted Successfully');
    }


    ///Head Opening Balance

    public function head_opening_balance_index()
    {
        try {
            $data['main_menu']              = 'accounts';
            $data['child_menu']             = 'account-opening-balance';
            $data['categories']             = AccountCategory::get();
            $data['head']                   = AccountHead::get();
            $data['balance']         = HeadOpeningBalance::with('category', 'head')->where('company_id', session()->get('company_id'))->where('status', 1)->paginate(10);
            return view('account.head.head_opening_balance', $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function head_opening_balance_store(Request $request)
    {
        try {
            // $existingBalance = HeadOpeningBalance::where('head_id', $request->head)->first();

            // if ($existingBalance) {
            //     $existingBalance->amount += $request->amount;
            //     $existingBalance->save();

            //     $msg = "Opening balance already exists and has been updated with the new amount.";
            //     $request->session()->flash('warning', $msg);
            //     return redirect()->back()->with('status', $msg);
            // }

            $balance = new HeadOpeningBalance();
            if ($request->category_name) {
                $category_type = json_encode($request->category_type);
                $category = new AccountCategory();
                $category->category_name = $request->category_name;
                $category->category_type = $category_type;
                $category->save();
                $balance->category_id = $category->id;
            } else {
                $balance->category_id = $request->category;
            }

            if ($request->head_name) {
                $head = new AccountHead();
                $head->category_id = $balance->category_id;
                $head->head_name = $request->head_name;
                $head->save();
                $balance->head_id = $head->id;
            } else {
                $balance->head_id = $request->head;
            }

            $balance->company_id = Session::get('company_id');
            $balance->date = $request->date;
            $balance->amount = $request->amount;
            $balance->remarks = $request->remarks;
            $balance->created_by = auth()->user()->id;
            $balance->status = 1;
            $balance->save();

            $msg = "Head Wise Opening/Previous Balance Added.";
            $request->session()->flash('warning', $msg);

            return redirect()->back()->with('status', $msg);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function head_opening_balance_update(Request $request, $id)
    {
        try {
            $balance = HeadOpeningBalance::findOrFail($id);
            $balance->category_id = $request->category;
            $balance->head_id = $request->head;
            $balance->company_id = Session::get('company_id');
            $balance->date = $request->date;
            $balance->amount = $request->amount;
            $balance->remarks = $request->remarks;
            $balance->updated_by = auth()->user()->id;
            $balance->status = 1;
            $balance->update();
    
            $msg = "Head Wise Opening/Previous Balance Updated.";
            $request->session()->flash('success', $msg);
    
            return redirect()->back()->with('status', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    
}
