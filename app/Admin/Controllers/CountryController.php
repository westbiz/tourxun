<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CountryController extends Controller {
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
		$grid = new Grid(new Country);

		$grid->id('ID');
		$grid->cname('中文');
		$grid->continent()->cn_name('大洲')->label('info');
		$grid->name('英文');
		// $grid->lower_name('en小写');
		$grid->country_code('代码');
		// $grid->full_name('en全称');
		$grid->full_cname('全称');
		$grid->remark('简介')->limit(30);
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
		$show = new Show(Country::findOrFail($id));

		$show->id('ID');
		$show->cname('中文');
		$show->continent_id('洲id');
		$show->name('英文');
		// $show->lower_name('en小写');
		$show->country_code('代码');
		$show->full_name('全称');
		$show->full_cname('全称');
		$show->remark('简介')->limit(30);
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
		$form = new Form(new Country);

		$form->display('ID');
		$form->text('cname');
		$form->text('continent_id');
		$form->text('name');
		$form->text('country_code');
		$form->text('full_name');
		$form->text('full_cname');
		$form->text('remark');
		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
