<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Continent;
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

		$grid->filter(function ($filter) {
			$filter->disableIdFilter();

			// 	$filter->expand()->where(function ($query) {

			// 		$query->whereHas('continent', function ($query) {
			// 			$query->where('cn_name', 'like', "%{$this->input}%");
			// 		});
			// 	}, '洲名');
			$filter->column(1 / 2, function ($filter) {
				$continents = Continent::pluck('cn_name', 'id');
				$filter->expand()->equal('continent_id', '大洲')->select($continents);
			});

			// $filter->like('cname', '名称');
		});
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
		$show->cname('中文名称');
		$show->continent_id('大洲');
		$show->name('英文名称');
		// $show->lower_name('en小写');
		$show->country_code('代码');
		$show->full_name('英文全称');
		$show->full_cname('中文全称');
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
		$form->text('cname', '中文名称');
		$continents = Continent::pluck('cn_name', 'id');
		$form->select('continent_id', '大洲')->options($continents);
		$form->text('name', '英文名称');
		$form->text('country_code', '代码');
		$form->text('full_name', '英文全称');
		$form->text('full_cname', '中文全称');
		$form->textarea('remark', '简介');
		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
