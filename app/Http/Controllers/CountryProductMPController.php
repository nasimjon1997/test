<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryMarketPrice;
use App\CountryMarketPrice;
use App\CountryProduct;
use App\LanguageMarketPrice;
use App\ProductMarketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CountryProductMPController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:country-productmp-read');
        $this->middleware('permission:country-productmp-create', ['only' => ['create','store']]);
        $this->middleware('permission:country-productmp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:country-productmp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $country = CountryProduct::with('product','country','user')->paginate(config("custom.num_of_records"));
        return view('pages.countryproductmp.index', compact('country'));
    }

    public function create()
    {
        $products = ProductMarketPrice::where('language_id','=',2)->get();
        $countries = CountryMarketPrice::where('language_id','=',2)->get();
        return view('pages.countryproductmp.create',compact('countries','products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),CountryProduct::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $country = new CountryProduct($request->all());
        $country->save();
        Session::flash('create', '');
        return redirect('countrymp');
    }

    public function edit($id)
    {
        $products = ProductMarketPrice::where('language_id','=',2)->get();
        $countries = CountryMarketPrice::where('language_id','=',2)->get();
        $country = CountryProduct::findOrFail($id);
        return view('pages.countryproductmp.edit', compact('country','countries','products'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),CountryProduct::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $country = CountryProduct::findOrFail($id);
        $country->update($request->all());
        Session::flash('update', '');
        return redirect('countrymp');
    }

    public function destroy($id)
    {

        $country = CountryProduct::findOrFail($id);
        $country->delete();
        Session::flash('destroy', '');
        return redirect('countrymp');
    }

}
