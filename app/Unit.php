<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $connection="mysql_agroshop";
    protected $table="units";
    protected $primaryKey='unit_id';
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
