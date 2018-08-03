<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sight;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class SightController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('景点');
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

			$content->header('景点');
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

			$content->header('景点');
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
		return Admin::grid(Sight::class, function (Grid $grid) {

			// $sight = Sight::find(1);
			// dd($sight->pics->pluck('pictureuri'));

			$grid->id('ID')->sortable();
			$grid->name('名称');
			$grid->city_id('区域');

			$grid->pics()->pluck('pictureuri')->display(function ($pictureuri) {
				return json_decode($pictureuri, true);
			})->map(function ($path) {
				return 'http://tourxun.test/uploads/' . $path;
			})->image();
			// $grid->pictures()->display(function ($pictures) {
			// 	$pictures = array_map(function ($picture) {
			// 		return "<img src='http://tourxun.test/uploads/{$picture['pictureuri']}' height='10%' width='20%' class='img img-thumbnail'>";
			// 	}, $pictures);
			// 	return join('&nbsp;', $pictures);
			// });
			$grid->summary('概况');
			$grid->content('内容');

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
		return Admin::form(Sight::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('name', '名称');
			$form->text('city_id', '区域');
			$form->multipleImage('pictures.pictureuri', '图片')->removable();
			$form->text('summary', '概述');
			$form->textarea('content', '介绍');

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}
}
