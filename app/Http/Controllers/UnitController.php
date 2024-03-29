<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $data = Unit::select('*');
        $tableData = (new Unit)->getTableProperties();
        $data = $data->searchAllFields($tableData);
        return bd_json($data);
    }

    public function store(Request $request)
    {
        $data = (new Unit)->record($request);      
        return bd_json($data);
    }

    public function show($id)
    {
        $data = Unit::find($id);
        return bd_json($data);
    }

    public function update(Request $request, $id)
    {
        $data = Unit::find($id)->record($request);
        return bd_json($data);
    }

    public function destroy($id)
    {
        $data = Unit::find($id);
        if ($data) {
            $data->delete();
        }
        return bd_json($data);
    }
}
