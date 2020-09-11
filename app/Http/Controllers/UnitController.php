<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:unit-read');
        $this->middleware('permission:unit-create', ['only' => ['create','store']]);
        $this->middleware('permission:unit-update', ['only' => ['edit','update']]);
        $this->middleware('permission:unit-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $units = Unit::orderBy('name_rus')->paginate(config("custom.num_of_records"));
        return view('pages.unit.index', compact('units'));
    }

    public function create()
    {
        return view('pages.unit.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),Unit::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $unit = new Unit($request->all());
        $unit->save();
        Session::flash('create', '');
        return redirect('units');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('pages.unit.edit', compact('unit'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),Unit::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $unit = Unit::findOrFail($id);
        $unit->update($request->all());
        Session::flash('update', '');
        return redirect('units');
    }

    public function destroy($id)
    {
        $unit = Unit::where('unit_id', $id);
        $unit->delete();
        Session::flash('destroy', '');
        return redirect('units');
    }
}
