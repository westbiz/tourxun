<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attrvalue;
use App\Models\Catattr;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Models\Worldcity;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller {
	use HasResourceActions;

	/**
	 * Index interface.
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function index(Content $content) {
		return $content
			->header('产品')
			->description('列表')
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
			->header('详细')
			->description('详细')
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
			->header('更改')
			->description('详细')
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
			->header('创建')
			->description('表单')
			->body($this->form());
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		$grid = new Grid(new Product);

		// $grid->disableActions();
		// $grid->actions(function ($actions) {
		// 	$p_id = $actions->getKey();

		// 	$c_id = $actions->row->category_id;
		// 	$d_id = request()->get('d_id');
		// 	// $d_id = $actions->row->destinations()->pivot()->destination_id;
		// 	// dd($d_id);
		// 	$actions->prepend("<a href='products/" . $p_id . "/edit?c_id=" . $c_id . "' title='添加子类'><i class='fa fa-plus-square'></i></a>&nbsp;");
		// });
		$grid->actions(function ($actions) {
			$actions->disableEdit();
			$p_id = $actions->getKey();
			$c_id = $actions->row->category_id;
			$actions->prepend("<a href='products/" . $p_id . "/edit?c_id=" . $c_id . "' title='编辑'><i class='fa fa-edit'></i>编辑</a>&nbsp;");
			$actions->prepend("<a href='prices/create?p_id=" . $p_id . "&c_id=" . $c_id . "' title='添加价格'><i class='fa fa-plus-square'></i>价格</a>&nbsp;");
		});

		$grid->id('ID')->sortable();
		$grid->avatar('图片')->display(function ($avatar) {
			return "<img src='http://tourxun.test/uploads/$avatar' height='10%' width='20%' class='img img-thumbnail'>";
		});
		$grid->name('名称')->display(function ($name) {
			return "<a href='/admin/products/1/edit?c_id=" . $this->category_id . "&d_id=" . $this->destinations . "'>$name</a>";
		});

		// $grid->destinations('目的地')->display(function ($destinations) {
		//  $destinations = array_map(function ($destination) {
		//      return "{$destination['id']}";
		//  }, $destinations);
		//  return join('&nbsp;', $destinations);
		// });

		// $grid->pictureuri('图片')->image('http://tourxun.test/uploads/', 50, 50);
		$grid->category()->name('分类')->label('danger');
		$grid->day('天数');
		$grid->night('晚数');
		// $grid->hotel('酒店');
		$grid->star('评星');
		$grid->prices('价格')->display(function ($prices) {
			$prices = array_map(function ($price) {
				return "<span class='label label-info'>{$price['schedule']} : {$price['price']}</span>";
			}, $prices);
			return join('&nbsp;', $prices);
		})->style('max-width:300px;word-break:break-all;');
		$grid->summary('概述');

		// $grid->column('actions', '操作')->displayUsing(CustomActions::class);

		// $grid->content('正文')->limit(30);
		// $grid->active('激活');

		// $grid->created_at();
		// $grid->updated_at();

		return $grid;
	}

	/**
	 * Make a show builder.
	 *
	 * @param mixed $id
	 * @return Show
	 */
	protected function detail($id) {
		$show = new Show(Product::findOrFail($id));

		$show->name('名称');
		$show->avatar('图片')->unescape()->as(function ($avatar) {
			return "<img src='http://tourxun.test/uploads/{$avatar}'>";
		});
		$show->summary('概况');
		// $show->content('内容')->as(function ($content) {
		//  return "<pre>{$content}</pre>";
		// });
		$show->prices('价格', function ($prices) {
			$prices->resource('/admin/prices');
			$prices->id('ID');
			$prices->schedule('出发日期');
			$prices->quantity('数量');
			$prices->price('价格');
			$prices->remark('备注');
		});

		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		$form = new Form(new Product);

		$c_id = request()->get('c_id');
		$d_id = request()->get('d_id');
		$form->select('category_id', '分类')->options(
			Category::pluck('name', 'id')
		)->load('destinations', '/api/v1/categories/children')->rules('required')->default($c_id)->disable();
		// $form->display('id', 'ID');
		// $category = Category::where('id', $c_id)->pluck('name');
		// // dd($category[0]);
		// $form->text('category', '分类')->default($category[0])->readonly();
		$form->text('name', '名称')->rules('required|min:3');


		// $form->select('departure', '出发城市')->options(Worldcity::chinacities()->departure()->pluck('cn_name','id'))->load('cities','/api/v1/worldcities/aroundcities');

		// $form->multipleSelect('destinations', '目的地')->options(Destination::pluck('name', 'id'))->default($d_id);
		if ($c_id == 1) {
			// $form->multipleSelect('countries', '目的地 地区')->options(Country::china()->pluck('cname', 'id'));
			// $form->multipleSelect('cities', '目的地 城市')->options(Worldcity::chinacities()->orderBy('promotion', 'desc')->pluck('cn_name', 'id'));
			$form->select('departure', '出发城市')->options(Worldcity::chinacities()->departure()->pluck('cn_name','id'));
			$form->multipleSelect('cities', '目的地 城市')->options(Worldcity::china()->pluck('cn_name','id'))->ajax('/api/v1/chinacities/ajax');
		} elseif ($c_id == 2) {
			$form->select('departure', '出发城市')->options(Worldcity::chinacities()->departure()->pluck('cn_name','id'));
			// ->load('cities','/api/v1/worldcities/aroundcities');
			$form->multipleSelect('countries', '目的地 地区')->options(Country::abroad()->orderBy('promotion', 'desc')->pluck('cname', 'id'));
			$form->multipleSelect('cities', '目的地 城市')->options(Worldcity::worldcities()->orderBy('promotion', 'desc')->pluck('cn_name', 'id'));
		} elseif ($c_id == 3) {
			$form->select('departure', '出发城市')->options(Worldcity::chinacities()->departure()->pluck('cn_name','id'));
			$form->multipleSelect('countries', '目的地 地区')->options(Country::gangaotai()->pluck('cname', 'id'));
		} elseif ($c_id == 4) {
			$form->select('departure', '出发城市')->options(Worldcity::chinacities()->departure()->pluck('cn_name','id'));
			$form->multipleSelect('countries', '目的地 地区')->options(Country::island()->orderBy('promotion', 'desc')->pluck('cname', 'id'));
			$form->multipleSelect('cities', '目的地 城市')->options(Worldcity::island()->orderBy('promotion', 'desc')->pluck('cn_name', 'id'));
		} elseif ($c_id == 5) {
			//周边游
			// $form->select('departure', '出发城市')->options(function($id){
			// 	$city = Worldcity::china()->find($id);
			// 	if ($city) {
			// 		return [$city->id => $city->cn_name];
			// 	}
			// })->ajax('/api/v1/chinacities/ajax');
			$form->select('departure', '出发城市')->options(Worldcity::chinacities()->departure()->pluck('cn_name','id'))->load('cities','/api/v1/worldcities/aroundcities');
			$form->multipleSelect('cities','目的地')->rules('required');

			// $form->multipleSelect('cities', '目的地 城市')->options(function($id){
			// 	$city = Worldcity::china()->find($id);
			// 	if ($city) {
			// 		return [$city->id => $city->cn_name];
			// 	}
			// })->ajax('/api/v1/chinacities/ajax');
		} elseif ($c_id == 6) {
			$form->select('departure', '出发城市')->options(Worldcity::chinacities()->departure()->pluck('cn_name','id'))->load('cities','/api/v1/worldcities/aroundcities');
			$form->multipleSelect('cities', '目的地城市')->options(Worldcity::chinacities()->orderBy('promotion', 'desc')->pluck('cn_name', 'id'));
		} else {
			$form->multipleSelect('countries', '目的地 地区')->options(Country::abroad()->orderBy('promotion', 'desc')->pluck('cname', 'id'));
			// $form->multipleSelect('cities', '目的地 城市')->options(Worldcity::worldcities()->orderBy('promotion', 'desc')->pluck('cn_name', 'id'));
		}

		$form->number('day', '天数')->min(1)->default(1);
		$form->number('night', '晚数')->min(0)->default(0);
		// $form->multipleSelect('destination', '目的地')->options(function ($id) {
		//  return Destination::options($id);
		// })->help('没有需要的分类？前往<a href="/admin/categories/create">创建</a>');

		// $form->multipleSelect('city_id', '游览城市')->options(function ($id) {
		//  $city = Worldcity::find($id);
		//  if ($city) {
		//      return [$city->id => $city->cn_city];
		//  }
		// })->ajax('/api/v1/worldcities/all');

		// $form->multipleSelect('city', '地区')->options(function ($id) {
		//  $country = Country::find($id);
		//  if ($country) {
		//      return [$country->id => $country->cname];
		//  }
		// })->ajax('/api/v1/countries/ajax')->rules('required');

		// dd($c_id);

		////销售属性
		$form->embeds('attributes', '销售属性', function ($form) {
			$cates = Catattr::with('categories')->where('parent_id', 1)
				->whereHas('categories', function ($query) {
					$c_id = request()->get('c_id');
					$query->where('category_id', '=', $c_id);
				})->get();
			// dd($cates);
			foreach ($cates as $cate) {
				if ($cate->inputtype == 'checkbox') {

					$form->checkbox($cate->en_name, $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)
							->where('status', '1')
							->orderBy('order', 'asc')->pluck('attrvalue', 'id'));

				} elseif ($cate->inputtype == 'select') {
					if ($cate->en_name == 'departures') {
						$form->select($cate->en_name, $cate->name)->options(Worldcity::chinacities()->departure()->pluck('cn_name', 'id'));
					} elseif ($cate->en_name == 'countries') {

						$form->select($cate->en_name, $cate->name)->options(Country::abroad()->orderBy('promotion', 'desc')->pluck('cname', 'id'));
					} elseif ($cate->en_name == 'chinacities') {
						$form->select($cate->en_name, $cate->name)->options(Worldcity::chinacities()->orderBy('promotion', 'desc')->pluck('cn_name', 'id'));
					}
					 else {
						$form->select($cate->en_name, $cate->name)->options(Worldcity::worldcities()->orderBy('promotion', 'desc')->pluck('cn_name', 'id'));
					}

				} elseif ($cate->inputtype == 'radio') {
					$form->radio($cate->en_name, $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)->pluck('attrvalue', 'id'));
				} elseif ($cate->inputtype == 'text' && $cate->inputformat == 'number') {
					$min = $cate->extra[0]['value'];
					$max = $cate->extra[1]['value'];

					$form->number($cate->en_name, $cate->name)->min($min)->max($max)->default(1);
				} elseif ($cate->inputtype == 'text' && $cate->inputformat == 'date') {
					$form->date($cate->en_name, $cate->name);
				} else {
					$form->text($cate->en_name, $cate->name);
				}
			}
		});

		// $categories = Category::whereDoesntHave('childcategories')->pluck('name', 'id');
		// $form->select('category_id', '分类')->options($categories);
		// $form->select('category_id', '分类')->options('/api/v1/categories/all');
		// $form->image('avatar', '图片')->move('images')->fit(175, 256, function ($constraint) {
		//  // $constraint->aspectRatio();
		//  $constraint->upsize();
		// })->removable();

		// $form->multipleImage('pictureuri', '多图')->removable();
		// $form->text('graphs.description', '图片描述');

		// $manager = new ImageManager(array('driver' => 'imagick'));
		// $image = $manager->make('images/AAA170509322152256644.jpg')->resize(300, 200);

		//裁切175X256，添加文字HOT,添加右下角评分栏，移除
		// $text = 'HOT';
		// $form->image('avatar', '图片')->move('images')->fit(175, 256, function ($constraint) {
		//  // $constraint->aspectRatio();
		//  $constraint->upsize();
		// })->text($text, 12, 12, function ($font) {
		//  $font->file('font/Elephant.ttf');
		//  $font->size(10);
		//  $font->color('#fdf6e3');
		//  $font->align('center');
		//  $font->valign('middle');
		//  $font->angle(45);
		// })->rectangle(140, 240, 175, 256, function ($draw) {
		//  $draw->background('#00BB00');
		//  $draw->border(1, '#000');
		// })->removable();

		// $parents = Category::all()->pluck('name', 'id');
		// $form->select('category_id', '父类')->options($parents)->load('children', '/api/v1/categories/children');
		// $form->select('children', '分类');

		/////下拉菜单分组
		// $labs = Worldcity::with('country')->worldcities()->get();
		// // ,'country_id'
		// // dd($labs[0]->cn_name);
		// // ->groupBy('country_id')
		// $group = [
		// 	[
		// 		'label' => $labs[0]->country->cname,
		// 		'options' => [
		// 			1 => 'foo',
		// 			2 => 'bar',
		// 		],
		// 	],
		// 	// [
		// 	// 	'label' => 'aaaa',
		// 	// 	'options' => [
		// 	// 		3 => 'doo',
		// 	// 		4 => 'fffar',
		// 	// 		5 => 'doo',
		// 	// 		6 => 'fffar',
		// 	// 	],
		// 	// ],
		// ];
		// $form->select('country-city')->options()->groups($group);

		//选项过多的ajax 方法加载方法
		// $form->select('category_id', '父类')->options(function ($id) {
		//  $category = Category::find($id);
		//  if ($category) {
		//      return [$category->id => $category->name];
		//  }
		// })->ajax('/api/v1/categories/ajax');

		// $form->text('star', '评星')->attribute(['class' => 'rating', 'min' => 0, 'max' => 5, 'step' => 1, 'step' => 1, 'data-size' => 'sm', 'value=' => 2]);
		$form->starRating('star', '推荐指数')->default(4);
		// $form->slider('star', '评星')->options(['max' => 5, 'min' => 1, 'step' => 0.5, 'postfix' => '星级']);
		$form->text('summary', '概述');
		// $form->file('route', '上传行程');
		$form->editor('content', '商品详情');
		$form->switch('active', '激活？');
		$form->embeds('properties', '价格属性', function ($form) {
				$cates = Catattr::with('categories')->where('parent_id', 2)
					->whereHas('categories', function ($query) {
						$c_id = request()->get('c_id');
						$query->where('category_id', '=', $c_id);
					})->get();
				foreach ($cates as $cate) {
					if ($cate->inputtype == 'checkbox') {

						$form->checkbox($cate->en_name, $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)
								->where('status', '1')
								->orderBy('order', 'asc')->pluck('attrvalue', 'id'));
					} elseif ($cate->inputtype == 'select') {
						$form->select($cate->en_name, $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)->pluck('attrvalue', 'id'));
					} elseif ($cate->inputtype == 'radio') {
						$form->radio($cate->en_name, $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)->pluck('attrvalue', 'id'));
					} else {
						$form->text($cate->en_name, $cate->name);
					}
				}
			});

		$form->hasMany('prices', '价格', function (Form\NestedForm $form) {
			$form->text('name', '套餐名|属性名...');
			$form->select('departure_id', '出发地')->options(Worldcity::chinacities()->departure()->pluck('cn_name', 'id'));

			$form->number('day', '天数')->min(1)->max(90)->default(1);
			// $form->number('night', '晚数')->min(0);
			$form->currency('price', '成人价格')->symbol('￥');
			$form->currency('childprice', '儿童价格')->symbol('￥');
			$form->select('chutuan', '发团方式')->options([1=>'天天', 2=>'定期', 3=>'每周' ])->default(1);
			$form->dateRange('start_date', 'end_date', '有效期');

			$form->date('schedule', '团期');
			$form->text('quantity', '数量');
			$form->text('remark', '说明');
			
		});

		return $form;
	}
}
