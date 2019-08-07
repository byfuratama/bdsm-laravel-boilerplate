<?php

/* File yang berkaitan

App/Http/Controllers/TestDetail (Controller)
Database/Migrations/create_test_details_table (Migrasi Database)

*/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestDetail extends Model
{
    //Memakai fitur softdelete laravel untuk menyembunyikan data ketika dihapus ketimbang menghapus secara permanen
    use SoftDeletes;

    //Laravel otomatis membaca nama tabel secara 'plural', untuk menghindari hal tsb kita memberi nama $table dengan nama baru
    protected $table = 'test_detail';

    //Sembunyikan data yang akan dikembalikan dalam bentuk json
    protected $hidden = [
        'deleted_at', 'created_at','updated_at'
    ];
}