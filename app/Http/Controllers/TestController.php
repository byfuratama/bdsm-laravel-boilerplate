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
        //panggil new <NamaModel> untuk membuat model baru dan 'record' request di dalamnya
        $data = (new Test)->record($request);
        //jika terdapat detail, insert juga detailnya
        $data = $data->recordDetail($request->detail);
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
        //Mengambil model data by id dan mengisi dengan 'record' request baru
        $data = Test::find($id)->record($request);
        //Jika terdapat detail maka hapus detail yang ada, kemudian isi kembali
        $data = $data->deleteDetail()->recordDetail($request->detail);
        return bd_json($data);
    }

    //Menghapus data by id
    public function destroy($id)
    {
        //Mengambil model data by id
        $data = Test::find($id);
        if ($data) {
            //Jika model ada, maka hapus
            $data->deleteDetail(); //hapus detailnya jika perlu
            $data->delete();
        }
        return bd_json($data);
    }

    public function indexWithDetail() {
        
    }


}
