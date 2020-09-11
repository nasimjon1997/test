<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function create()
    {
        return view('pages.role.create');
    }

    public function store(Request $request)
    {
        $rules = ['name' => 'required|unique:roles,name',
            'description' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }

        $role = new Role($request->all());
        $role->save();
        Session::flash('create', '');
        return redirect('roles');
    }

    public function index()
    {
        $roles=Role::all();
        return view('pages.role.index', compact('roles'));
    }

    public function edit($id)
    {
        $role=Role::findOrFail($id);
        return view('pages.role.edit', compact('role'));
    }

    public function update($id, Request $request)
    {
        $role = Role::find($id);
        $role->update($request->all());
        Session::flash('update', '');
        return redirect('roles');
    }

    public function destroy($id)
    {
        $role=Role::findOrFail($id);
        $role->delete();
        Session::flash('destroy', '');
        return redirect('roles');
    }

    public function permission($id)
    {
        $role=Role::findOrFail($id);
        $permissions=Permission::groupBy('controller')->get();
        return view('pages.role.permission', compact('role','permissions'));
    }

    public function update_permissions($id, Request $request)
    {
        $role = Role::findOrFail($id);
        $role->syncPermissions($request->input('permission'));
        return redirect('roles');
    }

}
