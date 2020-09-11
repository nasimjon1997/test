<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:category-read');
        $this->middleware('permission:category-create', ['only' => ['create','store']]);
        $this->middleware('permission:category-update', ['only' => ['edit','update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function create()
    {
        return view('pages.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),Category::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        $cottonindex = new Category($request->all());
        $cottonindex->save();
        Session::flash('create', '');
        return redirect('categories');
    }

    public function index()
    {
        $categories = Category::paginate(config("custom.num_of_records"));
        return view('pages.category.index', compact('categories'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.category.edit', compact('category'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),Category::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $category = Category::findOrFail($id);
        $category->update($request->all());
        Session::flash('update', '');
        return redirect('categories');
    }

    public function destroy($id)
    {

        $category = Category::findOrFail($id);
        $category->delete();
        Session::flash('destroy', '');
        return redirect('categories');
    }

}
