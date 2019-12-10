<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\PurchaseDetail;
use App\Item;

class PurchaseController extends Controller
{
    public function index()
    {
        $data = Purchase::listing();
        $data = $data->searchAllFields(
            array_merge(['order_no','total','supplier','po','order_at'])
        );
        
        return bd_json($data);
    }

    public function store(Request $request)
    {
        $request->request->add([
            "user_id" => auth()->user()->id,
            // "order_at" => date('Y-m-d H:i'),
        ]);
        
        $data = (new Purchase)->record($request);

        $reduceStock = function ($detail) {
            Item::find($detail['item_id'])->increment('stock',$detail['item_qty']);
        };
        $data = $data->recordDetail($request->detail, $reduceStock);     

        return bd_json($data);
    }

    public function show($id)
    {
        $data = Purchase::find($id);
        return bd_json($data);
    }

    public function update(Request $request, $id)
    {
        $request->request->add([
            "user_id" => auth()->user()->id,
            // "order_at" => date('Y-m-d H:i'),
        ]);

        $data = Purchase::find($id)->record($request);

        PurchaseDetail::where('pur_id', $id)->get()->map(function($data) {
                Item::find($data->item_id)->decrement('stock',$data->item_qty);
            }
        );

        $reduceStock = function ($detail) {
            Item::find($detail['item_id'])->increment('stock',$detail['item_qty']);
        };
        $data = $data->deleteDetail()->recordDetail($request->detail, $reduceStock);

        return bd_json($data);
    }

    public function destroy($id)
    {

        PurchaseDetail::where('pur_id', $id)->get()->map(function($data) {
                Item::find($data->item_id)->increment('stock',$data->item_qty);
            }
        );

        $data = Purchase::find($id);
        if ($data) {
            $data->deleteDetail();
            $data->delete();
        }
        return bd_json($data);
    }

    public function noOrder(Request $request) {
        $no = Purchase::getNo();
        return bd_json(["no" => $no]);
    }

}
