<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Catattr;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CatattrController extends Controller {
	use HasResourceActions;

	/**
	 * Index interface.
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function index(Content $content) {
		return $content
			->header('属性管理')
			->description('description')
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
			->header('Detail')
			->description('description')
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
			->header('Edit')
			->description('description')
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
			->header('Create')
			->description('description')
			->body($this->form());
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		$grid = new Grid(new Catattr);

		$grid->model()->orderBy('parent_id','asc');
		$grid->id('ID');
		$grid->name('属性名称')->editable();
		$grid->description('说明')->editable();
		// $grid->categories('类别')->pluck('name')->label('danger');
		$grid->parentcatattr()->name('属性类别')->label('danger');
		$grid->attrvalues('属性值')->pluck('attrvalue')->label('info')->style('max-width:200px;word-break:break-all;');
		// $grid->attrvalues('属性值')->pluck('attrvalue', 'id')->label();
		// $grid->isrequired('必填')->using(['1' => '是', '0' => '否']);
		// $grid->isrequired('必须？')->display(function ($isrequired) {
		// 	return $isrequired ? '是' : '否';
		// });
		// $states = [
		// 	'on' => ['value' => 1, 'text' => '是', 'color' => 'primary'],
		// 	'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
		// ];
		// $grid->isrequired('必填')->switch($states);
		$grid->isrequired('必须')->select(['1' => '是', '0' => '否']);
		$grid->active('激活')->using(['1' => '是', '0' => '否']);
		$grid->inputtype('控件')->select(['checkbox' => '复选框', 'text' => '文本框', 'select' => '下拉框', 'radio' => '单选']);

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
		$show = new Show(Catattr::findOrFail($id));

		$show->id('ID');
		$show->name();
		$show->parentcatattr()->name();
		$show->description();
		$show->inputtype();
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
		$form = new Form(new Catattr);

		$form->display('ID');
		// $form->multipleSelect('categories', '分类')->options(Category::where('parent_id', '1')->pluck('name', 'id'));
		$form->select('parent_id', '属性分类')->options(Catattr::where('parent_id', 0)->pluck('name', 'id'));

		$form->text('name', '属性名称')->rules('required|min:2');
		$form->text('description', '说明')->rules('required|min:2');
		// $form->text('category_id', '类别id');
		$form->radio('isrequired', '必填')->options([1 => '是', 0 => '否'])->default(0);
		$form->select('inputtype', '控件类型')->options(['select' => '下拉框', 'checkbox' => '复选框', 'radio' => '单选框', 'text' => '文本框'])->rules('required');
		$form->radio('active', '激活')->options([1 => '是', 0 => '否'])->default(0);

		$form->hasMany('attrvalues', '属性值', function (Form\NestedForm $form) {
			$form->text('attrvalue', '值名');
			$form->text('order', '排序');
			$form->radio('status', '状态')->options([1 => '是', 0 => '否'])->default(0);
		});

		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
