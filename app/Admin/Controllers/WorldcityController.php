<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Worldcity;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class WorldcityController extends Controller {
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
		$grid = new Grid(new Worldcity);

		$grid->filter(function ($filter) {
			$filter->disableIdFilter();
			$filter->column(3 / 4, function ($filter) {
				$continents = Country::orderBy('cname','asc')->pluck('cname', 'id');
				$filter->expand()->equal('country_id', '按国家|地区')->select($continents);
				$filter->expand()->where(function ($query) {
					$query->where('cn_name', 'like', "%{$this->input}%")
						->orWhere('cn_state', 'like', "%{$this->input}%")
						->orWhere('name', 'like', "%{$this->input}%")
						->orWhere('lower_name', 'like', "%{$this->input}%")
						->orWhere('state', 'like', "%{$this->input}%")
						->orWhere('city_code', 'like', "%{$this->input}%")
						->orWhere('state_code', 'like', "%{$this->input}%")
					;
					// $query->whereHas('country', function ($query){
					// 	$query->where('cname', 'like', "%{$this->input}%");
					// });
				}, '关键字');
			});
		});
		$grid->id('ID');
		$grid->cn_name('城市名');
		$grid->name('EN名称');
		$grid->country()->cname('国家/地区')->label('info');
		$grid->cn_state('省/州');
		$grid->state('EN省/州');
		$grid->lower_name('en小写');
		$grid->city_code('代码');
		$grid->state_code('州代码');
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
		$show = new Show(Worldcity::findOrFail($id));

		$show->id('ID');
		$show->cn_name('城市名');
		$show->name('EN名称');
		$show->country()->cname('国家')->label('info');
		$show->cn_state('省/州');
		$show->state('EN省/州');
		$show->lower_name('en小写');
		$show->city_code('代码');
		$show->state_code('州代码');
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
		$form = new Form(new Worldcity);

		// $form->display('ID');
		$form->text('cn_name', '城市名');
		$form->select('country_id', '国家')->options(Country::pluck('cname', 'id'));
		$form->text('name', 'EN名称');
		$form->text('lower_name', 'en小写');
		// $form->text('country_id', '国家');
		$form->text('cn_state', '省/州名');
		$form->text('state', 'EN省/州名');

		$form->text('city_code', '城市代码');
		$form->text('state_code', '州代码');
		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
