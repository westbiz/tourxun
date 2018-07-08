<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\MessageBag;

class CityController extends Controller {
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



	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	//地市grid	
	protected function grid() {
		return Admin::grid(Area::class, function (Grid $grid) {

			$grid->filter(function ($filter) {
				$filter->disableIdFilter();
				$filter->like('areaName', 'name');
			});
			$grid->model()->where('level', 2);
			$grid->id('id')->sortable();
			$grid->areaName('区域名')->editable();
			$grid->cities()->display(function ($cityies) {
				$cityies = array_map(function ($city) {
					return "<span class='label label-success'>{$city['areaName']}</span>";
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
			$form->text('areaCode', '区域编码')->rules('required|regex:/^\d+$/|min:6',[
				'regex'=>'区域编码 必须全部为数字',
				'min'=>'区域编码 不能少于6各字符',
			]);
			$form->text('areaName', '地区名')->rules('required|min:2');
			$form->number('level', '级别');
			$form->text('cityCode', '城市编码');
			$form->text('center', '经纬度');
			$form->text('parent_id', '父节点');
			$form->hasMany('cities', '添加子类', function (Form\NestedForm $form) {
				$form->text('areaCode', '区域编码');
				$form->text('areaName', '地区名');
				$form->number('level', '级别');
				$form->text('cityCode', '城市编码');
				$form->text('center', '经纬度');
			});
			// $form->saving(function(Form $form){
			// 	throw new \Exception('出错...');
				
			// });

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}





}
