<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryMarketPrice extends Model
{
    protected $connection="mysql_products_price";
    protected $table="countries";
    protected $primaryKey='_id';
    protected $fillable=[
        'country_id',
        'language_id',
        'name',
        'country_code',
        'currency',
        'created_by'
    ];

    public function lang(){
        return $this->belongsTo('App\LanguageMarketPrice', 'language_id','language_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public static $rules=[
        'country_id'=>['required'],
        'language_id'=>['required'],
        'name'=>['required'],
    ];
}
