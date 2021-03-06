<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Picture;
use App\Models\Picturetype;
use App\Models\Sight;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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

	public function show($id) {
		return Admin::content(function (Content $content) use ($id) {
			$content->header('图片');
			$content->description('详细');

			$content->body(Admin::show(Picture::findOrFail($id), function (show $show) {
				$show->panel()->style('info')->title('详情');
				$show->id('ID');
				$show->title('标题');
				// $show->pictureuri('地址')->image();
				$show->description();
			}));
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Picture::class, function (Grid $grid) {

			//// 关闭新增按钮
			// $grid->disableCreateButton();
			// $grid->disableExport();
			// $grid->disableActions();
			$grid->id('ID')->sortable();
			$grid->title('标题');
			$grid->pictureable_type('类别');
			$grid->pictureable_id('类别id');
			$grid->pictureuri('图片路径')->image('http://tourxun.test/uploads/', 50, 50);
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

			$picture_id = request()->route()->parameters('picture');
			if ($picture_id) {
				$form->setTitle('修改');
			} else {
				$form->setTitle('新增');
			}

			$form->display('id', 'ID');
			$s_id = request()->get('sight_id');
			$type = request()->get('type');
			// dd($type);
			if ($s_id) {
				$s_name = Sight::where('id', $s_id)->pluck('name')->all();
				// dd($s_name);
				$form->html($label = $s_name[0], '添加到');
			}

			$form->text('pictureable_id', '所属ID')->value($s_id);
			// $form->text('pictureable_type', '类型')->value($type);
			//['Sight' => '景点', 'Product' => '产品', 'value' => 'optionname']
			$items = Picturetype::all()->pluck('name', 'ename');
			// dd($items);
			$form->select('pictureable_type', '类型')->options($items)->default($type);
			$dir = 'images/' . date('Y') . '/' . date('m') . '/' . date('d');
			$form->multipleImage('pictureuri', '图片')->removable()->move($dir)->uniqueName();
			$form->text('title', '标题');
			$form->textarea('description', '图片描述')->rows(2);
			// $form->saving(function (Form $form) {

			// 	$form->model()->id;

			// });

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}
}
