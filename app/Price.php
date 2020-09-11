<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $connection="mysql_agroshop";
    protected $table="prices";
    protected $primaryKey='price_id';
    protected $fillable=[
        'product_id',
        'shop_id',
        'date',
        'price',

    ];

    public function products()
    {
        return $this->belongsTo("App/Product", 'product_id', 'product_id');
    }

    public function shops()
    {
        return $this->belongsTo("App/Shop", 'shop_id', 'shop_id');
    }

    public static $rules=[
        'shop_id'=>['required'],
        'date'=>['required'],
    ];
}
