<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:user-read');
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-update', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function create()
    {
        $roles = Role::all();
        return view('pages.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $rules = [
            'email'=>'unique:users',
            'password'=>'min:6',
            'password_confirmation' => 'same:password|min:6',
            'photo.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return back()->withErrors($validator)
                ->withInput();
        }

        $user= new User;
        if($request->hasfile('photo'))
        {
            $photo = $request->file('photo');
            $photo_name = time().$photo->getClientOriginalName();
            $photo->move(public_path().'/images/', $photo_name);
            $user->photo = $photo_name;
        }
        $user->fio = $request->get('fio');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->save();
        $user->roles()->attach($request->input('roles'));
        Session::flash('create','');
        return redirect('users');
    }

    public function index()
    {
        $users=User::all();
        return view('pages.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user=User::findOrFail($id);
        $roles=Role::all();
        $userRole=$user->roles->all();
        return view('pages.users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update($id,Request $request)
    {
        $user=User::findOrFail($id);
        $request->validate([
            'photo.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if(isset($request->photo)) {
            $photo = $request->file('photo');
            $name = time() . $photo->getClientOriginalName();
            $photo->move(public_path() . '/images/', $name);
            $user->photo = $name;
        }
        $user->fio=$request->get('fio');
        if ($request->get('password')<>"")
        {
            $user->password=bcrypt($request->get('password'));
        }

        $user->update();
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->roles()->attach($request->input('roles'));
        Session::flash('update','');
        return redirect('users');

    }

    public function destroy($id)
    {
        $user=User::findOrFail($id);
        $user->delete();
        Session::flash('destroy','');
        return redirect('users');
    }

    public function permission($id)
    {
        $user=User::findOrFail($id);
        $user_permissions = $user->permissions->pluck('id')->toArray();
        $permissions=Permission::groupBy('controller')->get();
        $role_permissions=$user->getPermissionsViaRoles ();
        return view('pages.users.permission',compact('user','user_permission','permissions','role_permissions'));
    }

    public function update_permissions($id,Request $request)
    {
        $user=User::findOrFail($id);
        $user->syncPermissions($request->input('permission'));
        return redirect('users');
    }
}
