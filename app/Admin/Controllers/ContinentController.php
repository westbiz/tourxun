<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Continent;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ContinentController extends Controller {
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
		$grid = new Grid(new Continent);

		$grid->id('ID');
		$grid->cn_name('中文')->display(function () {
			return "<a href='/admin/continents/" . $this->id . "'><span>" . $this->cn_name . "</span></a>";
		});
		$grid->parentcontinent()->cn_name('父级')->label('info');
		$grid->en_name('英文')->editable();
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
		$show = new Show(Continent::findOrFail($id));

		// $show->id('ID');
		$show->cn_name('中文');
		// $show->en_name('英文');

		$show->countries('国家地区', function ($countries) {
			$countries->resource('/admin/countries');
			$countries->id('ID');
			$countries->cname('中文');
			// $countries->continent()->cn_name('大洲')->label('info');
			$countries->name('英文');
			// $countries->lower_name('en小写');
			$countries->country_code('代码');
			// $countries->full_name('en全称');
			$countries->full_cname('全称');
			$countries->remark('简介')->limit(30);
		});
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
		$form = new Form(new Continent);

		$form->display('ID');
		$form->text('cn_name', '中文名称');
		$form->text('en_name', '英文名称');
		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}