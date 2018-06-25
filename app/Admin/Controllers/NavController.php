<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Nav;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;

class NavController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('导航');
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

			$content->header('导航');
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

			$content->header('导航');
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
		return Admin::grid(Nav::class, function (Grid $grid) {

// $grid->model()->withTrashed();
			$grid->id('ID')->sortable();
			$grid->name('名称')->editable();
			// dd($grid->parent('父类'));
			$grid->parentcategory('归属父类')->display(function ($parentcategory) {
				return "<span class='label label-warning'>{$parentcategory['name']}</span>";
			});
			$grid->parent_id('父类');
			$grid->order('排序');
			$grid->url('链接');
			$grid->description('说明')->limit(30)->editable();
			$grid->order('排序');

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
		return Admin::form(Nav::class, function (Form $form) {

			$form->display('id', 'ID');
			$cates = collect([0 => '---创建父导航---']);

			$merged = $cates->merge(Nav::all()->pluck('name', 'id'));
			$form->select('parent_id', '父类')->options($merged);
			$form->text('name', '导航名称')->rules('required|min:2|max:20')->help('请输入2-20个字符！');
			$nextid = DB::select("SHOW TABLE STATUS LIKE 'tx_navs'");
			$form->text('order', '默认排序')->value($nextid[0]->Auto_increment);
			$form->text('url', '链接');
			$form->textarea('description', '说明');
		});
	}
}
