<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data = User::where('role','<>','superadmin')->select('*');
        $data = $data->searchAllFields();
        return bd_json($data);
    }

    public function store(Request $request)
    {
        $data = (new User)->record($request);      
        return bd_json($data);
    }

    public function show($id)
    {
        $data = User::find($id);
        return bd_json($data);
    }

    public function update(Request $request, $id)
    {
        $data = User::find($id)->record($request);
        return bd_json($data);
    }

    public function destroy($id)
    {
        $data = User::find($id);
        if ($data) {
            $data->delete();
        }
        return bd_json($data);
    }
}
