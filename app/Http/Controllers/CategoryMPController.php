<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryMarketPrice;
use App\LanguageMarketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryMPController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:categorymp-read');
        $this->middleware('permission:categorymp-create', ['only' => ['create','store']]);
        $this->middleware('permission:categorymp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:categorymp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $categories = CategoryMarketPrice::with('lang','user')->paginate(config("custom.num_of_records"));
        return view('pages.categorymp.index', compact('categories'));
    }

    public function create()
    {
        $lang = LanguageMarketPrice::all();
        return view('pages.categorymp.create',compact('lang'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),CategoryMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $category = new CategoryMarketPrice($request->all());
        $category->save();
        Session::flash('create', '');
        return redirect('categorymp');
    }

    public function edit($id)
    {
        $lang = LanguageMarketPrice::all();
        $category = CategoryMarketPrice::findOrFail($id);
        return view('pages.categorymp.edit', compact('category','lang'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),CategoryMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $category = CategoryMarketPrice::findOrFail($id);
        $category->update($request->all());
        Session::flash('update', '');
        return redirect('categorymp');
    }

    public function destroy($id)
    {

        $category = CategoryMarketPrice::findOrFail($id);
        $category->delete();
        Session::flash('destroy', '');
        return redirect('categorymp');
    }

}
