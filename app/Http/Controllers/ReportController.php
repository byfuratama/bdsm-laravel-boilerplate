<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;

class ReportController extends Controller
{
    public function uang(Request $request) {
        $method = $request->input('method','d');
        $from = $request->input('from',null);
        $to = $request->input('to',null);
        $data = Report::uang($method,$from,$to);
        return bd_json(Report::envelop($data));
    }

    public function item(Request $request) {
        $method = $request->input('method','d');
        $from = $request->input('from',null);
        $to = $request->input('to',null);
        $item = $request->input('item',null);
        $data = Report::item($method,$from,$to,$item);
        return bd_json(Report::envelop($data));
    }

    public function sales(Request $request) {
        $method = $request->input('method','d');
        $from = $request->input('from',null);
        $to = $request->input('to',null);
        $data = Report::sales($method,$from,$to);
        return bd_json(Report::envelop($data));
    }

    public function uangExcel(Request $request) {
        $method = $request->input('method','d');
        $from = $request->input('from',null);
        $to = $request->input('to',null);
        Report::excelUang($method,$from,$to);
    }

    public function itemExcel(Request $request) {
        $method = $request->input('method','d');
        $from = $request->input('from',null);
        $to = $request->input('to',null);
        Report::excelItem($method,$from,$to);
    }

    public function salesExcel(Request $request) {
        $method = $request->input('method','d');
        $from = $request->input('from',null);
        $to = $request->input('to',null);
        Report::excelOrder($method,$from,$to);
    }
}
