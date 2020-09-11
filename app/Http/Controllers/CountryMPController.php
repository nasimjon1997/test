<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryMarketPrice;
use App\CountryMarketPrice;
use App\LanguageMarketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CountryMPController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:countrymp-read');
        $this->middleware('permission:countrymp-create', ['only' => ['create','store']]);
        $this->middleware('permission:countrymp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:countrymp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $country = CountryMarketPrice::with('lang','user')->paginate(config("custom.num_of_records"));
        return view('pages.countrymp.index', compact('country'));
    }

    public function create()
    {
        $lang = LanguageMarketPrice::all();
        return view('pages.countrymp.create',compact('lang'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),CountryMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $country = new CountryMarketPrice($request->all());
        $country->save();
        Session::flash('create', '');
        return redirect('countrymp');
    }

    public function edit($id)
    {
        $lang = LanguageMarketPrice::all();
        $country = CountryMarketPrice::findOrFail($id);
        return view('pages.countrymp.edit', compact('country','lang'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),CountryMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $country = CountryMarketPrice::findOrFail($id);
        $country->update($request->all());
        Session::flash('update', '');
        return redirect('countrymp');
    }

    public function destroy($id)
    {

        $country = CountryMarketPrice::findOrFail($id);
        $country->delete();
        Session::flash('destroy', '');
        return redirect('categorymp');
    }

}
