<?php

namespace App\Http\Controllers;

use App\Cottonindex;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CottonindexController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:cottonindex-read');
        $this->middleware('permission:cottonindex-create', ['only' => ['create','store']]);
        $this->middleware('permission:cottonindex-update', ['only' => ['edit','update']]);
        $this->middleware('permission:cottonindex-delete', ['only' => ['destroy']]);
    }

    public function create()
    {
        return view('pages.cottonindex.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),Cottonindex::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }

        $cottonindex = new Cottonindex($request->all());
        $cottonindex->sana=Carbon::parse($request->get('sana'))->format('Y-m-d');
        $cottonindex->save();
        Session::flash('create', '');
        return redirect('cottonindex');
    }

    public function index()
    {
        //dd(Auth::User()->permissions);
        $cottonindexes =Cottonindex::orderBy('sana', "desc");
        if (session()->has('from')) {
            $cottonindexes =$cottonindexes->where('sana', '>=', Carbon::parse(session()->get('from'))->format('Y-m-d'));
        }
        if (session()->has('to')) {
            $cottonindexes = $cottonindexes->where('sana', '<=', Carbon::parse(session()->get('to'))->format('Y-m-d'));
        }
        $cottonindexes = $cottonindexes->paginate(config("custom.num_of_records"));

        //dd($cottonindexes);
        return view('pages.cottonindex.index', compact('cottonindexes'));
    }

    public function edit($id)
    {
        $cottonindex = Cottonindex::findOrFail($id);
        return view('pages.cottonindex.edit', compact('cottonindex'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),Cottonindex::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }
        $cottonindex = Cottonindex::findOrFail($id);
        $cottonindex->sana=Carbon::parse($request->sana)->format('Y-m-d');
        $cottonindex->cottonindex=$request->get('cottonindex');
        $cottonindex->update();
        Session::flash('update', '');
        return redirect('cottonindex');
    }

    public function destroy($id)
    {

        $cottonindex = Cottonindex::findOrFail($id);
        $cottonindex->delete();
        Session::flash('destroy', '');
        return redirect('cottonindex');
    }

    public function filter(Request $request)
    {
        $input=$request->all();
        if (isset($input['from'])) {
            if (($input['from']) ==null) {
                session()->forget('from');
            } else {
                session()->put("from", $input['from']);
            }
        }
        if (isset($input['to'])) {
            if (($input['to']) == null) {
                session()->forget('to');
            } else {
                session()->put("to", $input['to']);
            }
        }
        return redirect('cottonindex');
    }

    public function delete_filter()
    {
        session()->forget('from');
        session()->forget('to');
        session()->save();
        return redirect('cottonindex');
    }

}
