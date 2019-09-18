<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::select('*');
        $data = $data->searchAllFields();
        return bd_json($data);
    }

    public function store(Request $request)
    {
        $data = (new Category)->record($request);      
        return bd_json($data);
    }

    public function show($id)
    {
        $data = Category::find($id);
        return bd_json($data);
    }

    public function update(Request $request, $id)
    {
        $data = Category::find($id)->record($request);
        return bd_json($data);
    }

    public function destroy($id)
    {
        $data = Category::find($id);
        if ($data) {
            $data->delete();
        }
        return bd_json($data);
    }
}
