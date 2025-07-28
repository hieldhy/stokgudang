<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockinTable extends Migration
{
    public function up()
    {
        Schema::create('stockin', function (Blueprint $table) {
            $table->id('stockinid');
            $table->foreign('itemid')->references('itemid')->on('items')->onDelete('cascade');
            $table->integer('volume');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stockin');
    }
}