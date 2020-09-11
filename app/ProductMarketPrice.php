<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMarketPrice extends Model
{
    protected $connection='mysql_products_price';
    protected $table='products';
    protected $primaryKey='_id';
    protected $fillable=[
        'product_id',
        'language_id',
        'category_id',
        'name',
        'unit_id',
        'sort',
        'img',
        'added_by',
    ];

    public function lang(){
        return $this->belongsTo('App\LanguageMarketPrice', 'language_id','language_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'added_by', 'id');
    }

    public function category(){
        return $this->belongsTo('App\CategoryMarketPrice', 'category_id','category_id');
    }

    public function unit(){
        return $this->belongsTo('App\UnitMarketPrice', 'unit_id', 'unit_id');
    }

    public static $rules=[
        'product_id'=>'required',
        'category_id'=>'required',
        'language_id'=>'required',
        'name'=>'required',
        'unit_id'=>'required',
        'photo.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ];
}
