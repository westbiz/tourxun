<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Sight;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Form\Tools;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\Actions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SightController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('景区');
			$content->description('列表');

			$content->body($this->grid());
		});
	}

	public function show($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('景点 ');
			$content->description('查看');

			$content->body(Admin::show(Sight::findOrFail($id), function (Show $show) {
				// $show->id('ID');
				$show->name('名称');
				// $show->pictureuri('图片')->image();
				$show->summary('概况');
				$show->content('内容');
				// $sit = Sight::find(2);
				// // dd($sit);
				// foreach ($sit->pictures as $picture) {
				// 	dd($picture);
				// }
				$show->pictures('多态图片', function ($pictures) {
					$pictures->resource('admin/sight');
					$pictures->title();
					$pictures->pictureuri()->image();
					$pictures->description();
				});

				$show->spot('所有景点', function ($spot) {
					$spot->resource('/admin/sight');
					$spot->id();
					$spot->name();
					$spot->city_id();
					$spot->summary();
				});

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

			$content->header('景点');
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

			$content->header('景点');
			$content->description('新增');

			$content->body($this->form());
		});
	}

	public function createsight() {
		return Admin::content(function (Content $content) {

			$content->header('景点');
			$content->description('新增');

			$content->body($this->createform());
		});
	}

	// 从city add sight方法
	public function cityaddsight($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('China 地市景点');
			$content->description('新增');

			$content->body($this->cityaddsightform());
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Sight::class, function (Grid $grid) {

			//关掉创建按钮
			// $grid->disableCreateButton();
			//关掉批量删除
			// $grid->expandFilter();
			$grid->filter(function ($filter) {
				//去掉ID过滤器
				$filter->disableIdFilter();
				//添加字段过滤
				$filter->like('name', '景点名称')->placeholder('请输入名称');
				//时间段
				$filter->scope('new', '最近修改')
					->whereDate('created_at', date('Y-m-d'))
					->orWhere('updated_at', date('Y-m-d'));

				$filter->scope('areaName', '只看有图片')->whereHas('city', function ($query) {
					$query->whereNotNull('areaName');
				});
				//关联关系查询
				$filter->where(function ($query) {
					$query->whereHas('city', function ($query) {
						$query->where('areaName', 'like', "%{$this->input}%");
					});
				}, '地区名称');
			});
			//grid操作及控制，添加操作
			$grid->actions(function ($actions) {
				// prepend一个操作
				$s_id = $actions->getKey();
				$c_id = Sight::where('id', $s_id)->pluck('city_id')->all();
				// dd($c_id);
				$actions->prepend("<a href='sight/create?parent_id=" . $actions->getKey() . "' title='添加子类'><i class='fa fa-plus-square'></i></a>&nbsp;");
			});

			//修改源数据
			$grid->model()->where('parent_id', -1);
			$grid->tools(function ($tools) {
				$tools->batch(function ($batch) {
					$batch->disableDelete();
				});
			});
			//grid
			$grid->id('ID')->sortable();
			$grid->name('名称')->editable();
			$grid->city()->areaName('区域');
			$grid->parent_id('父级');
			$grid->spot()->display(function ($sights) {
				$sights = array_map(function ($sight) {
					return "<a href='sight/{$sight['id']}'><span class='label label-info'>{$sight['name']}</span></a>";
				}, $sights);
				return join('&nbsp;', $sights);
			});
			$grid->pictureuri('图片')->image('http://tourxun.test/uploads/', 50, 50);
			$grid->summary('概况');
			$grid->content('内容');

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
		return Admin::form(Sight::class, function (Form $form) {

			$form->display('id', 'ID');
			//获取参数city_id
			$c_id = request()->get('city_id');
			//获取参数parent_id
			$p_id = request()->get('parent_id');
			//获取sight_id
			$s_id = request()->route()->parameters('sight');
			// dd($c_id);

			if ($s_id != null) {
				// dd($s_id['sight']);
				$city = Sight::where('id', $s_id['sight'])->pluck('city_id')->all();

				$form->text('city_id', '所属区域ID')->value($city);
				// dd($city[0]);
				$form->text('parent_id', '父级');
			} elseif ($c_id != null) {
				$form->text('parent_id', '父级')->value('-1');
				// $city = Sight::where('id', $p_id)->pluck('id')->all();
				// dd($c_id);
				$form->text('city_id', '所属区域ID')->value($c_id);
			} elseif ($p_id != null) {
				// dd($p_id);
				$form->text('parent_id', '父级')->value($p_id);
				$city = Sight::where('id', $p_id)->pluck('city_id')->all();
				// dd($city[0]);
				$form->text('city_id', '所属区域ID')->value($city[0]);
			}

			// elseif ($s_id != null) {

			// 	$city = Sight::where('id', $s_id)->pluck('city_id')->all();

			// 	$form->text('city_id', '所属区域ID')->value($city);
			// 	// dd($s_id);
			// 	$form->text('parent_id', '父级');
			// }
			// elseif ($p_id != null) {
			// 	$form->text('parent_id', '父级')->value($p_id);
			// 	$city = Sight::where('id', $p_id)->pluck('city_id')->all();
			// 	$form->text('city_id', '所属区域ID')->value($city[0]);
			// 	// dd($p_id);
			// }

			else {
				$provinces = Area::where('parent_id', '-1')->pluck('areaName', 'id');
				$form->select('Provinces', '省区')->options($provinces)->load('cities', '/api/v1/area/city');
				$form->select('cities', '地市')->options($provinces)->load('city_id', '/api/v1/area/city');
				$form->select('city_id', '区县');
				$form->text('parent_id', '父级')->value('-1');

			}

			// if ($p_id != null) {
			// 	$form->text('parent_id', '父级')->value($p_id);
			// 	$city = Area::where('id', $p_id)->pluck('id')->all();
			// 	$form->text('city_id', '所属区域ID')->value($city[0]);
			// } else {
			// 	$form->text('parent_id', '父级')->value('-1');
			// }

			// dd($c_id);
			// $form->text('city_id', '所属区域ID');

			$form->text('name', '名称');
			$form->multipleImage('pictureuri', '图片')->removable();
			$form->text('summary', '概述');
			$form->textarea('content', '介绍');
			// $form->embeds('extra', function ($form) {
			// 	$form->text('title', '标题')->rules('required');
			// 	$form->text('author', '作者');
			// 	$form->datetime('updatetime', '日期');
			// 	$form->image('pic', '图片')->removable();
			// });

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}

	//createsight,直接新建sight
	protected function createform() {
		return Admin::form(Sight::class, function (Form $form) {

			// $form->html('*选择*', '区域');
			$provinces = Area::where('parent_id', '-1')->pluck('areaName', 'id');
			// dd($provinces);
			$form->select('Provinces', '省区')->options($provinces)->load('cities', '/api/v1/area/city');
			$form->select('cities', '地市')->options($provinces)->load('city_id', '/api/v1/area/city');
			$form->select('city_id', '区县')->rules('bail|required');
			$form->divide();

			$form->display('id', 'ID');
			$form->text('parent_id', '父级')->default('-1');
			$form->text('name', '名称');
			$form->multipleImage('pictureuri', '图片')->removable();
			$form->text('summary', '概述');
			$form->textarea('content', '介绍');
			// $form->embeds('extra', function ($form) {
			// 	$form->text('title', '标题')->rules('required');
			// 	$form->text('author', '作者');
			// 	$form->datetime('updatetime', '日期');
			// 	$form->image('pic', '图片')->removable();
			// });

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}

	protected function cityaddsightform() {
		return Admin::form(Sight::class, function (Form $form) {

			$form->display('id', 'ID');
			// $city = request()->get('city_id');
			$city = request()->route()->parameters('city');
			$form->text('city_id', '所属区域ID')->value($city['city']);
			// $p_id = request()->get('parent_id');
			// $form->text('parent_id', '父级')->value($p_id);

			// $city = request()->get('city_id');
			// if ($city == null) {
			// 	$city = request()->route()->parameters('city');

			// }
			// dd($city);
			// $form->text('city_id', '所属区域ID')->value($city['city']);
			$form->text('parent_id', '父级')->default('-1');
			$form->text('name', '名称');
			$form->multipleImage('pictureuri', '图片')->removable();
			$form->text('summary', '概述');
			$form->textarea('content', '介绍');
			// $form->embeds('extra', function ($form) {
			// 	$form->text('title', '标题')->rules('required');
			// 	$form->text('author', '作者');
			// 	$form->datetime('updatetime', '日期');
			// 	$form->image('pic', '图片')->removable();
			// });

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}

}
