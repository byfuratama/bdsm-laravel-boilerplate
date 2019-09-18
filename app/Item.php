<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    use \App\BDSM\ModelHelper;

    protected $hidden = ['deleted_at', 'created_at','updated_at'];

    protected $fillable = ['code','name','cat_id','unit_id','price','capital','active','stock','desc'];


}
