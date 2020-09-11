<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Cottonindex extends Model
{
    use HasRoles;

    protected $connection='mysql';
    protected $guard_name = 'web';
    protected $table='cottonindex';
    protected $fillable=[
        "sana",
        "cottonindex",
    ];

    public static $rules=[
        'sana'=>['required'],
        'cottonindex'=>['required'],
    ];
}
