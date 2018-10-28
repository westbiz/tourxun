<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePAttrvaluesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_attrvalues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->index()->comment('商品ID');
            $table->integer('catattr_id')->index()->comment('属性id');
            $table->string('attrvalue')->comment('属性值');
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
        Schema::dropIfExists('p_attrvalues');
    }
}
