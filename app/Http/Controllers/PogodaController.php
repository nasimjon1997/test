<?php

namespace App\Http\Controllers;

use App\Desc_weather;
use App\Gorod;
use App\Pogoda;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;


class PogodaController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:pogoda-read');
        $this->middleware('permission:pogoda-create', ['only' => ['create','store']]);
        $this->middleware('permission:pogoda-update', ['only' => ['edit','update']]);
        $this->middleware('permission:pogoda-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $pogodi = Pogoda::orderBy('sana', "desc")->groupBy('sana');
        $cities = Gorod::all();
        if (session()->has('from_pogoda')) {
            $pogodi = $pogodi->where('sana', '>=', Carbon::parse(session()->get('from_pogoda'))->format('Y-m-d'));
        }
        if (session()->has('to_pogoda')) {
            $pogodi = $pogodi->where('sana', '<=', Carbon::parse(session()->get('to_pogoda'))->format('Y-m-d'));
        }
        $pogodi = $pogodi->paginate(config("custom.num_of_records"));
        return view('pages.pogoda.index', compact('pogodi','cities'));
    }

    public function create()
    {
        $characters=Desc_weather::orderBy('character_taj')->groupBy('character_taj')->get();
        $goroda=Gorod::all();
        return view('pages.pogoda.create', compact('characters', 'goroda'));
    }

    public function store(Request $request)
    {
        $rules=[
          "sana"=>"required",
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        for ($i = 0; $i < count(Input::get('gorod_id')); $i++) {
            $pogoda = new Pogoda();
            $pogoda->gorod_id = Input::get('gorod_id');
            $pogoda->sana = Carbon::parse(Input::get('sana'))->format('Y-m-d');
            $pogoda->kharakter_night = Input::get('kharakter_night')[$i];
            $pogoda->pogoda_night = Input::get('pogoda_night')[$i];
            $pogoda->kharakter_day = Input::get('kharakter_day')[$i];
            $pogoda->pogoda_day = Input::get('pogoda_day')[$i];
            $pogoda->save();
        }
        Session::flash('create', '');
        return redirect('pogoda');
    }

    public function edit($id)
    {
        $characters=Desc_weather::orderBy('character_taj')->groupBy('character_taj')->get();
        $pogoda=Pogoda::where('sana',"=",$id)->first();
        $pogodi=DB::connection('mysql_weatheralerts')->table('w_pogoda')
            ->join('w_gorod', 'w_pogoda.gorod_id', '=', 'w_gorod.gorod_id')
            ->select('w_pogoda.gorod_id','w_pogoda.pogoda_id','w_pogoda.kharakter_night', 'w_pogoda.kharakter_day','w_pogoda.pogoda_night', 'w_pogoda.pogoda_day', 'w_gorod.name_taj')
            ->orderBy('pogoda_id')->where( 'w_pogoda.sana', '=', $pogoda->sana)->get();
        return view('pages.pogoda.edit', compact('pogoda', 'pogodi', 'characters'));
    }

    public function update($id,Request $request)
    {
        for ($i=0; $i < count($request['gorod_id']); $i++)
        {
            $pogoda = Pogoda::where([
                ['sana', '=', $id],
                ['gorod_id', '=', $request['gorod_id'][$i]]
            ])->first();

            $pogoda->kharakter_night=$request['kharakter_night'][$i];
            $pogoda->pogoda_night=$request['pogoda_night'][$i];
            $pogoda->kharakter_day=$request['kharakter_day'][$i];
            $pogoda->pogoda_day=$request['pogoda_day'][$i];
            $pogoda->update();
        }

        Session::flash('update', '');
        return redirect('pogoda');
    }

    public function destroy($id)
    {
        Session::flash('destroy', '');
        $pogoda=Pogoda::findOrFail($id);
        $pogoda->delete();
        return redirect('pogoda');
    }

    public function import(Request $request)
    {
        $rules=array(
            'file'=>'required'
        );
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        if ($request->hasFile('file')) {
            $inputFileName = $request->file('file');
            $sheetname = 'prognoz_pogodi';
            $inputFileType = IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $reader->setLoadSheetsOnly($sheetname);
            $spreadsheets = $reader->load($inputFileName);
            $worksheet = $spreadsheets->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $pogoda = new Pogoda();
                $pogoda['gorod_id'] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $pogoda['sana'] = Carbon::parse($worksheet->getCellByColumnAndRow(3, $row)->getFormattedValue())->format('Y-m-d');
                //dd($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $pogoda['kharakter_night'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $pogoda['pogoda_night'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $pogoda['kharakter_day'] = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $pogoda['pogoda_day'] = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $pogoda->save();
                Session::flash('create', '');
            }
        }
        return redirect('pogoda');
    }

    public function filter(Request $request)
    {
        $input=$request->all();
        //dd($request->get('city'));
        if (isset($input['from_pogoda'])) {
            if (($input['from_pogoda']) ==null) {
                session()->forget('from_pogoda');
            } else {
                session()->put("from_pogoda", $input['from_pogoda']);
            }
        }
        if (isset($input['to_pogoda'])) {
            if (($input['to_pogoda']) == null) {
                session()->forget('to_pogoda');
            } else {
                session()->put("to_pogoda", $input['to_pogoda']);
            }
        }
        //dd(session()->get('city'));
        return redirect('pogoda');
    }

    public function delete_filter()
    {
        session()->forget('from_pogoda');
        session()->forget('to_pogoda');
        session()->save();
        return redirect('pogoda');
    }


}
