<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Worldcity;
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
		$grid->country()->cname('国家');
		$grid->city()->cn_name('所属城市');
		$grid->description('说明')->editable();
		$grid->categories('分类')->pluck('name')->label('info');
		$grid->promotion('推荐');
		$grid->sort('排序')->editable();
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

		// $form->display('ID');
		$form->text('name', '目的地名称')->rules(function ($form) {
			if (!$id = $form->model()->id) {
				return 'required|unique:tx_destinations';
			}
		});

		$c_id = request()->get('category');

		// $form->select('country_id','国家地区')->options(
		// 	Country::orderBy('cname','asc')->pluck('cname','id')
		// 	)->load('city_id','/api/v1/worldcities/getcities')
		// 	->rules('required');
		// $form->select('city_id','城市')->options(function($id){
		// 	return Worldcity::options($id);
		// })->rules('nullable');

		if ($c_id == 1) {
			// $form->select('country_id', '国家/地区')->options(
			// 	Country::china()->orderBy('cname', 'asc')->pluck('cname', 'id')
			// )->rules('required')
			// 	->load('city_id', '/api/v1/worldcities/getcities');
			// $form->select('city_id', '城市')->options(function ($id) {
			// 	return Worldcity::china()->pluck('cn_name', 'id');
			// })->rules('nullable');
			$form->hidden('country_id',101);
			$form->select('city_id', '城市')->options(Worldcity::chinacities()->pluck('cn_name','id'));
		} elseif ($c_id == 2) {
			$form->select('country_id', '国家/地区')->options(
				Country::abroad()->orderBy('cname', 'asc')->pluck('cname', 'id')
			)->rules('required')
				->load('city_id', '/api/v1/worldcities/getcities');
			// $form->hidden('city_id')->default(0);
			$form->select('city_id', '城市')->options(function ($id) {
				return Worldcity::options($id);
			})->rules('nullable');
		} elseif ($c_id == 3) {
			$form->select('country_id', '港澳台地区')->options(
				Country::gangaotai()->orderBy('cname', 'asc')->pluck('cname', 'id')
			)->rules('required')
				->load('city_id', '/api/v1/worldcities/getcities');
		} elseif ($c_id == 4) {
			$form->select('country_id', '国家/地区')->options(
				Country::orderBy('cname', 'asc')->pluck('cname', 'id')
			)->rules('required')
				->load('city_id', '/api/v1/worldcities/getcities');
			$form->select('city_id', '城市')->options(function ($id) {
				return Worldcity::options($id);
			})->rules('nullable');
		} elseif ($c_id == 5) {
			$form->select('country_id', '国家/地区')->options(
				Country::abroad()->orderBy('cname', 'asc')->pluck('cname', 'id')
			)->rules('required');
		} else {
			$form->select('country_id', '国家/地区')->options(
				Country::orderBy('cname', 'asc')->pluck('cname', 'id')
			)->load('city_id', '/api/v1/worldcities/getcities')
				->rules('required');
			$form->select('city_id', '城市')->options(function ($id) {
				return Worldcity::options($id);
			})->rules('nullable');
		}

		$form->multipleSelect('categories', '分类')->options(Category::where('parent_id', 0)
				->orWhere('parent_id', null)
				->pluck('name', 'id'))->default($c_id)->rules('required');
		$form->text('description', '说明');
		$states = [
			'on' => ['value' => 1, 'text' => '打开', 'color' => 'success'],
			'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
		];
		$form->switch('promotion', '推荐')->states($states);
		$form->text('sort', '排序')->rules('required|regex:/^\d+$/|min:1', [
			'regex' => '必须全部为数字',
			'min' => '不能少于一位',
		]);
		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
