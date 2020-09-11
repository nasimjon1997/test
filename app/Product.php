<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection="mysql_agroshop";
    protected $table="products";
    protected $primaryKey='product_id';
    protected $fillable=[
        'name_rus',
        'name_taj',
        'name_eng',
        'name_uzb',
        'category_id',
        'unit_id'

    ];

    public function categories()
    {
        return $this->belongsTo("App\Category", 'category_id', 'category_id');
    }

    public function units()
    {
        return $this->belongsTo("App\Unit", 'unit_id', 'unit_id');
    }

    public static $rules=[
        'name_taj'=>['required'],
        'name_rus'=>['required'],
        'name_eng'=>['required'],
        'name_uzb'=>['required'],
        'category_id'=>['required'],
        'unit_id'=>['required']
    ];
}
