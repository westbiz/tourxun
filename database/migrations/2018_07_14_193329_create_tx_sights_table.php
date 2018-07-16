<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxSightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_sights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('名称');
            $table->integer('city_id')->comment('所属地区id');
            $table->integer('picture_id')->comment('图片id');
            $table->string('summary')->comment('概况');
            $table->string('content')->comment('内容');
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
        Schema::dropIfExists('tx_sights');
    }
}
