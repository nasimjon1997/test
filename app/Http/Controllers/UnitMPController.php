<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryMarketPrice;
use App\CountryMarketPrice;
use App\LanguageMarketPrice;
use App\UnitMarketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UnitMPController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:unitmp-read');
        $this->middleware('permission:unitmp-create', ['only' => ['create','store']]);
        $this->middleware('permission:unitmp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:unitmp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $unit = UnitMarketPrice::with('lang','user')->paginate(config("custom.num_of_records"));
        return view('pages.unitmp.index', compact('unit'));
    }

    public function create()
    {
        $lang = LanguageMarketPrice::all();
        return view('pages.unitmp.create',compact('lang'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),UnitMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $unit = new UnitMarketPrice($request->all());
        $unit->save();
        Session::flash('create', '');
        return redirect('unitmp');
    }

    public function edit($id)
    {
        $lang = LanguageMarketPrice::all();
        $unit = UnitMarketPrice::findOrFail($id);
        return view('pages.unitmp.edit', compact('unit','lang'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),UnitMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $unit = UnitMarketPrice::findOrFail($id);
        $unit->update($request->all());
        Session::flash('update', '');
        return redirect('unitmp');
    }

    public function destroy($id)
    {

        $unit = UnitMarketPrice::findOrFail($id);
        $unit->delete();
        Session::flash('destroy', '');
        return redirect('unitmp');
    }

}
