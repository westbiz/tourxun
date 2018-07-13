<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class PriceController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('header');
			$content->description('description');
			$content->breadcrumb(
				['text' => '价格列表', 'url' => 'prices']
			);

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

			$content->header('header');
			$content->description('description');
			$content->breadcrumb(
				['text' => '价格列表', 'url' => 'prices'],
				['text' => '价格编辑']
			);

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

			$content->header('header');
			$content->description('description');

			$content->body($this->form());
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Price::class, function (Grid $grid) {

			$grid->id('ID')->sortable();
			$grid->product()->avatar('图片')->display(function ($avatar) {
				return "<img src='http://tourxun.test/uploads/$avatar' alt='$this->name' height='10%' width='10%' class='img img-thumbnail'>";
			});
			$grid->product()->name();
			$grid->price('价格');
			$grid->departure('日期');
			$grid->remark('说明');

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
		return Admin::form(Price::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->image('product.avatar');
			$form->currency('price', '价格')->symbol('￥');
			$form->date('departure', '日期');
			$form->textarea('remark', '说明');

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}
}
