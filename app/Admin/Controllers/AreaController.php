<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class AreaController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('China Area');
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

			$content->body($this->biaodan()->edit($id));
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

			$content->body($this->biaodan());
		});
	}

	public function addarea($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('addarea');
			$content->description('create');
			// dd($id);

			$content->body($this->add_biaodan());
		});
	}


	public function cascading() {
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
	protected function grid() {
		return Admin::grid(Area::class, function (Grid $grid) {

			$grid->filter(function($filter){
				$filter->disableIdFilter();
				$filter->like('areaName','name');
			});
			$grid->id('id')->sortable();
			$grid->areaName('区域名')->editable();
			$grid->level('等级');
			$grid->cityCode('城市编码');
			$grid->center('经纬度');
			$grid->parent_id('父节点');

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

			$provinces = Area::where('parent_id', '-1')->pluck('areaName', 'id');
			// dd($provinces);
			$form->select('Provinces')->options($provinces)->load('cities', '/api/v1/areas/city');
			// $form->text('areaCode', '区域编码');
			$form->select('cities')->options($provinces)->load('county', '/api/v1/areas/city');
			$form->select('county');

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}

	protected function biaodan() {
		return Admin::form(Area::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('areaCode', '区域编码');
			$form->text('areaName', '地区名');
			$form->number('level', '级别');
			$form->text('cityCode', '城市编码');
			$form->text('center', '经纬度');
			$form->text('parent_id', '父节点');

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}

	protected function add_biaodan() {
		return Admin::form(Area::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('areaCode', '区域编码');
			$form->text('areaName', '地区名');
			$form->number('level', '级别');
			$form->text('cityCode', '城市编码');
			$form->text('center', '经纬度');
			$form->display('parent_id', '父节点');

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}	

}
