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
use Encore\Admin\Show;

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

	public function show($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('China 地市');
			$content->description('查看');

			$content->body(Admin::show(Area::findOrFail($id), function (Show $show) {
				// $show->id('ID');
				$show->areaCode('编码');
				$show->areaName('名称');
				$show->level('等级');
				$show->cityCode('城市区号');
				// $show->center('坐标');
				$show->parent_id('父级');
				//一对多，多个区县
				$show->cities('下辖区县', function ($cities) {
					$cities->resource('/admin/city');
					$cities->areaCode('编码');
					$cities->areaName('名称')->display(function ($c_id) {
						return "<a href='" . $this->id . "' title='添加景区'><span class='label label-info'>" . $this->areaName . "</span></a>";
					});

					$cities->level('等级');
					$cities->cityCode('城市区号');
					$cities->center('坐标');
					$cities->parent_id('父级');
				});
				//一对多，多个景点
				//如果是父节点，不添加				
				$show->sights('景点', function ($sights) {
					$sights->resource('/admin/sight');

					$sights->id('ID');
					$sights->name('名称');
					$sights->city_id('区域名');
					$sights->pictureuri('图片')->image('http://tourxun.test/uploads/', 50, 50);
				});

				$show->panel()
					->style('danger')
					->title('基本信息');
			}));
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

			$grid->disableCreateButton();
			$grid->actions(function ($actions) {
				// prepend一个操作
				$actions->prepend("<a href='city/" . $actions->getKey() . "/addcity'><i class='fa fa-plus-square'></i></a>&nbsp;");
				// $actions->prepend("<a href='sight/city/" . $actions->getKey() . "/addsight'><i class='fa fa-image'></i></a>&nbsp;");
			});

			$grid->filter(function ($filter) {
				$filter->disableIdFilter();
				$filter->like('areaName', 'name');
			});
			$grid->model()->where('level', 2);
			$grid->id('id')->sortable();
			$grid->areaName('区域名')->display(function ($c_id) {
				return "<a href='city/" . $this->id . "'><span class='label label-info'>" . $this->areaName . "</span></a>";
			});
			$grid->cities()->display(function ($cities) {
				$cities = array_map(function ($city) {
					return "<a href='city/{$city['id']}'><span class='label label-success'>{$city['areaName']}</span></a>";
				}, $cities);
				return join('&nbsp;', $cities);
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

			// $form->tools(function (Form\Tools $tools) {
			// 	// 添加一个按钮, 参数可以是字符串, 或者实现了Renderable或Htmlable接口的对象实例
			// 	$tools->add('<a class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;delete</a>');
			// });

			$form->display('id', 'ID');
			$p_id = request()->get('parent_id');
			// $form->text('city_id', '区域id')->value($p_id);

			$form->text('parent_id', '父节点')->value($p_id);
			$form->text('areaCode', '区域编码')->rules('required|regex:/^\d+$/|min:6', [
				'regex' => '区域编码 必须全部为数字',
				'min' => '区域编码 不能少于6各字符',
			]);
			$form->text('areaName', '地区名')->rules('required|min:2');
			$form->number('level', '级别');
			$form->text('cityCode', '城市区号');
			$form->text('center', '经纬度');
			$form->hasMany('cities', '添加区县', function (Form\NestedForm $form) {
				$form->text('areaCode', '区域编码');
				$form->text('areaName', '地区名');
				$form->number('level', '级别');
				$form->text('cityCode', '城市区号');
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

			$form->text('parent_id', '父级')->value($pid['city'])->help('请勿更改！');
			$form->text('areaCode', '区域编码')->rules('required|regex:/^\d+$/|min:6', [
				'regex' => '区域编码 必须全部为数字',
				'min' => '区域编码 不能少于6各字符',
			]);
			$form->text('areaName', '地区名')->rules('required|min:2');
			$form->number('level', '级别')->min(1);
			$form->text('cityCode', '城市区号');
			$form->text('center', '经纬度');
		});
	}

}
