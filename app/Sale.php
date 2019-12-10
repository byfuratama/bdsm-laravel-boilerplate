<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Sale extends Model
{
    use SoftDeletes;
    use \App\BDSM\ModelHelper;

    protected $hidden = ['deleted_at', 'created_at','updated_at'];

    protected $fillable = ['order_no','user_id','cash','order_at'];

    use \App\BDSM\ModelDetailHelper;
    protected $detailModelClass = "App\\SaleDetail";
    protected $detailForeignKey = "sale_id";

    public static function getNo() {
        $date = date('Y-m-d');
        $count = Sale::whereDate('order_at',$date)->count();
        $count++;
        $no = sprintf("%s%s%s%s",date('y'),date('m'),date('d'),str_pad($count,7,'0',STR_PAD_LEFT));
        return $no;
    }

    public static function listing() {
        $sale = Sale::select("sales.id","order_no","order_at");
        $detail = \App\SaleDetail::select('sale_id',\DB::raw('SUM((100-item_disc)/100*item_qty*item_price) as total'))->groupBy('sale_id');

        $data = $sale->joinModel($detail, 'od' , 'od.sale_id' , 'sales.id')
                    ->select('sales.*','total');
        
        $data = \DB::table(\DB::raw("({$data->toSql()}) tbl"))->orderBy('order_at','DESC');

        return $data;
    }
}
