<?php
/**
 * Author: Tushar Das
 * Sr. Software Engineer
 * tushar2499@gmail.com 
 * 01815920898
 * STITBD
 */
namespace App\Http\Controllers;
use Session;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\MenuPermission;
use App\Models\Project;
use App\Models\SubMenuPermission;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function permission()
    {
        $data['main_menu']      = 'basic_settings';
        $data['child_menu']     = 'user-permission';
        $data['company']        = Company::all();
        $data['user_data']      = User::with('company')->get();

        return view('basic_settings.user_permission',$data);
    }
    public function user_menu($id){
        $data['menu_permission']= MenuPermission::where('role',$id)->get()->toArray();
        
        $data['sub_menu_permission']= SubMenuPermission::where('role',$id)->get()->toArray();
        //dd(array_search('employee-salary-list', array_column($data['sub_menu_permission'], 'menu_slug')));
        //exit;
        return view('basic_settings.user_menu',$data);
    }

    public function save_permission(Request $request)
    {
        //dd($request);
        $validator = Validator::make($request->all(),[
            'role' => 'required',
            'menu' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
        }

        // $user_id    = $request->user_id;
        $role       = $request->role;
        $menu       = $request->menu;
        $sub_menu   = $request->sub_menu;

        MenuPermission::where('role',$role)->delete();
        SubMenuPermission::where('role',$role)->delete();

        foreach($menu as $menu_value){
            $model = MenuPermission::where('role',$role)->where('menu_slug',$menu_value)->first();
            if($model == null){
                $model              = new MenuPermission();
                $model->role     = $role;
                $model->menu_slug   = $menu_value;
                $model->created_by  = auth()->user()->id;
                $model->save();
            }
        }
        foreach($sub_menu as $sub_menu_value){
            $text = explode('|',$sub_menu_value);
            $menu_value = $text[0];
            $sub_value = $text[1];
            $model = MenuPermission::where('role',$role)->where('menu_slug',$menu_value)->first();
            
            if($model != null){
                $menu_permission_id = $model->id;
                $sub_model = SubMenuPermission::where('role',$role)->where('menu_slug',$sub_value)->where('menu_permission_id',$menu_permission_id)->first();
                if($sub_model == null){
                    $model                      = new SubMenuPermission();
                    $model->role             = $role;
                    $model->menu_permission_id  = $menu_permission_id;
                    $model->menu_slug           = $sub_value;
                    $model->created_by          = auth()->user()->id;
                    $model->save();
                }
            }
        }
        return redirect()->back()->with('success','Permission Saved Successfully');
    }

    public function index()
    {
        $data['main_menu']      = 'basic_settings';
        $data['child_menu']     = 'user';
        $data['company']        = Company::all();
        $data['user_data']      = User::with('company')->get();
        $data['projects']       = Project::all();

        return view('basic_settings.user_list',$data);
    }

    public function store(Request $request){
        $request->validate([
            'name'          => 'required',
            'email'         => 'required',
            'password'      => 'required',
            'role'          => 'required'
        ]);

        $model = new User();
        $model->name            = $request->post('name');
        $model->email           = $request->post('email');
        $model->password        = Hash::make($request->post('password'));
        $model->role            = $request->post('role');
        $model->project_id      = $request->post('project_id');
        $model->company_id      = $request->post('company_id');
        $model->save();

        $msg="User Inserted.";
        $request->session()->flash('message',$msg);

        return redirect('user-list')->with('status', 'User created!');
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = User::find($id);
        $model->status          = $status;
        $model->save();

        $msg="User Status Updated.";
        $request->session()->flash('message',$msg);

        return redirect('user-list')->with('status', 'User Status updated!');
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required',
            'email'     => 'required'
        ]);
        //dd($request->post());
        $model = User::find($request->post('id'));
        $model->name        = $request->post('name');
        $model->email        = $request->post('email');
        $model->role        = $request->post('role');
        $model->project_id    = $request->post('project_id');
        $model->company_id    = $request->post('company_id');

        if(!empty($request->post('password'))){
            $model->password        = Hash::make($request->post('password'));
        }
        $model->save();

        $msg="User Updated.";
        $request->session()->flash('message',$msg);

        return redirect('user-list')->with('status', 'User updated!');
    }
}
