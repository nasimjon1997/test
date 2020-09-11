<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LanguageMarketPrice extends Model
{
    protected $connection="mysql_products_price";
    protected $table="languages";
    protected $primaryKey='_id';
    protected $fillable=[
        'language_id',
        'name',
        'lang_code',
        'created_by'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public static $rules=[
        'language_id'=>['required'],
        'name'=>['required'],
        'lang_code'=>['required'],
    ];
}
