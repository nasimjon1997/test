<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryMarketPrice;
use App\CountryMarketPrice;
use App\LanguageMarketPrice;
use App\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MarketMPController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:marketmp-read');
        $this->middleware('permission:marketmp-create', ['only' => ['create','store']]);
        $this->middleware('permission:marketmp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:marketmp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $market = Market::with('lang','user','country')->paginate(config("custom.num_of_records"));
        return view('pages.marketmp.index', compact('market'));
    }

    public function create()
    {
        $country = CountryMarketPrice::where('language_id','=',2)->get();
        $lang = LanguageMarketPrice::all();
        return view('pages.marketmp.create',compact('lang','country'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),Market::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $market = new Market($request->all());
        $market->save();
        Session::flash('create', '');
        return redirect('marketmp');
    }

    public function edit($id)
    {
        $lang = LanguageMarketPrice::all();
        $country = CountryMarketPrice::where('language_id','=',2)->get();
        $market = Market::findOrFail($id);
        return view('pages.marketmp.edit', compact('market','lang','country'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),Market::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $market = Market::findOrFail($id);
        $market->update($request->all());
        Session::flash('update', '');
        return redirect('marketmp');
    }

    public function destroy($id)
    {

        $market = Market::findOrFail($id);
        $market->delete();
        Session::flash('destroy', '');
        return redirect('marketmp');
    }

}
