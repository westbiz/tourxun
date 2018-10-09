<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxCommentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tx_comments', function (Blueprint $table) {
			$table->increments('id');
			$table->string('content')->comment('内容');
			$table->integer('commentable_id')->comment('关联id');
			$table->string('commentabl_type')->comment('关联类型');
			$table->string('pictureuri')->comment('图片');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('tx_comments');
	}
}
