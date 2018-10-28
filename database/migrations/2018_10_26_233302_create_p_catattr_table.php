<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePCatattrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_catattr', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('属性名');
            $table->string('note')->comment('备注');
            $table->integer('category_id')->comment('分类id');
            $table->string('fildname')->comment('字段名');
            $table->string('displayname')->comment('显示名');
            $table->tinyInteger('isrequired')->comment('是否必须');
            $table->string('inputtype')->comment('输入类型');
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
        Schema::dropIfExists('p_catattr');
    }
}
