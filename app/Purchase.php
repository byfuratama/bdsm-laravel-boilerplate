<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Purchase extends Model
{
    use SoftDeletes;
    use \App\BDSM\ModelHelper;

    protected $hidden = ['deleted_at', 'created_at','updated_at'];

    protected $fillable = ['order_no','user_id','credit','sup_id','order_at','po'];

    use \App\BDSM\ModelDetailHelper;
    protected $detailModelClass = "App\\PurchaseDetail";
    protected $detailForeignKey = "pur_id";

    public static function getNo() {
        $date = date('Y-m-d');
        $count = Purchase::whereDate('order_at',$date)->count();
        $count++;
        $no = sprintf("BL%s%s%s%s",date('y'),date('m'),date('d'),str_pad($count,3,'0',STR_PAD_LEFT));
        return $no;
    }

    public static function listing() {
        $pur = Purchase::select("purchases.id","order_no","order_at");
        $supplier = \App\Supplier::select("*");
        $detail = \App\PurchaseDetail::select('pur_id',\DB::raw('SUM((100-0)/100*item_qty*item_capital) as total'))->groupBy('pur_id');

        $data = $pur->joinModel($supplier, 'suppliers' , 'suppliers.id' , 'purchases.sup_id')
                    ->joinModel($detail, 'od' , 'od.pur_id' , 'purchases.id')
                    ->select('purchases.*','total','suppliers.name as supplier');
        
        $data = \DB::table(\DB::raw("({$data->toSql()}) tbl"))->orderBy('order_at','DESC');

        return $data;
    }
}
