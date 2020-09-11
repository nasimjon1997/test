<?php

namespace App\Http\Controllers;

use App\CountryMarketPrice;
use App\LanguageMarketPrice;
use App\Market;
use App\MarketPrice;
use App\ProductMarketPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MarketPriceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:marketPrice-read');
        $this->middleware('permission:marketPrice-create', ['only' => ['create','store']]);
        $this->middleware('permission:marketPrice-update', ['only' => ['edit','update']]);
    }

    public function index()
    {
        $market=Market::where('language_id','=',2)->get();
        $markets = MarketPrice::with(['market'=>function($query){$query->whereRaw('language_id=2');}])->groupBy('market_id')->groupBy('date')->orderBy('date', "desc");
        if (session()->has('date_market')) {
            $markets = $markets->where('date', '=', Carbon::parse(session()->get('date_market'))->format('Y-m-d'));
        }
        if (session()->has('market')) {
            $markets = $markets->whereIn("market_id", session()->get('market'));
        }
        $markets = $markets->paginate(config('custom.num_of_records'));
        return view('pages.market_price.index', compact('markets','market','product','country'));
    }

    public function create()
    {
        $product=ProductMarketPrice::where('language_id','=',2)->get();
        $market=Market::where('language_id','=',2)->get();
        return view('pages.market_price.create', compact('market', 'product'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),MarketPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // remove old data (if exists)
        MarketPrice::where(array(
            'market_id' => $request->get('market_id'),
            'date' => Carbon::parse($request->get('date'))->format("Y-m-d")
        ))->delete();

        for ($i = 0; $i < count(Input::get('product_id')); $i++)
        {
            $price=new MarketPrice();
            $price->country_id = 1;
            $price->market_id = $request->get('market_id');
            $price->date = Carbon::parse($request->get('date'))->format('Y-m-d');
            $price->product_id = $request['product_id'][$i];

            if (!is_null($request['price_trade'][$i])){
                $price->price_trade=$request['price_trade'][$i];
            }

            if (!is_null($request['price_retail'][$i])){
                $price->price_retail=$request['price_retail'][$i];
            }
            $price->added_by = $request->get('added_by');

            $price->save();
        }
        Session::flash('create', '');

        return redirect('market-price');
    }

    public function edit($id_market, $date)
    {

        $markets = MarketPrice::select('price_id', 'market_id', 'product_id', 'price_trade', 'price_retail', 'date')
            ->with(['market' =>function($query){$query->whereRaw('language_id=2');},
                    'product' =>function($query){$query->whereRaw('language_id=2');}]
            )
            ->where(array(
                'date' => $date,
                'market_id' => $id_market
            ))
            ->orderBy('product_id')
            ->get();
        return view('pages.market_price.edit', compact( 'markets'));
    }

    public function update($id_market, $date, Request $request)
    {

        for ($i=0; $i < count($request['product_id']); $i++)
        {
            $promat = MarketPrice::where([
                ['market_id', '=', $id_market],
                ['product_id', '=', $request['product_id'][$i]],
                ['date', '=', Carbon::parse($date)->format('Y-m-d')]
            ])->first();

            $promat->price_trade = $request['price_trade'][$i];
            $promat->price_retail = $request['price_retail'][$i];
            $promat->update();
        }

        Session::flash('update', '');
        return redirect(route('market-price-index'));
    }

    public function get_data($id_market, $date)
    {
        $market['prices'] = MarketPrice::select('price_id', 'market_id', 'product_id', 'price_trade', 'price_retail', 'date')
            ->with(['market' =>function($query){$query->whereRaw('language_id=2');},
                    'product' =>function($query){$query->whereRaw('language_id=2');}]
            )
            ->where([
                ['market_id', '=', $id_market],
                ['date', '=', Carbon::parse($date)->format("Y-m-d")]
            ])
            ->orderBy('product_id')
            ->get();

        $market['products'] = ProductMarketPrice::select('product_id', 'name')->where("language_id",2)->get();
        return response()->json($market);
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $inputFileName = $request->file('file');
            $sheetname = 'Лист1';
            $inputFileType = IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $reader->setLoadSheetsOnly($sheetname);
            $spreadsheets = $reader->load($inputFileName);
            $worksheet = $spreadsheets->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            for ($row = 4; $row <= $highestRow; $row++) {
                $market = new MarketPrice();
                $market['country_id'] =1;
                $market['market_id'] = $request->get('market_id');
                $market['date'] = Carbon::parse($request->get('current_date'))->format('Y-m-d');
                //dd($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $market['product_id'] = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $market['price_trade'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $market['price_retail'] = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $market->save();
            }
            Session::flash('create', '');
        }
        return redirect('market-price');
    }

    public function filter(Request $request)
    {
        $input=$request->all();
        if (isset($input['date_market'])) {
            if (($input['date_market']) == null) {
                session()->forget('date_market');
            } else {
                session()->put("date_market", $input['date_market']);
            }
        }
        if (isset($input['market'])) {
            if (($input['market']) == null) {
                session()->forget('market');
            } else {
                session()->put("market", $input['market']);
            }
        }
        return redirect('market-price');
    }

    public function delete_filter()
    {
        session()->forget('date_market');
        session()->forget('market');
        session()->save();
        return redirect('market-price');
    }
}

