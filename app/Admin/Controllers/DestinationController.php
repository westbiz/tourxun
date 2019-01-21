<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class DestinationController extends Controller {
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
		$grid = new Grid(new Destination);

		$grid->id('ID');
		$grid->name('名称')->editable();
		$grid->description('说明')->editable();
		$grid->categories('分类')->pluck('name')->label('info');
		$grid->promotion('推荐');
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
		$show = new Show(Destination::findOrFail($id));

		$show->id('ID');
		$show->name('名称');
		$show->description('说明');
		$show->promotion('推荐');
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
		$form = new Form(new Destination);

		$form->display('ID');
		$form->text('name', '名称')->rules(function($form){
			if (!$id=$form->model()->id) {
				return 'required|unique:tx_destinations';
			}
		});
		$form->multipleSelect('categories', '分类')->options(Category::where('parent_id', 0)->pluck('name', 'id'))->rules('required');
		$form->text('description', '说明');
		$states = [
			'on' => ['value' => 1, 'text' => '打开', 'color' => 'success'],
			'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
		];
		$form->switch('promotion', '推荐')->states($states);
		// $form->text('promotion','推荐');
		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
