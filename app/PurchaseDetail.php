<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    use SoftDeletes;
    use \App\BDSM\ModelHelper;

    protected $hidden = ['deleted_at'];

    protected $fillable = ['pur_id','item_id','item_name','item_price','item_capital','item_qty'];

    public $timestamps = false;
}
