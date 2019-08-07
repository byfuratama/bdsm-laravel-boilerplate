<?php

/* File yang berkaitan

App/Http/Controllers/Test (Controller)
Database/Migrations/create_tests_table (Migrasi Database)

*/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\TestDetail;

class Test extends Model
{
    //Memakai fitur softdelete laravel untuk menyembunyikan data ketika dihapus ketimbang menghapus secara permanen
    use SoftDeletes;

    //Laravel otomatis membaca nama tabel secara 'plural', untuk menghindari hal tsb kita memberi nama $table dengan nama baru
    protected $table = 'test';

    //Sembunyikan data yang akan dikembalikan dalam bentuk json
    protected $hidden = [
        'deleted_at', 'created_at','updated_at'
    ];

    //Fungsi untuk menyimpan/mengupdate model
    public function record($request) {
        //$data->namakolom => $request->namakolom
        $this->str = $request->str;
        $this->int = $request->int;
        $this->bool = $request->bool;
        $this->date = $request->date;
        $this->save();

        return $this;
    }

    public function recordDetail($detail) {
        $detailData = [];
        foreach ($detail as $dtl) {
            $detailData[] = (new TestDetail())->record((object) $dtl, $this->id);
        }
        $this->detail = $detailData;

        return $this;
    }

    public function deleteDetail() {
        $detailData = TestDetail::where('id_test', $this->id);
        $this->detail = $detailData->get();
        $detailData->delete();

        return $this;
    }

    
}
