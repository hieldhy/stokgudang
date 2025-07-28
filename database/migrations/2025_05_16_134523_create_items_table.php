<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('itemid'); 
            $table->string('nama_perangkat');
            $table->string('type')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->integer('volume');
            $table->string('satuan')->nullable();
            $table->string('serialnumber')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('referensi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}