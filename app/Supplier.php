<?php

namespace App;

use App\Local_cottonindex;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Supplier extends Model
{
    use HasRoles;

    protected $connection='mysql';
    protected $guard_name = 'web';
    protected $table='suppliers';
    protected $primaryKey='supplier_id';
    protected $fillable=[
        'name',
        'responsible_person',
        'telefon'
    ];

    public static $rules=[
        'name'=>['required'],
        'responsible_person'=>['required'],
        'telefon'=>['required'],
    ];
}
