<?php

namespace App\Http\Controllers;

use App\CategoryMarketPrice;
use App\LanguageMarketPrice;
use App\ProductMarketPrice;
use App\UnitMarketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductMPController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:productmp-read');
        $this->middleware('permission:productmp-create', ['only' => ['create','store']]);
        $this->middleware('permission:productmp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:productmp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $product = ProductMarketPrice::with('lang','user','category','unit')->paginate(config("custom.num_of_records"));
        return view('pages.productmp.index', compact('product'));
    }

    public function create()
    {
        $unit=UnitMarketPrice::where('language_id','=',2)->get();
        $category=CategoryMarketPrice::where('language_id','=',2)->get();
        $lang = LanguageMarketPrice::all();
        return view('pages.productmp.create',compact('lang','category','unit'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),ProductMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $product = new ProductMarketPrice($request->all());
        $product->product_id=$request->get('product_id');
        $product->language_id=$request->get('language_id');
        $product->category_id=$request->get('category_id');
        $product->name=$request->get('name');
        $product->unit_id=$request->get('unit_id');
        $product->sort=$request->get('sort');

        if(isset($request->img)) {
            $photo = $request->file('img');
            $name = time() . $photo->getClientOriginalName();
            $photo->move(public_path() . '/images/product/', $name);
            $product->img = $name;
        }
        $product->save();
        Session::flash('create', '');
        return redirect('productmp');
    }

    public function edit($id)
    {
        $unit=UnitMarketPrice::where('language_id','=',2)->get();
        $category=CategoryMarketPrice::where('language_id','=',2)->get();
        $lang = LanguageMarketPrice::all();
        $product = ProductMarketPrice::findOrFail($id);
        return view('pages.productmp.edit', compact('product','lang','unit','category'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),ProductMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $product = ProductMarketPrice::findOrFail($id);
        $product->product_id=$request->get('product_id');
        $product->language_id=$request->get('language_id');
        $product->category_id=$request->get('category_id');
        $product->name=$request->get('name');
        $product->unit_id=$request->get('unit_id');
        $product->sort=$request->get('sort');

        if(isset($request->img)) {
            $photo = $request->file('img');
            $name = time() . $photo->getClientOriginalName();
            $photo->move(public_path() . '/images/product/', $name);
            $product->img = $name;
        }
        $product->update();
        Session::flash('update', '');
        return redirect('productmp');
    }

    public function destroy($id)
    {

        $product = ProductMarketPrice::findOrFail($id);
        $product->delete();
        Session::flash('destroy', '');
        return redirect('productmp');
    }

}
