<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryMarketPrice extends Model
{
    protected $connection="mysql_products_price";
    protected $table="categories";
    protected $primaryKey='_id';
    protected $fillable=[
        'category_id',
        'language_id',
        'name',
        'sort',
        'added_by'
    ];

    public function lang(){
        return $this->belongsTo('App\LanguageMarketPrice', 'language_id','language_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'added_by', 'id');
    }

    public static $rules=[
        'category_id'=>['required'],
        'language_id'=>['required'],
        'name'=>['required'],
    ];
}
