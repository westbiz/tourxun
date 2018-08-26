<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sight;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
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

			$content->header('景点');
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

	//addsight方法
	// public function addsight($id) {
	// 	return Admin::content(function (Content $content) use ($id) {

	// 		$content->header('China 地市景点');
	// 		$content->description('新增');

	// 		$content->body($this->addsightform());
	// 	});
	// }

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
			$grid->tools(function ($tools) {
			    $tools->batch(function ($batch) {
			        $batch->disableDelete();
			    });
			});			

			$grid->expandFilter();
			
			$grid->filter(function($filter){
				//去掉ID过滤器
				$filter->disableIdFilter();
				//添加字段过滤
				$filter->like('name','景点名称')->placeholder('请输入名称');
				//时间段
				$filter->scope('new', '最近修改')
					->whereDate('created_at',date('Y-m-d'))
					->orWhere('updated_at', date('Y-m-d'));

				$filter->scope('areaName','只看有图片')->whereHas('city',function($query){
					$query->whereNotNull('areaName');
				});
				//关联关系查询
				$filter->where(function($query){
					$query->whereHas('city',function($query){
						$query->where('areaName','like', "%{$this->input}%");
					});
				},'地区名称');

			});

			$grid->id('ID')->sortable();
			$grid->name('名称');
			$grid->city()->areaName('区域');
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
			$c_id = request()->get('city_id');
			$form->text('city_id', '所属区域ID')->value($c_id);
			$form->text('name', '名称');
			$form->multipleImage('pictureuri', '图片')->removable();
			$form->text('summary', '概述');
			$form->textarea('content', '介绍');
			$form->embeds('extra', function ($form) {
				$form->text('title', '标题')->rules('required');
				$form->text('author', '作者');
				$form->datetime('updatetime', '日期');
				$form->image('pic', '图片')->removable();
			});

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}

	//新增景点表单
	// protected function addsightform() {
	// 	return Admin::form(Area::class, function (Form $form) {

	// 		$pid = request()->route()->parameters('city');
	// 		$pname = Area::where('id', $pid['city'])->pluck('areaName')->all();

	// 		$form->text('city_id', $pname[0])->value($pid['city'])->help('请勿更改！');
	// 		$form->text('name', '景点名称')->rules('required|min:2', ['min' => '区域编码 不能少于2各字符',
	// 		]);

	// 		$form->text('name', '名称');
	// 		$form->multipleImage('pictureuri', '图片')->removable();
	// 		$form->text('summary', '概述');
	// 		$form->textarea('content', '介绍');

	// 	});
	// }

}
