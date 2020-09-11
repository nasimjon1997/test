<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $connection="mysql_agroshop";
    protected $table="shops";
    protected $primaryKey='shop_id';
    protected $fillable=[
        'name_rus',
        'name_taj',
        'name_eng',
        'name_uzb',
        'region',
        'contact_person',
        'telefon'

    ];

    public static $rules=[
        'name_taj'=>['required'],
        'name_rus'=>['required'],
        'name_eng'=>['required'],
        'name_uzb'=>['required'],
        'region'=>['required'],
        'contact_person'=>['required'],
        'telefon'=>['required'],
    ];
}
