<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:supplier-read');
        $this->middleware('permission:supplier-create', ['only' => ['create','store']]);
        $this->middleware('permission:supplier-update', ['only' => ['edit','update']]);
        $this->middleware('permission:supplier-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $suppliers=Supplier::orderBy('created_at', 'desc')->paginate(config('custom.num_of_records'));
        return view('pages.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('pages.supplier.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),Supplier::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }

        $supplier=new Supplier($request->all());
        $supplier->save();
        Session::flash('create','');
        return redirect('suppliers');
    }

    public function edit($id)
    {
        $supplier = Supplier::where('supplier_id', $id)->first();
        return view('pages.supplier.edit',compact('supplier'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),Supplier::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $supplier=Supplier::findOrFail($id);
        $supplier->update($request->all());
        Session::flash('update','');
        return redirect('suppliers');
    }

    public function destroy($id)
    {
        $supplier=Supplier::findOrFail($id);
        $supplier->delete();
        Session::flash('destroy','');
        return redirect('suppliers');
    }
}
