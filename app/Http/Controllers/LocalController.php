<?php

namespace App\Http\Controllers;

use App\Local_cottonindex;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LocalController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:local-read');
        $this->middleware('permission:local-create', ['only' => ['create','store']]);
        $this->middleware('permission:local-update', ['only' => ['edit','update']]);
        $this->middleware('permission:local-delete', ['only' => ['destroy']]);
    }

    public function create(){
        $suppliers=Supplier::all();
        return view('pages.local-cottonindex.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),Local_cottonindex::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }

        $local=new Local_cottonindex($request->all());
        $local->date=Carbon::parse($request->get('date'))->format('Y-m-d');
        $local->save();
        Session::flash('create','');
        return redirect(route('local-cottonindex-index'));
    }

    public function index()
    {
        $locals = Local_cottonindex::with('suppliers')->orderBy('date', "desc");
        if (session()->has('from_local')) {
            $locals = $locals->where('date', '>=', Carbon::parse(session()->get('from_local'))->format('Y-m-d'));
        }
        if (session()->has('to_local')) {
            $locals = $locals->where('date', '<=', Carbon::parse(session()->get('to_local'))->format('Y-m-d'));
        }
        $locals = $locals->paginate(config("custom.num_of_records"));
        return view('pages.local-cottonindex.index', compact('locals'));
    }

    public function edit($id)
    {

        $local=Local_cottonindex::findOrFail($id);
        $local->sana=Carbon::parse($local->sana)->format('d.m.Y');
        $suppliers=Supplier::all();
        return view('pages.local-cottonindex.edit', compact('local','suppliers'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),Local_cottonindex::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $local=Local_cottonindex::findOrFail($id);
        $local->date=Carbon::parse($request->get('date'))->format('Y-m-d');
        $local->supplier_id=$request->get('supplier_id');
        $local->price=$request->get('price');
        $local->update();
        Session::flash('update','');
        return redirect('local-cottonindex');
    }

    public function destroy($id)
    {
        $local=Local_cottonindex::findOrFail($id);
        $local->delete();
        Session::flash('destroy','');
        return redirect('local-cottonindex');
    }

    public function filter(Request $request)
    {
        $input=$request->all();
        if (isset($input['from_local'])) {
            if (($input['from_local']) ==null) {
                session()->forget('from_local');
            } else {
                session()->put("from_local", $input['from_local']);
            }
        }
        if (isset($input['to_local'])) {
            if (($input['to_local']) == null) {
                session()->forget('to_local');
            } else {
                session()->put("to_local", $input['to_local']);
            }
        }
        return redirect('local-cottonindex');
    }

    public function delete_filter()
    {
        session()->forget('from_local');
        session()->forget('to_local');
        session()->save();
        return redirect('local-cottonindex');
    }

}