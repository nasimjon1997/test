<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-read');
        $this->middleware('permission:product-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-update', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $products = Product::with('categories', 'units')->orderBy('name_rus')->paginate(config("custom.num_of_records"));
        return view('pages.product.index', compact('products'));
    }

    public function create()
    {
        $categories=Category::all();
        $units=Unit::all();
        return view('pages.product.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),Product::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }

        $product = new Product($request->all());
        $product->save();
        Session::flash('create', '');
        return redirect('products');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories=Category::all();
        $units=Unit::all();
        return view('pages.product.edit', compact('product','categories', 'units'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),Product::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $product = Product::findOrFail($id);
        $product->update($request->all());
        Session::flash('update', '');
        return redirect('products');
    }

    public function destroy($id)
    {

        $product = Product::findOrFail($id);
        $product->delete();
        Session::flash('destroy', '');
        return redirect('products');
    }
}
