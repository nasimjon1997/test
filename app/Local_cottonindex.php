<?php

namespace App;

use App\Supplier;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Local_cottonindex extends Model
{
    use HasRoles;

    protected $connection='mysql';
    protected $guard_name = 'web';
    protected $table="local_cottonindex";
    protected $fillable=[
        'supplier_id',
        'price',
        'date',
    ];

    public function suppliers()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id','supplier_id');
    }

    public static $rules=[
        'date'=>['required'],
        'supplier_id'=>['required'],
        'price'=>['required'],
    ];
}
