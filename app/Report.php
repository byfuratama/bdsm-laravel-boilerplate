<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;
use App\Sale;
use App\SaleDetail;
use App\Purchase;
use App\PurchaseDetail;
use App\Supplier;
use App\BDSM\ExcelHelper;
use DB;

class Report extends Model
{
    public static function envelop($result) {
        return DB::table(DB::raw("({$result->toSql()}) tbl"))->addBinding($result->getBindings());
    }

    public static function uang($method,$from,$to) {
        $sal = Sale::selectRaw("DATE(order_at) as tanggal, SUM(item_qty*item_price) as pemasukan, 0 as pengeluaran")->joinModel(
            SaleDetail::select('*'),'sd','sd.sale_id','sales.id'
        )->groupBy('tanggal');

        $pur = Purchase::selectRaw("DATE(order_at) as tanggal, 0 as pemasukan, SUM(item_qty*item_capital) as pengeluaran")->joinModel(
            PurchaseDetail::select('*'),'pd','pd.pur_id','purchases.id'
        )->groupBy('tanggal');

        $result = $sal->union($pur);
        $result = self::envelop($result);

        if ($method === 'y') {
            $result = $result->groupBy('date')->orderBy('date');
            $tanggal = "CONCAT(YEAR(tanggal),'-01-01')";
        } else if ($method === 'm') {
            $result = $result->groupBy('date')->orderBy('date');
            $tanggal = "CONCAT(DATE_FORMAT(tanggal, '%Y-%m'),'-01')";
        } else if ($method === 'd') {
            $result = $result->groupBy('date')->orderBy('date');
            $tanggal = "tanggal";
        } else if ($method === 'a') {
            $result = $result->groupBy('date');
            $tanggal = "2000";
        }

        $result = $result->selectRaw("{$tanggal} as date, SUM(pemasukan) as pemasukan, SUM(pengeluaran) as pengeluaran, (SUM(pemasukan)-SUM(pengeluaran)) as laba");
        
        if ($from != null) $result = $result->whereDate('tanggal','>=',$from);
        if ($to != null) $result = $result->whereDate('tanggal','<=',$to);

        // dd($result->toSql());
        return $result;
    }

    public static function item($method,$from,$to,$fkey = null) {
        $sal = Sale::selectRaw("DATE(order_at) as tanggal, item_id, item_name, 0 as qty_in, SUM(item_qty) as qty_out, SUM(item_qty*item_price) as pemasukan, 0 as pengeluaran")->joinModel(
            SaleDetail::select('*'),'sd','sd.sale_id','sales.id'
        )->groupBy('tanggal','item_name','item_id');

        $pur = Purchase::selectRaw("DATE(order_at) as tanggal, item_id, item_name, SUM(item_qty) as qty_in, 0 as qty_out, 0 as pemasukan, SUM(item_qty*item_capital) as pengeluaran")->joinModel(
            PurchaseDetail::select('*'),'pd','pd.pur_id','purchases.id'
        )->groupBy('tanggal','item_name','item_id');

        $result = $sal->union($pur);
        $result = self::envelop($result);

        if ($method === 'y') {
            $result = $result->groupBy('date','item_id','item_name')->orderBy('date')->orderBy('item_name');
            $tanggal = "CONCAT(YEAR(tanggal),'-01-01')";
        } else if ($method === 'm') {
            $result = $result->groupBy('date','item_id','item_name')->orderBy('date')->orderBy('item_name');
            $tanggal = "CONCAT(DATE_FORMAT(tanggal, '%Y-%m'),'-01')";
        } else if ($method === 'd') {
            $result = $result->groupBy('date','item_id','item_name')->orderBy('date')->orderBy('item_name');
            $tanggal = "tanggal";
        } else if ($method === 'a') {
            $result = $result->groupBy('date','item_id','item_name')->orderBy('item_name');
            $tanggal = "2000";
        }

        $result = $result->selectRaw(
            "{$tanggal} as date, item_id, item_name, SUM(qty_in) as qty_in, SUM(qty_out) as qty_out,  
            SUM(pemasukan) as pemasukan, SUM(pengeluaran) as pengeluaran"
        );
        
        if ($from != null) $result = $result->whereDate('tanggal','>=',$from);
        if ($to != null) $result = $result->whereDate('tanggal','<=',$to);
        if ($fkey != null) $result = $result->where('item_id',$fkey);

        // dd($result->get()->toArray());
        return $result;
    }

