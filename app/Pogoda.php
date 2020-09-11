<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pogoda extends Model
{
    protected $connection='mysql_weatheralerts';
    protected $table='w_pogoda';
    protected $primaryKey='pogoda_id';
    protected $fillable=[
        'sana',
        'pogoda_night',
        'kharacter_night',
        'pogoda_day',
        'kharacter_day',
        'gorod_id',
    ];

    public function gorod(){
        return $this->belongsTo('App\Gorod', 'gorod_id', 'gorod_id');
    }

    public function kharakter_nights(){
        return $this->belongsTo('App\Desc_weather', 'kharakter_night', 'id');
    }

    public function kharakter_days(){
        return $this->belongsTo('App\Desc_weather', 'kharakter_day', 'id');
    }

}
