<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxProductTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tx_product', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->comment('名称');
			$table->integer('category_id')->comment('分类id');
			$table->integer('day')->comment('行程天数');
			$table->integer('night')->comment('住宿晚数');
			$table->tinyInteger('hotel')->comment('住宿星级');
			// $table->integer('comment_id')->comment('评论id');
			// $table->integer('price_id')->comment('价格id');
			$table->string('star')->comment('评星');
			$table->string('summary')->comment('概述');
			$table->text('content')->comment('正文');
			$table->tinyInteger('active')->comment('激活，1是，0否');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('tx_product');
	}
}
