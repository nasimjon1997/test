<?php

namespace App\Http\Controllers;
use App\CategoryMarketPrice;
use App\CountryMarketPrice;
use App\LanguageMarketPrice;
use App\Market;
use App\MarketPrice;
use App\ProductMarketPrice;
use App\UnitMarketPrice;

class MobileController extends Controller
{


    public function list_language()
    {
        $language = LanguageMarketPrice::selectRaw("language_id, name, lang_code")
            ->get();
        return response()->json($language);
    }

    public function list_country()
    {
        $countries = CountryMarketPrice::selectRaw("country_id, name, currency,language_id")
            ->get();
        return response()->json($countries);
    }

    public function list_category($lang_id)
    {
        $categories = CategoryMarketPrice::selectRaw("category_id, name, sort")
            ->where('language_id', $lang_id)
            ->get();
        return response()->json($categories);
    }

    public function list_unit($lang_id)
    {
        $unit = UnitMarketPrice::selectRaw("unit_id, short_name,full_name")
            ->where('language_id', $lang_id)
            ->get();
        return response()->json($unit);
    }

    public function list_market($country_id)
    {
        $market = Market::selectRaw("market_name,city_name,sort,market_id,country_id,language_id")
            ->whereRaw("country_id=$country_id")
            ->get();
        return response()->json($market);
    }

    public function list_product($country_id)
    {
        $products = ProductMarketPrice::selectRaw("distinct products.product_id,products.category_id,c.country_id,products.language_id, products.name,products.unit_id,products.img")
            ->leftJoin('country_product', 'country_product.product_id', 'products.product_id')
            ->leftJoin('countries as c', 'c.country_id', 'country_product.country_id')
            ->where('c.country_id', $country_id)
            ->get();
        return response()->json($products);
    }

    public function list_price($country_id, $markets, $products, $date)
    {
        if ($markets == 0) {
            $markets = array();
        } else {
            $markets = explode(',', $markets);
        }
        if ($products == 0) {
            $products = array();
        } else {
            $products = explode(',', $products);
        }
        $query = MarketPrice::selectRaw("country_id,market_id,product_id,price_trade,price_retail,date")
            ->where('country_id', $country_id);

        if (is_array($markets) || is_object($markets)) {
            if (count($markets) != 0) {
                $query = $query->whereIn('market_id', $markets);
            }
        }
        if (is_array($products) || is_object($products)) {
            if (count($products) != 0) {
                $query = $query->whereIn('product_id', $products);
            }
        }
        if ($date == 1) {
            $query = $query->whereRaw('date=(select date from prices order by date desc limit 1)');
        } else {
            $query = $query->where('date', $date);
        }
        $prices = $query->get();
        return response()->json($prices);
    }
}