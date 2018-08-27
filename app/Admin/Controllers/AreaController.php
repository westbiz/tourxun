<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class AreaController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('China 省区');
			$content->description('管理');

			$content->body($this->grid());

		});
	}

	//调用citygrid获取地市grid
	public function getcities() {
		return Admin::content(function (Content $content) {

			$content->header('China 地市');
			$content->description('管理');

			$content->body($this->citygrid());
		});
	}

	/**
	 * Edit interface.
	 *
	 * @param $id
	 * @return Content
	 */
	public function edit($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('header');
			$content->description('description');

			$content->body($this->form()->edit($id));
		});
	}

	/**
	 * Create interface.
	 *
	 * @return Content
	 */
	public function create() {
		return Admin::content(function (Content $content) {

			$content->header('header');
			$content->description('description');

			$content->body($this->form());
		});
	}

	//select调用cascadingfomr
	public function cascading() {
		return Admin::content(function (Content $content) {

			$content->header('header');
			$content->description('description');

			$content->body($this->cascadingform());
		});
	}

	public function show($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('China 地市');
			$content->description('查看');

			$content->body(Admin::show(Area::findOrFail($id), function (Show $show) {
				// $show->id('ID');
				$show->areaCode('编码');
				$show->areaName('名称');
				// $show->level('等级');
				// $show->cityCode('城市代码');
				// $show->center('坐标');
				// $show->parent_id('父级');
				$show->cities('下辖区域', function ($cities) {
					$cities->resource('/admin/city');

					$cities->id('ID');
					$cities->areaCode('编码');
					$cities->areaName('名称');
					$cities->level('等级');
					$cities->cityCode('城市代码');
					$cities->center('坐标');
					$cities->parent_id('父级');

				});
				$show->panel()
					->style('danger')
					->title('基本信息');
			}));
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Area::class, function (Grid $grid) {

			$grid->actions(function ($actions) {
				// prepend一个操作
				$actions->prepend("<a href='city/" . $actions->getKey() . "/addcity'><i class='fa fa-plus-square'></i></a>&nbsp;");
			});

			$grid->filter(function ($filter) {
				$filter->disableIdFilter();
				$filter->like('areaName', 'name');
			});
			$grid->model()->where('parent_id', -1);
			$c_id = $grid->id('id');
			// dd($c_id);
			$grid->areaName('区域名')->display(function ($c_id) {
				return "<a href='city/" . $this->id . "'><span class='label label-success'>" . $this->areaName . "</span></a>";
			});
			$grid->cities()->display(function ($cities) {
				$cities = array_map(function ($city) {
					return "<a href='area/{$city['id']}'><span class='label label-info'>{$city['areaName']}</span></a>";
				}, $cities);
				return join('&nbsp;', $cities);
			});

			// $grid->created_at();
			// $grid->updated_at();
		});
	}

	//地市grid
	// protected function citygrid() {
	// 	return Admin::grid(Area::class, function (Grid $grid) {

	// 		$grid->filter(function ($filter) {
	// 			$filter->disableIdFilter();
	// 			$filter->like('areaName', 'name');
	// 		});
	// 		$grid->model()->where('level', 2);
	// 		$grid->id('id')->sortable();
	// 		$grid->areaName('区域名')->editable();
	// 		$grid->cities()->display(function ($cityies) {
	// 			$cityies = array_map(function ($city) {
	// 				return "<a href='cities/{$city['id']}/edit'><span class='label label-success'>{$city['areaName']}</span></a>";
	// 			}, $cityies);
	// 			return join('&nbsp;', $cityies);
	// 		});

	// 		// $grid->created_at();
	// 		// $grid->updated_at();
	// 	});
	// }

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		return Admin::form(Area::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('areaCode', '区域编码');
			$form->text('areaName', '地区名');
			$form->number('level', '级别');
			$form->text('cityCode', '城市编码');
			$form->text('center', '经纬度');
			$form->text('parent_id', '父节点');
			$form->hasMany('cities', '添加下辖区县', function (Form\NestedForm $form) {
				$form->text('areaCode', '区域编码');
				$form->text('areaName', '地区名');
				$form->number('level', '级别');
				$form->text('cityCode', '城市编码');
				$form->text('center', '经纬度');
			});

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}

	//ajax三级联动select
	protected function cascadingform() {
		return Admin::form(Area::class, function (Form $form) {

			$provinces = Area::where('parent_id', '-1')->pluck('areaName', 'id');
			// dd($provinces);
			$form->select('Provinces')->options($provinces)->load('cities', '/api/v1/area/city');
			$form->select('cities')->options($provinces)->load('city_id', '/api/v1/area/city');
			$form->select('city_id');
			$form->setAction('cascading/c_id/{$city_id}');
			// dd($form->select('county'));
			// $form->saving(function(Form $form){
			// 	return response('xxxx');
			// 	// return redirect('/admin/city');
			// });
		});
	}

}
