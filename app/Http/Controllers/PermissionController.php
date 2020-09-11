<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:permission-read');
        $this->middleware('permission:permission-create', ['only' => ['create','store']]);
        $this->middleware('permission:permission-update', ['only' => ['edit','update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function create()
    {
        return view('pages.permission.create');
    }

    public function store(Request $request)
    {
        $permission=new Permission($request->all());
        $permission->save();
        Session::flash('create', '');
        return redirect('permissions');
    }

    public function index()
    {

        $permissions=Permission::all();
        return view('pages.permission.index', compact('permissions'));
    }

    public function edit($id)
    {
        $permission=Permission::findOrFail($id);
        return view('pages.permission.edit',compact('permission'));
    }

    public function update($id, Request $request)
    {
        $permission=Permission::findOrFail($id);
        $permission->update($request->all());
        Session::flash('update', '');
        return redirect('permissions');

    }

    public function destroy($id)
    {
        $permission=Permission::findOrFail($id);
        $permission->delete();
        Session::flash('destroy', '');
        return redirect('permissions');
    }


}
