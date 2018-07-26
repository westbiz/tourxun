<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Picture;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class PictureController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			// $picture = Picture::find(1);
			// dd($picture->pictureable());

			$content->header('图片');
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

			$content->header('图片');
			$content->description('description');

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

			$content->header('图片');
			$content->description('创建');

			$content->body($this->form());
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Picture::class, function (Grid $grid) {

			$grid->id('ID')->sortable();
			$grid->pictureable_id('类型');
			$grid->product()->name('产品');
			$grid->pictureuri('图片路径')->image('http://tourxun.test/uploads/', 100, 100);
			$grid->description('描述');

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
		return Admin::form(Picture::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->select('pictureable_type', '类别')->options(['sight' => '风景', 'product' => '产品', 'people' => '人物']);
			$form->text('pictureable_id');
			$form->multipleImage('pictureuri', '图片')->removable();
			$form->text('description', '图片描述');

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}
}
