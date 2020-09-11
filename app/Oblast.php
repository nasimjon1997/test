<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oblast extends Model
{
    protected $connection='mysql_weatheralerts';
    protected $table='w_oblast';
    protected $primaryKey='oblast_id';
    protected $fillable=[
        'name_rus',
        'name_taj',
        'name_eng',
    ];
}
