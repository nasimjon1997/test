<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryMarketPrice;
use App\LanguageMarketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LanguageMPController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:languagemp-read');
        $this->middleware('permission:languagemp-create', ['only' => ['create','store']]);
        $this->middleware('permission:languagemp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:languagemp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $language = LanguageMarketPrice::with('user')->paginate(config("custom.num_of_records"));
        return view('pages.languagemp.index', compact('language'));
    }

    public function create()
    {
        return view('pages.languagemp.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),LanguageMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $language = new LanguageMarketPrice($request->all());
        $language->save();
        Session::flash('create', '');
        return redirect('languagemp');
    }

    public function edit($id)
    {
        $language = LanguageMarketPrice::findOrFail($id);
        return view('pages.languagemp.edit', compact('language'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),LanguageMarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $language = LanguageMarketPrice::findOrFail($id);
        $language->update($request->all());
        Session::flash('update', '');
        return redirect('languagemp');
    }

    public function destroy($id)
    {

        $language = LanguageMarketPrice::findOrFail($id);
        $language->delete();
        Session::flash('destroy', '');
        return redirect('languagemp');
    }

}
