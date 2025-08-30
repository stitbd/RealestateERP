<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use Illuminate\Http\Request;

class AccountCategoryController extends Controller
{
    public function index(Request $request){
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'account_category';
        $categories                     = AccountCategory::select();

        if($request->search){
            $searchQuery = trim($request->query('search'));

            $categories->where('category_name', 'like', "%$searchQuery%")->orWhere('category_type', 'like', "%$searchQuery%");

            $data['serachText'] = $searchQuery;

        }

        $categories                     = $categories->paginate(20);
        $data['categories']             = $categories;

        return view('account.category.manage_category',$data);
    }


    public function store(Request $request){
        
        $category_type = json_encode($request->category_type);
        $data = [
            'category_name' => $request->category_name,
            'category_type' => $category_type,
            // 'only_head_office' => $request->only_head_office
        ];
        AccountCategory::create($data);
        $msg = "Category Created Successfully";
        $request->session()->flash('message',$msg);
        return redirect()->back();
    }

    public function update(Request $request, $id){
        $category = AccountCategory::where('id',$id)->first();
        $category_type = json_encode($request->category_type);
        $data = [
            'category_name' => $request->category_name,
            'category_type' => $category_type,
            // 'only_head_office' => $request->only_head_office??0
        ];
        $category->update($data);
        $msg = "Category Data Updated Successfully";
        $request->session()->flash('message',$msg);
        return redirect()->back();
    }


    public function delete($id){
        $category_id = AccountCategory::find($id);
        $category_id->delete();
        return redirect()->route('account-category')->with('success','Category Deleted Successfully');
    }

}
