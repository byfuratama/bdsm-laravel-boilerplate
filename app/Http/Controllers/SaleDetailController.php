<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SaleDetail;

class SaleDetailController extends Controller
{
    public function byIDParent($id) {
        $data = SaleDetail::where('sale_id',$id)->joinModel(
            \App\Item::query(),'items','sale_details.item_id','items.id'
        )->select('sale_details.*','items.name','items.price','items.capital');
        return bd_json($data);
    }
}
