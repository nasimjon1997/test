<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryProduct extends Model
{
    protected $connection="mysql_products_price";
    protected $table="country_product";
    protected $primaryKey='_id';
    protected $fillable=[
        'product_id',
        'country_id',
        'api_product_id',
        'added_by'
    ];

    public function product(){
        return $this->belongsTo('App\ProductMarketPrice', 'product_id','product_id');
    }

    public function country(){
        return $this->belongsTo('App\CountryMarketPrice', 'country_id', 'country_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'added_by', 'id');
    }

    public static $rules=[
        'product_id'=>['required'],
        'country_id'=>['required'],
    ];
}
