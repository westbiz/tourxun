<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('分类管理');
			$content->description('分类列表');

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

			$content->header('分类管理');
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
		return Admin::grid(Category::class, function (Grid $grid) {

			$grid->id('ID')->sortable();
			$grid->name('名称');
			$grid->parent_id('父类');
			$grid->level('等级');
			$grid->column('description', '说明');

			$grid->created_at();
			$grid->updated_at();
		});
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		return Admin::form(Category::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('name', '名称')->help('请输入2-20个字符！');
			$form->select('parent_id', '父类')->options(Category::all()->pluck('name', 'id'));
			$nextid = DB::select("SHOW TABLE STATUS LIKE 'tx_category'");
			// $form->text('parent_id', '父类id');
			$form->text('level', '层级')->value($nextid[0]->Auto_increment);
			$form->textarea('description', '说明');

			$form->display('created_at', 'Created At');
			$form->display('updated_at', 'Updated At');
		});
	}
}
