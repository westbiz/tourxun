<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ProductController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('产品');
			$content->description('列表');

			$content->body($this->grid());
		});
	}

	/**
	 * Edit interface.
	 *
	 * @param $id
	 * @return Content
	 */
	public function edit($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('产品');
			$content->description('编辑');

			$content->body($this->form()->edit($id));
		});
	}

	/**
	 * Create interface.
	 *
	 * @return Content
	 */
	public function create() {
		return Admin::content(function (Content $content) {

			$content->header('产品');
			$content->description('新增');

			$content->body($this->form());
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Product::class, function (Grid $grid) {

			$grid->id('ID')->sortable();
			$grid->name('名称');
			$grid->category_id('分类');
			$grid->day('天数');
			$grid->night('晚数');
			$grid->hotel('酒店星级');
			$grid->comment_id('评论');
			$grid->price_id('价格id');
			$grid->star('评星');
			$grid->summary('概述');
			$grid->content('正文');
			$grid->active('激活');

			// $grid->created_at();
			// $grid->updated_at();
		});
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		return Admin::form(Product::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('name', '名称');
			$form->text('category_id', '分类');
			$form->text('day', '天数');
			$form->text('night', '晚数');
			$form->text('hotel', '酒店星级');
			$form->text('comment_id', '评论');
			$form->text('price_id', '价格id');
			$form->text('star', '评星')->attribute(['class' => 'rating', 'min' => 0, 'max' => 5, 'step' => 1, 'step' => 1, 'data-size' => 'sm', 'value=' => 2]);
			$form->text('summary', '概述');
			$form->editor('content', '正文');
			$form->radio('active', '激活')->options(['0' => '否', '1' => '是']);;

			$form->display('created_at', 'Created At');
			$form->display('updated_at', 'Updated At');
		});
	}
}
