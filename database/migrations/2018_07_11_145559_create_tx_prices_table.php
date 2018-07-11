<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxPricesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tx_prices', function (Blueprint $table) {
			$table->increments('id')->comment('id');
			$table->integer('product_id')->comment('产品id');
			$table->string('price')->comment('价格');
			$table->dateTime('date')->comment('日期');
			$table->string('remark')->comment('备注');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('tx_prices');
	}
}
