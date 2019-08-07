<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_test')->unisgned(); //field integer dengan bilangan cacah (unsigned = positif)
            $table->string('str_1')->default('default'); //field string dengan value default jika kosong
            $table->string('str_2')->nullable(); //field string yang bisa diinsert value null
            $table->enum('enum',['enum_1','enum_2','enum_3']); //membuat field enum (pilihan)
            $table->decimal('decimal', 5, 2); //membuat field decimal 5 digit, termasuk 2 angka dibelakang koma (999.99)
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_detail');
    }
}