    public static function sales($method,$from,$to) {
        $sal = Sale::selectRaw("DATE(order_at) as date, SUM(item_qty*item_price) as sales, 
            (SUM(item_qty*item_price) - SUM(item_qty*item_capital)) as profit, 
            SUM(item_qty) as nitem, COUNT(*) as nsales")
            ->joinModel(SaleDetail::select('*'),'sd','sd.sale_id','sales.id')
            ->groupBy('date');

        $result = $sal;

        if ($method === 'y') {
            $result = $result->groupBy('date')->orderBy('date');
            $date = "CONCAT(YEAR(date),'-01-01')";
        } else if ($method === 'm') {
            $result = $result->groupBy('date')->orderBy('date');
            $date = "CONCAT(DATE_FORMAT(date, '%Y-%m'),'-01')";
        } else if ($method === 'd') {
            $result = $result->groupBy('date')->orderBy('date');
            $date = "date";
        } else if ($method === 'a') {
            $result = $result->groupBy('date');
            $date = "2000";
        }

        // $result = $result->selectRaw("{$date} as date, sales, profit, nitem, nsales");
        
        if ($from != null) $result = $result->whereDate('date','>=',$from);
        if ($to != null) $result = $result->whereDate('date','<=',$to);

        // dd($result->toSql());
        return $result;
    }

    public static function excelUang($method,$from,$to) {
        $reportData = self::envelop(self::customer($method,$from,$to));
        if ($method === 'd')
            $reportData = $reportData->select('date as TANGGAL','nama as CUSTOMER','qty as JUMLAH_PRODUK','nilai as NILAI_ORDER','terbayar as TERBAYAR');
        else if ($method === 'm')
            $reportData = $reportData->select(DB::raw("DATE_FORMAT(date,'%Y-%m') as BULAN"),'nama as CUSTOMER','qty as JUMLAH_PRODUK','nilai as NILAI_ORDER','terbayar as TERBAYAR');
        else if ($method === 'y')
            $reportData = $reportData->select(DB::raw("YEAR(date) as TAHUN"),'nama as CUSTOMER','qty as JUMLAH_PRODUK','nilai as NILAI_ORDER','terbayar as TERBAYAR');
        else
            $reportData = $reportData->select('nama as CUSTOMER','qty as JUMLAH_PRODUK','nilai as NILAI_ORDER','terbayar as TERBAYAR');

        $reportDataGet = $reportData->get()
            ->map(function ($item, $key) {
                return (array) $item;
            })
            ->all();

        $sheet = new ExcelHelper("Uang");
        $sheet->setTitle('Laporan Uang');
        if ($method === 'd')
            $sheet->setSubTitle("Harian");
        else if ($method === 'm')
            $sheet->setSubTitle("Bulanan");
        else if ($method === 'y')
            $sheet->setSubTitle("Tahunan");

        if ($from !== null || $to !== null) {
            $from = $from == null ? '' : $from;
            $to = $to == null ? '' : $to;
            $sheet->setHeaderMeta("tgl","Tanggal {$from} s/d {$to}",'#000000',10,"right");
            // public function setHeaderMeta($meta,$string,$color="#000000",$size=12,$align="center",$weight="normal") {
        }
        $sheet->setData($reportDataGet,'#808080','#ffffff');
        
        ExcelHelper::render();
    }

    public static function excelItem($method,$from,$to) {
        $reportData = self::envelop(self::produk($method,$from,$to));
        if ($method === 'd')
            $reportData = $reportData->select('date as TANGGAL','nama as PRODUK','qty as JUMLAH_PRODUK','total as NOMINAL','net as NETTO');
        else if ($method === 'm')
            $reportData = $reportData->select(DB::raw("DATE_FORMAT(date,'%Y-%m') as BULAN"),'nama as PRODUK','qty as JUMLAH_PRODUK','total as NOMINAL','net as NETTO');
        else if ($method === 'y')
            $reportData = $reportData->select(DB::raw("YEAR(date) as TAHUN"),'nama as PRODUK','qty as JUMLAH_PRODUK','total as NOMINAL','net as NETTO');
        else
            $reportData = $reportData->select('nama as PRODUK','qty as JUMLAH_PRODUK','total as NOMINAL','net as NETTO');

        $reportDataGet = $reportData->get()
            ->map(function ($item, $key) {
                return (array) $item;
            })
            ->all();

        $sheet = new ExcelHelper("Produk");
        $sheet->setTitle('Laporan Produk');
        if ($method === 'd')
            $sheet->setSubTitle("Harian");
        else if ($method === 'm')
            $sheet->setSubTitle("Bulanan");
        else if ($method === 'y')
            $sheet->setSubTitle("Tahunan");

        if ($from !== null || $to !== null) {
            $from = $from == null ? '' : $from;
            $to = $to == null ? '' : $to;
            $sheet->setHeaderMeta("tgl","Tanggal {$from} s/d {$to}",'#000000',10,"right");
            // public function setHeaderMeta($meta,$string,$color="#000000",$size=12,$align="center",$weight="normal") {
        }
        $sheet->setData($reportDataGet,'#808080','#ffffff');
        
        ExcelHelper::render();
    }

    public static function excelSales($method,$from,$to) {
        $reportData = self::envelop(self::order($method,$from,$to));
        if ($method === 'd')
            $reportData = $reportData->select('date as TANGGAL','norder AS JUMLAH_ORDER','nproduk as JUMLAH_PRODUK','total as NOMINAL','torder as CASH','korder as CREDIT');
        else if ($method === 'm')
            $reportData = $reportData->select(DB::raw("DATE_FORMAT(date,'%Y-%m') as BULAN"),'norder AS JUMLAH_ORDER','nproduk as JUMLAH_PRODUK','total as NOMINAL','torder as CASH','korder as CREDIT');
        else if ($method === 'y')
            $reportData = $reportData->select(DB::raw("YEAR(date) as TAHUN"),'norder AS JUMLAH_ORDER','nproduk as JUMLAH_PRODUK','total as NOMINAL','torder as CASH','korder as CREDIT');
        else
            $reportData = $reportData->select('norder AS JUMLAH_ORDER','nproduk as JUMLAH_PRODUK','total as NOMINAL','torder as CASH','korder as CREDIT');

        $reportDataGet = $reportData->get()
            ->map(function ($item, $key) {
                return (array) $item;
            })
            ->all();

        $sheet = new ExcelHelper("Order");
        $sheet->setTitle('Laporan Order');
        if ($method === 'd')
            $sheet->setSubTitle("Harian");
        else if ($method === 'm')
            $sheet->setSubTitle("Bulanan");
        else if ($method === 'y')
            $sheet->setSubTitle("Tahunan");

        if ($from !== null || $to !== null) {
            $from = $from == null ? '' : $from;
            $to = $to == null ? '' : $to;
            $sheet->setHeaderMeta("tgl","Tanggal {$from} s/d {$to}",'#000000',10,"right");
            // public function setHeaderMeta($meta,$string,$color="#000000",$size=12,$align="center",$weight="normal") {
        }
        $sheet->setData($reportDataGet,'#808080','#ffffff');
        
        ExcelHelper::render();
    }
}

