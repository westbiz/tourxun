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
			$content->description('列表');
			$content->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类列表']
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

			$content->header('分类管理');
			$content->description('编辑');
			$content->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类管理', 'url' => '/categories'],
				['text' => '编辑分类']
			);
			// $content->name();

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

			$content->header('分类');
			$content->description('新增');
			$content->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类管理', 'url' => '/categories'],
				['text' => '新增分类']
			);

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

			// $grid->model()->withTrashed();
			$grid->id('ID')->sortable();
			$grid->name('名称')->editable();
			// dd($grid->parent('父类'));
			$grid->child('归属父类')->display(function ($child) {
				return "<span class='label label-warning'>{$child['name']}</span>";
			});
			$grid->parent_id('父类');
			$grid->description('说明')->limit(30)->editable();

			$grid->filter(function ($filter) {
				// 设置created_at字段的范围查询
				$filter->between('created_at', 'Created Time')->datetime();
			});

			// $grid->deleted_at();
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
		return Admin::form(Category::class, function (Form $form) {

			$form->display('id', 'ID');
			$cates = collect([0 => '---创建父分类---']);
			$merged = $cates->merge(Category::all()->pluck('name', 'id'));
			$form->select('parent_id', '父类')->options($merged);
			$form->text('name', '名称')->rules('required|min:2|max:20')->help('请输入2-20个字符！');
			$nextid = DB::select("SHOW TABLE STATUS LIKE 'tx_category'");
			$form->text('level', '层级')->value($nextid[0]->Auto_increment);
			$form->textarea('description', '说明');

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}
}