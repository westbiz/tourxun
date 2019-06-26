<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Catattr;
use App\Models\Category;
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

		$grid->actions(function ($actions) {
			$p_id = $actions->getKey();
			$actions->prepend("<a href='catattrs/create?parent_id=" . $p_id . "' title='添加子类'><i class='fa fa-plus-square'></i></a>&nbsp;");
		});

		$grid->id('ID');
		$grid->name('属性名称')->editable();
		$grid->en_name('说明')->editable();		
		// $grid->childcatattr('属性值')->pluck('name')->label();
		$grid->attrvalues('属性值')->pluck('attrvalue')->label('info')->style('max-width:200px;line-height:1.5em;word-break:break-all;');

		$grid->parentcatattr()->name('属性类别')->label('danger');
		$grid->categories('归属分类')->pluck('name')->label('warning');
		// $grid->inputtype('控件名');
		$grid->inputtype('控件类型')->select([
			'checkbox' => '多选框',
			'radio' => '单选框',
			'select' => '下拉菜单',
			'text' => '文本框',
		]);
		$grid->inputformat('控件数据格式');

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
		$show->name('属性名称');
		$show->categories('分类', function ($category) {
			$category->resource('/admin/categories');
			$category->name();
		});
		$show->parentcatattr()->name('上级分类')->label();
		$show->en_name('说明');
		$show->inputtype('控件类型');
		$show->order('排序');
		$show->active('激活');
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

		// $form->display('ID');
		$form->text('name', '属性名称')->rules('required|min:2');
		$form->multipleSelect('categories', '归属分类')->options(Category::where('parent_id', 0)->pluck('name', 'id'));
		$form->select('parent_id', '属性类别')->options(Catattr::where('parent_id', 0)->orWhere('parent_id', null)->pluck('name', 'id'));

		$form->text('en_name', '属性')->rules('required|min:2');

		$form->radio('isrequired', '必填')->options([1 => '是', 0 => '否'])->default(0);
		$form->select('inputtype', '控件类型')->options(['select' => '下拉框', 'checkbox' => '复选框', 'radio' => '单选框', 'text' => '文本框'])->rules('required');
		$form->select('inputformat', '控件格式')
			->options([
				'date' => '日期', 
				'datetime' => '日期时间', 
				'dateRange' => '日期范围', 
				'timeRange' => '时间范围',
				'number' => '数字输入',
				'currency' => '货币',
				'text' => '文本',
			])->rules('required');	
		// $form->list('list');	
		$form->radio('active', '激活')->options([1 => '是', 0 => '否'])->default(0);

		$form->hasMany('attrvalues', '属性值', function (Form\NestedForm $form) {
			$form->text('attrvalue', '值名');
			$form->text('order', '说明');
			$form->radio('status', '状态')->options([1 => '是', 0 => '否'])->default(0);
		});

		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
