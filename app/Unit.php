<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    //Memakai fitur softdelete laravel untuk menyembunyikan data ketika dihapus ketimbang menghapus secara permanen
    // use SoftDeletes;
    //Memakai fungsi tambahan BDSM
    use \App\BDSM\ModelHelper;

    //Laravel otomatis membaca nama tabel secara 'plural', untuk menghindari hal tsb kita memberi nama $table dengan nama baru
    protected $table = 'unit';

    //Sembunyikan data yang akan dikembalikan dalam bentuk json
    // protected $hidden = ['deleted_at', 'created_at','updated_at'];

    //Isikan field yang bisa diisi ke tabel
    protected $fillable = ['nama','keterangan'];
}
