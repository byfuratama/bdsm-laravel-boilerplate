<?php

/* File yang berkaitan

App/Test (Model)
App/TestController (Controller)

*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    
    // Sumber materi
    // https://laravel.com/docs/5.4/migrations#columns

    public function up()
    {
        //Membuat tabel test pada database yang sudah disetup di .env
        //Schema::create('NAMA_TABEL', function (Blueprint $table))...
        Schema::create('test', function (Blueprint $table) {
            $table->increments('id'); //integer + auto-increment
            $table->string('str'); //varchar/string
            $table->boolean('bool'); //tinyint/boolean
            $table->date('date'); //date
            $table->softDeletes(); //membuat field deleted_at yang bertindak sebagai softdelete
            $table->timestamps(); //membuat field created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test');
    }
}
