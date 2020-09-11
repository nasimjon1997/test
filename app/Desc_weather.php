<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desc_weather extends Model
{
    protected $connection='mysql_weatheralerts';
    protected $table='desc_weather';
    protected $fillable=[
        'character_eng',
        'character_taj',
    ];
}
