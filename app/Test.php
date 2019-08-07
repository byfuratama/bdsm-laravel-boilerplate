<?php

/* File yang berkaitan

App/Http/Controllers/Test (Controller)
Database/Migrations/create_tests_table (Migrasi Database)

*/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    //Memakai fitur softdelete laravel untuk menyembunyikan data ketika dihapus ketimbang menghapus secara permanen
    use SoftDeletes;
    //Memakai fungsi tambahan BDSM
    use \App\BDSM\ModelHelper;

    //Laravel otomatis membaca nama tabel secara 'plural', untuk menghindari hal tsb kita memberi nama $table dengan nama baru
    protected $table = 'test';

    //Sembunyikan data yang akan dikembalikan dalam bentuk json
    protected $hidden = ['deleted_at', 'created_at','updated_at'];

    //Isikan field yang bisa diisi ke tabel
    protected $fillable = ['str','int','bool','date'];

    //Jika terdapat model detail pada model ini, tambahkan fungsi berikut
    use \App\BDSM\ModelDetailHelper;
    protected $detailModelClass = "App\\TestDetail"; //nama class detail
    protected $detailForeignKey = "id_test"; //nama foreign key pada tabel detail
    
}
