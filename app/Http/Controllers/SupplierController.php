<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $data = Supplier::select('*');
        $data = $data->searchAllFields();
        return bd_json($data);
    }

    public function store(Request $request)
    {
        $data = (new Supplier)->record($request);      
        return bd_json($data);
    }

    public function show($id)
    {
        $data = Supplier::find($id);
        return bd_json($data);
    }

    public function update(Request $request, $id)
    {
        $data = Supplier::find($id)->record($request);
        return bd_json($data);
    }

    public function destroy($id)
    {
        $data = Supplier::find($id);
        if ($data) {
            $data->delete();
        }
        return bd_json($data);
    }
}
