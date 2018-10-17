<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sighttype;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class SighttypeController extends Controller {
	use HasResourceActions;

	/**
	 * Index interface.
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function index(Content $content) {
		return $content
			->header('列表')
			->description('详细')
			->body($this->grid());
	}

	/**
	 * Show interface.
	 *
	 * @param mixed $id
	 * @param Content $content
	 * @return Content
	 */
	public function show($id, Content $content) {
		return $content
			->header('概览')
			->description('详细')
			->body($this->detail($id));
	}

	/**
	 * Edit interface.
	 *
	 * @param mixed $id
	 * @param Content $content
	 * @return Content
	 */
	public function edit($id, Content $content) {
		return $content
			->header('更新')
			->description('表单')
			->body($this->form()->edit($id));
	}

	/**
	 * Create interface.
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function create(Content $content) {
		return $content
			->header('新增')
			->description('表单')
			->body($this->form());
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		$grid = new Grid(new Sighttype);
		$grid->actions(function ($actions) {
			$p_id = $actions->getKey();
			$actions->prepend("<a href='sighttypes/create?parent_id=" . $p_id . "' title='添加子类'><i class='fa fa-plus-square'></i></a>&nbsp;");
		});

		$grid->id('ID');
		$grid->name('名称')->editable();
		// $grid->parentcategory('归属父类')->display(function ($parentcategory) {
		// 	return "<span class='label label-info'>{$parentcategory['name']}</span>";
		// });
		$grid->parent_id('父类');
		$grid->description('说明')->limit(30)->editable();
		$grid->order('排序');
		// $grid->created_at('Created at');
		// $grid->updated_at('Updated at');

		return $grid;
	}

	/**
	 * Make a show builder.
	 *
	 * @param mixed $id
	 * @return Show
	 */
	protected function detail($id) {
		$show = new Show(Sighttype::findOrFail($id));

		$show->id('ID');
		$show->panel()->title('概览');
		$show->name('名称')->editable();
		$show->description('描述')->editable();
		$show->parent_id();
		// dd($show->parent_id());

		$show->order('排序');
		// $show->created_at('Created at');
		// $show->updated_at('Updated at');

		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		$form = new Form(new Sighttype);

		$p_id = request()->get('parent_id');
		$form->display('ID');
		$cates = collect([0 => '---创建父分类---']);
		$merged = $cates->merge(Sighttype::all()->pluck('name', 'id'));
		$form->select('parent_id', '父类')->options($merged)->value($p_id);
		$form->text('name', '分类名称')->rules('required|min:2|max:20')->help('请输入2-20个字符！');
		$next_id = DB::select("SHOW TABLE STATUS LIKE 'tx_sighttype'");
		$form->text('description', '说明')->help('请输入2-50个字符！');
		$form->text('order', '排序')->value($next_id[0]->Auto_increment);
		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
