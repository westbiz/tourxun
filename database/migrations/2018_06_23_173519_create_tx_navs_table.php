<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxNavsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tx_navs', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->comment('名称');
			$table->integer('parent_id')->comment('父级id');
			$table->integer('order')->comment('排序');
			$table->string('url')->comment('url');
			$table->string('description')->comment('描述');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('tx_navs');
	}
}
