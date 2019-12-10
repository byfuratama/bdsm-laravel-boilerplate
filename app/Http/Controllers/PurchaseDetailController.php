<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseDetail;

class PurchaseDetailController extends Controller
{
    public function byIDParent($id) {
        $data = PurchaseDetail::where('pur_id',$id)->joinModel(
            \App\Item::query(),'items','purchase_details.item_id','items.id'
        )->select('purchase_details.*','items.name','items.price','items.capital');
        return bd_json($data);
    }
}
