<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gorod extends Model
{
    protected $connection='mysql_weatheralerts';
    protected $table='w_gorod';
    protected $primaryKey='gorod_id';
    protected $fillable=[
        'name_rus',
        'name_taj',
        'name_eng',
        'name_uzb',
        'oblast_id',
        'locationkey',
    ];

    public function oblast()
    {
        return $this->belongsTo('App\Oblast', 'oblast_id', 'id');
    }



}
