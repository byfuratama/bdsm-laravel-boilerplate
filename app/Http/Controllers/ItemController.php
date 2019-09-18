<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Category;
use App\Unit;

class ItemController extends Controller
{
    public function index()
    {        
        $itemData = Item::select('*');
        $categoryData = Category::select('id as cat_id','name as category');
        $unitData = Unit::select('id as unit_id','name as unit');

        $data = $itemData
            ->leftJoinModel($categoryData, 'categories' , 'items.cat_id' , 'categories.cat_id')
            ->leftJoinModel($unitData, 'units' , 'items.unit_id' , 'units.unit_id');
        
        $data = $data->searchAllFields();

        return bd_json($data);
    }

    public function indexAktif()
    {        
        $data = Item::where('active',1)->orderBy('name')->get();
        return bd_json($data);
    }

    public function indexAll()
    {        
        $data = Item::orderBy('name')->get();
        return bd_json($data);
    }

    public function store(Request $request)
    {
        $data = (new Item)->record($request);      
        return bd_json($data);
    }

    public function show($id)
    {
        $data = Item::find($id);
        return bd_json($data);
    }

    public function update(Request $request, $id)
    {
        $data = Item::find($id)->record($request);
        return bd_json($data);
    }

    public function destroy($id)
    {
        $data = Item::find($id);
        if ($data) {
            $data->delete();
        }
        return bd_json($data);
    }


}
