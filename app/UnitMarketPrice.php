<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitMarketPrice extends Model
{
    protected $connection="mysql_products_price";
    protected $table="units";
    protected $primaryKey='_id';
    protected $fillable=[
        'unit_id',
        'language_id',
        'short_name',
        'full_name',
        'created_by'
    ];

    public function lang(){
        return $this->belongsTo('App\LanguageMarketPrice', 'language_id','language_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public static $rules=[
        'unit_id'=>['required'],
        'language_id'=>['required'],
    ];
}
