<?php

namespace App\Http\Controllers;

use App\Price;
use App\Product;
use App\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PriceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:price-read');
        $this->middleware('permission:price-create', ['only' => ['create','store']]);
        $this->middleware('permission:price-update', ['only' => ['edit','update']]);
        $this->middleware('permission:price-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $prices = Price::with('products', 'shops')->groupBy('shop_id')->groupBy('date')->orderBy('date', "desc");
        $shops=Shop::all();
        if (session()->has('date_price')) {
            $prices = $prices->where('date', '=', Carbon::parse(session()->get('date_price'))->format('Y-m-d'));
        }
        if (session()->has('shop')) {
            $prices = $prices->whereIn("shop_id", session()->get('shop'));
        }
        $prices = $prices->paginate(config('custom.num_of_records'));
        return view('pages.price_product.index', compact('prices', 'shops'));
    }

    public function create()
    {
        $products=Product::select('product_id', 'name_rus')->orderBy('name_rus')->get();
        $shops=Shop::all();
        return view('pages.price_product.create', compact('products', 'shops'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),Price::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        // remove old data
        Price::where(array(
            'shop_id' => Input::get('shop_id'),
            'date' => Carbon::parse(Input::get("date"))->format("Y-m-d")
        ))->delete();

        for ($i = 0; $i < count(Input::get('product_id')); $i++) {
            if(!is_null(Input::get('price')[$i])){
                $price = new Price();
                $price->shop_id = Input::get('shop_id');
                $price->date = Carbon::parse(Input::get('date'))->format('Y-m-d');
                $price->product_id = Input::get('product_id')[$i];
                $price->price = Input::get('price')[$i];
                $price->save();
            }
        }
        Session::flash('create', '');
        return redirect('prices');
    }

    public function edit($shop_id, $date)
    {
        $shop = Shop::where('shop_id', $shop_id)->first();
        $prices = $this->get_data($shop_id, $date);

        return view('pages.price_product.edit', compact('prices', 'shop', 'date'));
    }

    public function get_data($shop_id, $date1)
    {
        $prices = Product::select(
                'product_id',
                'name_rus',
                DB::raw("(SELECT prices.price FROM prices WHERE prices.date = '".Carbon::parse($date1)->format('Y-m-d')."' AND prices.shop_id = ".$shop_id." and prices.product_id = products.product_id ) price ")
            )
            ->orderBy('name_rus')
            ->get();

        return $prices;
    }

    public function filter(Request $request)
    {
        $input=$request->all();
        if (isset($input['date_price'])) {
            if (($input['date_price']) == null) {
                session()->forget('date_price');
            } else {
                session()->put("date_price", $input['date_price']);
            }
        }
        if (isset($input['shop'])) {
            if (($input['shop']) == null) {
                session()->forget('shop');
            } else {
                session()->put("shop", $input['shop']);
            }
        }
        return redirect('prices');
    }

    public function delete_filter()
    {
        session()->forget('date_price');
        session()->forget('shop');
        session()->save();
        return redirect('prices');
    }
}
