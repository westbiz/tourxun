<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_pictures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pictureable_id')->index()->comment('商品id');
            $table->string('pictureable_type')->index()->comment('商品id');
            $table->string('pictureuri')->comment('图片路径');
            $table->string('description')->comment('描述')->nullable();

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
        Schema::dropIfExists('tx_pictures');
    }
}
