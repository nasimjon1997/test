<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection="mysql_agroshop";
    protected $table="categories";
    protected $primaryKey='category_id';
    protected $fillable=[
        'name_rus',
        'name_taj',
        'name_eng',
        'name_uzb'
    ];

    public static $rules=[
        'name_taj'=>['required'],
        'name_rus'=>['required'],
        'name_eng'=>['required'],
        'name_uzb'=>['required'],
    ];
}
