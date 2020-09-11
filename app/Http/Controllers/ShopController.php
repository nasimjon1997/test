<?php

namespace App\Http\Controllers;

use App\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class ShopController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:shop-read');
        $this->middleware('permission:shop-create', ['only' => ['create','store']]);
        $this->middleware('permission:shop-update', ['only' => ['edit','update']]);
        $this->middleware('permission:shop-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $shops = Shop::orderBy('name_rus')->paginate(config("custom.num_of_records"));
        return view('pages.shop.index', compact('shops'));
    }

    public function create()
    {
        return view('pages.shop.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),Shop::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }

        $shop = new Shop($request->all());
        $shop->save();
        Session::flash('create', '');
        return redirect('shops');
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('pages.shop.edit', compact('shop'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),Shop::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $unit = Shop::findOrFail($id);
        $unit->update($request->all());
        Session::flash('update', '');
        return redirect('shops');
    }

    public function destroy($id)
    {

        $shop = Shop::where('shop_id', $id);
        $shop->delete();
        Session::flash('destroy', '');
        return redirect('shops');
    }
}
