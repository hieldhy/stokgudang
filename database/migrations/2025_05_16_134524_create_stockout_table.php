<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockoutTable extends Migration
{
    public function up()
    {
        Schema::create('stockout', function (Blueprint $table) {
            $table->id('stockoutid');
            $table->foreign('itemid')->references('itemid')->on('items')->onDelete('cascade');
            $table->integer('volume');
            $table->text('keterangan')->nullable();
            $table->string('recipient')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stockout');
    }
}