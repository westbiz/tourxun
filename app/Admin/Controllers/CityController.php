<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\CityController;
use App\Http\Controllers\Controller;
use App\Models\Area;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class CityController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('China 地市');
			$content->description('管理');

			$content->body($this->grid());
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

			$content->header('China 地市');
			$content->description('编辑');

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

			$content->header('China 地市');
			$content->description('创建');

			$content->body($this->form());
		});
	}

	public function addcity($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('China 地市');
			$content->description('新增');

			$content->body($this->addcityform());
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	//地市grid
	protected function grid() {
		return Admin::grid(Area::class, function (Grid $grid) {

			$grid->actions(function ($actions) {
				// prepend一个操作
				$actions->prepend("<a href='city/" . $actions->getKey() . "/addcity'><i class='fa fa-plus-square'></i></a>&nbsp;");
				$actions->prepend("<a href='sight/city/" . $actions->getKey() . "/addsight'><i class='fa fa-image'></i></a>&nbsp;");
			});

			$grid->filter(function ($filter) {
				$filter->disableIdFilter();
				$filter->like('areaName', 'name');
			});
			$grid->model()->where('level', 2);
			$grid->id('id')->sortable();
			$grid->areaName('区域名')->editable();
			$grid->cities()->display(function ($cityies) {
				$cityies = array_map(function ($city) {
					return "<a href='city/{$city['id']}/edit'><span class='label label-success'>{$city['areaName']}</span></a>";
				}, $cityies);
				return join('&nbsp;', $cityies);
			});

			// $grid->created_at();
			// $grid->updated_at();
		});
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		return Admin::form(Area::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('parent_id', '父节点');
			$form->text('areaCode', '区域编码')->rules('required|regex:/^\d+$/|min:6', [
				'regex' => '区域编码 必须全部为数字',
				'min' => '区域编码 不能少于6各字符',
			]);
			$form->text('areaName', '地区名')->rules('required|min:2');
			$form->number('level', '级别');
			$form->text('cityCode', '城市编码');
			$form->text('center', '经纬度');
			$form->hasMany('cities', '添加区县', function (Form\NestedForm $form) {
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

	//新增辖区表单
	protected function addcityform() {
		return Admin::form(Area::class, function (Form $form) {

			$pid = request()->route()->parameters('city');
			$pname = Area::where('id', $pid['city'])->pluck('areaName')->all();

			$form->text('parent_id', $pname[0])->value($pid['city'])->help('请勿更改！');
			$form->text('areaCode', '区域编码')->rules('required|regex:/^\d+$/|min:6', [
				'regex' => '区域编码 必须全部为数字',
				'min' => '区域编码 不能少于6各字符',
			]);
			$form->text('areaName', '地区名')->rules('required|min:2');
			$form->number('level', '级别')->min(1);
			$form->text('cityCode', '城市编码');
			$form->text('center', '经纬度');
		});
	}

}
