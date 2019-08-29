<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;

class UnitController extends Controller
{
    public function index()
    {
        //Beri sql query dari model Unit
        $data = Unit::select('*'); //untuk index() kita akan select all

        $tableData = (new Unit)->getTableProperties(); //Ambil property tabel dari test
        $data = $data->searchAllFields($tableData); //Filter data dengan property tadi

        return bd_json($data); //Return ke dalam bentuk json untuk diambil front end
    }

    //Menginsert data test
    public function store(Request $request)
    {
        //panggil new <NamaModel> untuk membuat model baru dan 'record' request di dalamnya
        $data = (new Unit)->record($request);
        //jika terdapat detail, insert juga detailnya
        // $data = $data->recordDetail($request->detail);
        return bd_json($data);
    }

    //Mengambil data by id
    public function show($id)
    {
        $data = Unit::find($id);
        return bd_json($data);
    }

    //Mengupdate data by id
    public function update(Request $request, $id)
    {
        //Mengambil model data by id dan mengisi dengan 'record' request baru
        $data = Unit::find($id)->record($request);
        //Jika terdapat detail maka hapus detail yang ada, kemudian isi kembali
        // $data = $data->deleteDetail()->recordDetail($request->detail);
        return bd_json($data);
    }

    //Menghapus data by id
    public function destroy($id)
    {
        //Mengambil model data by id
        $data = Unit::find($id);
        if ($data) {
            //Jika model ada, maka hapus
            // $data->deleteDetail(); //hapus detailnya jika perlu
            $data->delete();
        }

        return bd_json($data);
    }
}
