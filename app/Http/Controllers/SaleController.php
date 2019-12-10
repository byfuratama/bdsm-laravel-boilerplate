<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\SaleDetail;
use App\Item;

class SaleController extends Controller
{
    public function index()
    {
        $data = Sale::listing();
        $data = $data->searchAllFields(
            array_merge(['order_no','total','order_at'])
        );
        
        return bd_json($data);
    }

    public function store(Request $request)
    {
        $request->request->add([
            "user_id" => auth()->user()->id,
            // "order_at" => date('Y-m-d H:i'),
        ]);
        
        $data = (new Sale)->record($request);

        $reduceStock = function ($detail) {
            Item::find($detail['item_id'])->decrement('stock',$detail['item_qty']);
        };
        $data = $data->recordDetail($request->detail, $reduceStock);     

        return bd_json($data);
    }

    public function show($id)
    {
        $data = Sale::find($id);
        return bd_json($data);
    }

    public function update(Request $request, $id)
    {
        $request->request->add([
            "user_id" => auth()->user()->id,
            // "order_at" => date('Y-m-d H:i'),
        ]);

        $data = Sale::find($id)->record($request);

        SaleDetail::where('sale_id', $id)->get()->map(function($data) {
                Item::find($data->item_id)->increment('stock',$data->item_qty);
            }
        );

        $reduceStock = function ($detail) {
            Item::find($detail['item_id'])->decrement('stock',$detail['item_qty']);
        };
        $data = $data->deleteDetail()->recordDetail($request->detail, $reduceStock);

        return bd_json($data);
    }

    public function destroy($id)
    {

        SaleDetail::where('sale_id', $id)->get()->map(function($data) {
                Item::find($data->item_id)->increment('stock',$data->item_qty);
            }
        );

        $data = Sale::find($id);
        if ($data) {
            $data->deleteDetail();
            $data->delete();
        }
        return bd_json($data);
    }

    public function noOrder(Request $request) {
        $no = Sale::getNo();
        return bd_json(["no" => $no]);
    }

}
