<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    protected $connection='mysql_products_price';
    protected $table='prices';
    protected $primaryKey='price_id';
    protected $fillable=[
        'product_id',
        'country_id',
        'market_id',
        'price_trade',
        'price_retail',
        'date',
        'added_by',
    ];

    public function product(){
        return $this->belongsTo('App\ProductMarketPrice', 'product_id','product_id');
    }

    public function country(){
        return $this->belongsTo('App\CountryMarketPrice', 'country_id', 'country_id');
    }

    public function market(){
        return $this->belongsTo('App\Market', 'market_id', 'market_id');
    }

    public static $rules=[
        'product_id'=>'required',
        'market_id'=>'required',
        'date'=>'required',
    ];
}
