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
			->header('Index')
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

		$grid->id('ID');
		$grid->name('属性名称');
		$grid->note('说明');
		$grid->category()->name('分类');
		$grid->parent_id('父ID');
		$grid->fieldname('列名');
		$grid->displayname('显示名');
		$grid->isrequired('必填');
		$grid->inputtype('控件');

		$grid->attrvalues()->pluck('attrvalue', 'id')->label();
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
		$show->created_at('Created at');
		$show->updated_at('Updated at');

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
		$form->select('parent_id', '父类')->options(Catattr::all()->pluck('name', 'id'));
		$form->text('name', '属性名称');
		$form->text('fieldname', '列名');
		$form->text('note', '说明');
		$form->text('category_id', '类别id');
		$form->text('displayname', '显示名');
		$form->radio('isrequired', '必填')->options([1 => '是', 0 => '否'])->default(0);
		$form->text('inputtype', '控件类型');

		$form->hasMany('attrvalues', '属性值', function (Form\NestedForm $form) {
			$form->text('attrvalue');
			$form->text('order');
			$form->text('status');
		});

		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
