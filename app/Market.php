<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $connection='mysql_products_price';
    protected $table='markets';
    protected $primaryKey='_id';
    protected $fillable=[
        'market_id',
        'language_id',
        'country_id',
        'market_name',
        'city_name',
        'api_market_id',
        'sort',
        'added_by',
    ];

    public function lang(){
        return $this->belongsTo('App\LanguageMarketPrice', 'language_id','language_id');
    }

    public function country(){
        return $this->belongsTo('App\CountryMarketPrice', 'country_id','country_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'added_by', 'id');
    }

    public static $rules=[
        'market_id'=>['required'],
        'language_id'=>['required'],
        'country_id'=>['required'],
        'market_name'=>['required'],
    ];
}
