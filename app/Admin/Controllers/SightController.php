<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Sight;
use App\Models\Sighttype;
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

			$content->header('景区 ');
			$content->description('查看');

			$content->body(Admin::show(Sight::findOrFail($id), function (Show $show) {

				$show->panel()->style('info')->title('详情');
				$show->name('名称');
				$show->city()->areaName('区域');
				$show->rate();
				$show->avatar('图片')->image();
				$show->categories('类型')->as(function ($categories) {
					return $categories->pluck('name');
				})->label('info');
				$show->extra('扩展项')->display(function ($extra) {
					return "<span>{$extra}</span>";
				})->badge();
				$show->summary('概况');
				$show->content('内容');
				//所有景点
				$show->spot('所有景点', function ($spot) {
					$spot->resource('/admin/sights');
					$spot->id();
					$spot->name('名称');
					$spot->city()->areaName('区域');
					$spot->summary('概述');
				});
				//多态关联图片
				$show->pictures('多态_图片', function ($pictures) {
					$pictures->resource('/admin/pictures');
					$pictures->id('ID');
					$pictures->title('标题');
					$pictures->pictureuri('多态图片')->image();
					$pictures->description('描述');

					//关闭创建按钮
					// dd($sid = request()->route()->parameters());
					$pictures->disableCreateButton();
					//添加自定义按钮
					$pictures->tools(function ($tools) {
						$sid = request()->route()->parameters('sight');
						$tools->append("<a href='/admin/pictures/create?sight_id={$sid['sight']}&type=Sight' class='btn btn-default'>添加图片</a>");
					});

				});

				$show->comments('评论', function ($comments) {
					$comments->resource('/admin/comments');
					$comments->id();
					$comments->content();
					$comments->pictureuri();
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

			$content->header('景区');
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

			$content->header('景区');
			$content->description('新增');

			$content->body($this->form());
		});
	}

	// public function createsight() {
	// 	return Admin::content(function (Content $content) {

	// 		$content->header('景点');
	// 		$content->description('新增');

	// 		$content->body($this->createform());
	// 	});
	// }

	// // 从city add sight方法
	// public function cityaddsight($id) {
	// 	return Admin::content(function (Content $content) use ($id) {

	// 		$content->header('China 地市景点');
	// 		$content->description('新增');

	// 		$content->body($this->cityaddsightform());
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
			// $grid->expandFilter();
			$grid->model()->with('pictures:id,pictureuri');
			$grid->model()->with('comments');
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
				$actions->prepend("<a href='sights/create?parent_id=" . $actions->getKey() . "' title='添加子类'><i class='fa fa-plus-square'></i></a>&nbsp;");
				$actions->prepend("<a href='pictures/create?sight_id=" . $actions->getKey() . "&type=Sight' title='添加图片'><i class='fa fa-plus'></i></a>&nbsp;");
			});

			//修改源数据
			$grid->model()->where('parent_id', -1);
			$grid->tools(function ($tools) {
				$tools->batch(function ($batch) {
					$batch->disableDelete();
				});
			});

			$grid->id('ID');
			$grid->name('名称')->editable();
			$grid->avatar('图片')->lightbox(['http://tourxun.test/uploads/', 'width' => 50, 'height' => 50, 'zooming' => true]);
			// $pid = $grid->city()->parent_id();
			$grid->city()->parent_id('地区')->display(function ($parent_id) {
				return Area::where('id', $parent_id)->pluck('areaName')->all();
			})->label();
			$grid->city()->areaName('所属区域');

			$grid->rate('星级');
			$grid->comments('点评')->count()->badge();

			$grid->categories('类型')->pluck('name')->label('info');

			$grid->extra('门票')->display(function ($extra) {
				return "<span>{$extra['price']}</span>";
			})->badge();
			$grid->spot('景点数')->count();

			// $grid->pictures('多图')->pluck('pictureuri')->display(function ($pictureuri) {
			// 	return json_decode($pictureuri, true);
			// })->map(function ($path) {
			// 	return $path[0];
			// })->image('http://tourxun.test/uploads/', 50);

			// $grid->pictures()->pluck('pictureuri')->map(function ($item, $key) {
			// 	// return $item[0];
			// 	return count($item) > 0 ? $item[0] : 'noimage.png';
			// })->image('http://tourxun.test/uploads/', 50, 50);
			// $grid->pictures()->pluck('pictureuri')->image('http://tourxun.test/uploads/', 50, 50);

			$grid->summary('概况')->limit(30);
			// $grid->content('内容');

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
			$form->tab('基本信息', function ($form) {

				//获取参数city_id
				$c_id = request()->get('city_id');
				// dd($c_id);
				//获取参数parent_id
				$p_id = request()->get('parent_id');
				// //获取sight_id
				$s_id = request()->route()->parameters('sight');
				// // dd($c_id);

				// if ($s_id != null) {
				// 	// dd($s_id['sight']);
				// 	$s_name = Sight::where('id', $s_id)->pluck('name')->all();
				// 	$form->html($label = $s_name[0], '添加到');
				// 	$city_id = Sight::where('id', $s_id['sight'])->pluck('city_id')->all();

				// 	$form->text('city_id', '所属区域ID')->value($city_id);
				// 	// dd($city[0]);
				// 	$form->text('parent_id', '父级');
				// } elseif ($c_id != null) {
				// 	$form->text('parent_id', '父级')->value('-1');
				// 	// $city = Sight::where('id', $p_id)->pluck('id')->all();
				// 	// dd($c_id);
				// 	$form->text('city_id', '所属区域ID')->value($c_id);
				// } elseif ($p_id != null) {
				// 	$s_name = Sight::where('id', $p_id)->pluck('name')->all();
				// 	$c_id = Sight::where('id', $p_id)->pluck('city_id')->all();
				// 	$form->html($label = $s_name[0], '添加到');

				// 	$form->text('parent_id', '父级')->value($p_id);
				// 	// dd($city[0]);
				// 	$form->text('city_id', '所属区域ID')->value($c_id[0]);
				// }

				// // elseif ($s_id != null) {

				// // 	$city = Sight::where('id', $s_id)->pluck('city_id')->all();

				// // 	$form->text('city_id', '所属区域ID')->value($city);
				// // 	// dd($s_id);
				// // 	$form->text('parent_id', '父级');
				// // }
				// elseif ($p_id != null) {
				// 	$form->text('parent_id', '父级')->value($p_id);
				// 	$city = Sight::where('id', $p_id)->pluck('city_id')->all();
				// 	$form->text('city_id', '所属区域ID')->value($city[0]);
				// 	// dd($p_id);
				// }

				// else {
				// 	$provinces = Area::where('parent_id', '-1')->pluck('areaName', 'id');
				// 	$form->select('Provinces', '省区')->options($provinces)->load('cities', '/api/v1/area/city');
				// 	$form->select('cities', '地市')->options($provinces)->load('city_id', '/api/v1/area/city');
				// 	$form->select('city_id', '区县');
				// 	$form->text('parent_id', '父级')->value('-1');

				// }

				//如果存在sightid
				if ($s_id) {
					//默认为-1
					$form->display('id', 'ID');
					$form->text('parent_id', '父级');
					$form->select('shengqu', '省区')->options(
						Area::shengqu()->pluck('areaName', 'id')
					)->load('chengshi', '/api/v1/area/city');

					$form->select('chengshi', '市辖区')->options(function ($id) {
						return Area::options($id);
					})->load('city_id', '/api/v1/area/district');

					$form->select('city_id', '区县')->options(function ($id) {
						return Area::options($id);
					});
				} elseif ($p_id) {
					$form->text('parent_id', '父级')->value($p_id);
					$city_id = Sight::find($p_id)->city->id;
					$form->select('city_id', '区域')->options(
						Area::where('id', $city_id)->pluck('areaName', 'id')
					)->default($city_id);
				} elseif ($c_id) {
					$form->text('parent_id', '父级')->value('-1');

					$form->select('city_id', '区域')->options(
						Area::where('id', $c_id)->pluck('areaName', 'id')
					)->default($c_id);
				} else {
					$form->text('parent_id', '父级')->value('-1');
					$form->select('shengqu', '省区')->options(
						Area::shengqu()->pluck('areaName', 'id')
					)->load('chengshi', '/api/v1/area/city');

					$form->select('chengshi', '市辖区')->options(function ($id) {
						return Area::options($id);
					})->load('city_id', '/api/v1/area/district');

					$form->select('city_id', '区县')
						->rules('required', ['required' => '必要字段不能为空!'])
						->options(function ($id) {
							return Area::options($id);
						});
				}

				$form->text('name', '名称')->rules(function ($form) {
					return 'required|min:2|unique:tx_sights,name,' . $form->model()->id . ',id';
				});
				// if (request()->isMethod('POST')) {
				// 		return 'required|unique:tx_sights,name,';
				// 	}
				// 	if (request()->isMethod('PUT')) {
				// 		return 'required';
				// 	}
				$form->rate('rate', '星级');

				//通过categories获取分类表对应类型
				$form->checkbox('sighttype', '类型')->options(Sighttype::all()->pluck('name', 'id'));

				// $editor1 = new Editor();
				$form->editor('content', '介绍');
				$form->cropper('avatar', '图片');
				// $form->multipleImage('pictureuri', '图片')->removable();
				$form->text('summary', '概述');

				//忽略字段
				$form->ignore(['shengqu', 'chengshi']);
				// $form->display('created_at', 'Created At');
				// $form->display('updated_at', 'Updated At');

			})->tab('扩展属性', function ($form) {
				$form->embeds('extra', '扩展项目', function ($form) {
					$form->currency('price', '门票价格')->symbol('￥');
					$form->text('opentime', '开放时间');
					$form->textarea('offer', '优惠信息');
					$form->text('traffic', '交通');
					$form->image('pic', '图片')->removable();
				});
			})->tab('图片', function ($form) {
				$form->hasMany('pictures', '多态图片', function (Form\NestedForm $form) {
					$form->text('title', '标题');
					$dir = 'images/' . date('Y') . '/' . date('m') . '/' . date('d');
					$form->multipleFile('pictureuri', '图片')->removable()->move($dir)->uniqueName();
					$form->text('description', '描述');
				});
			})->tab('子类景点', function ($form) {
				$form->hasMany('spot', '所有景点', function (Form\
					NestedForm $form) {
					$form->text('name', '名称');

					$form->editor('content', '介绍');
					$form->image('avatar', '图片');
					// $form->multipleImage('pictureuri', '图片')->removable();
					$form->text('summary', '概述');

				});
			});

		});
	}

}
