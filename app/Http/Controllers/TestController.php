<?php

/* File yang berkaitan

App/Test (Model)
Database/Migrations/create_tests_table (Migrasi Database)

*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Test;

class TestController extends Controller
{
    //Menampilkan seluruh data test
    public function index()
    {
        //Beri sql query dari model Test
        //untuk index() kita akan select all
        $data = Test::select('*');

        //Return ke dalam bentuk json untuk diambil front end
        return bd_json($data);
    }

    //Menginsert data test
    public function store(Request $request)
    {
        //panggil new <NamaModel> untuk membuat model baru
        $data = new Test;

        //$data->namakolom => $request->namakolom
        $data->str = $request->str;
        $data->bool = $request->bool;
        $data->date = $request->date;

        //$data->save() untuk menyiman model ke dalam tabel
        $data->save();

        return bd_json($data);
    }

    //Mengambil data by id
    public function show($id)
    {
        $data = Test::find($id);
        return bd_json($data);
    }

    //Mengupdate data by id
    public function update(Request $request, $id)
    {
        //Mengambil model data by id
        $data = Test::find($id);

        //Mengupdate model data dengan yang baru
        $data->str = $request->str;
        $data->bool = $request->bool;
        $data->date = $request->date;

        $data->save();

        return bd_json($data);
    }

    //Menghapus data by id
    public function destroy($id)
    {
        $data = Test::find($id);
        $data->delete();

        return bd_json($data);
    }


}
