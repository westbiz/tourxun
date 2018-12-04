<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Attrvalue;
use App\Models\Catattr;
use App\Models\Category;
use App\Models\Product;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

require '../vendor/autoload.php';

class ProductController extends Controller {
	use ModelForm;
	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {
			$content->header('产品');
			$content->description('列表');
			$content->body($this->grid());
		});
	}

	public function show($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('景点 ');
			$content->description('查看');

			$content->body(Admin::show(Product::findOrFail($id), function (Show $show) {

				$show->name('名称');
				$show->avatar('图片')->unescape()->as(function ($avatar) {
					return "<img src='http://tourxun.test/uploads/{$avatar}'>";
				});
				$show->summary('概况');
				// $show->content('内容')->as(function ($content) {
				// 	return "<pre>{$content}</pre>";
				// });
				$show->prices('价格', function ($price) {
					$price->id('ID');
					$price->schedule('出发日期');
					$price->quantity('数量');
					$price->price('价格');
					$price->remark('备注');
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
			$content->header('产品');
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
			$content->header('产品');
			$content->description('新增');
			$content->body($this->form());
		});
	}
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Product::class, function (Grid $grid) {

			$grid->id('ID')->sortable();
			$grid->avatar('图片')->display(function ($avatar) {
				return "<img src='http://tourxun.test/uploads/$avatar' height='10%' width='20%' class='img img-thumbnail'>";
			});
			$grid->name('名称')->editable();

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
			})->style('max-width:300px;word-break:break-all;');;
			$grid->summary('概述');
			// $grid->content('正文')->limit(30);
			// $grid->active('激活');

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
		return Admin::form(Product::class, function (Form $form) {
			$form->display('id', 'ID');
			$form->text('name', '名称')->rules('required|min:3');

			$form->select('start', '出发地')->options(Area::where('active', 1)->pluck('areaName', 'id'))->default('2809');
			$form->select('destination', '目的地')->options(Area::all()->pluck('areaName', 'id'))->default('2809');
			// $categories = Category::whereDoesntHave('childcategories')->pluck('name', 'id');
			// $form->select('category_id', '分类')->options($categories);
			// $form->select('category_id', '分类')->options('/api/v1/categories/all');
			$form->image('avatar', '图片')->move('images')->fit(175, 256, function ($constraint) {
				// $constraint->aspectRatio();
				$constraint->upsize();
			})->removable();

			$form->multipleImage('pictureuri', '多图')->removable();
			$form->text('graphs.description', '图片描述');

			// $manager = new ImageManager(array('driver' => 'imagick'));
			// $image = $manager->make('images/AAA170509322152256644.jpg')->resize(300, 200);

			//裁切175X256，添加文字HOT,添加右下角评分栏，移除
			// $text = 'HOT';
			// $form->image('avatar', '图片')->move('images')->fit(175, 256, function ($constraint) {
			// 	// $constraint->aspectRatio();
			// 	$constraint->upsize();
			// })->text($text, 12, 12, function ($font) {
			// 	$font->file('font/Elephant.ttf');
			// 	$font->size(10);
			// 	$font->color('#fdf6e3');
			// 	$font->align('center');
			// 	$font->valign('middle');
			// 	$font->angle(45);
			// })->rectangle(140, 240, 175, 256, function ($draw) {
			// 	$draw->background('#00BB00');
			// 	$draw->border(1, '#000');
			// })->removable();

			// $parents = Category::all()->pluck('name', 'id');
			// $form->select('category_id', '父类')->options($parents)->load('children', '/api/v1/categories/children');
			// $form->select('children', '分类');

			// $group = [
			// 	[
			// 		'label' => 'xxxx',
			// 		'options' => [
			// 			1 => 'foo',
			// 			2 => 'bar',
			// 		],
			// 	],

			// 	[
			// 		'label' => 'aaaa',
			// 		'options' => [
			// 			3 => 'doo',
			// 			4 => 'fffar',
			// 			5 => 'doo',
			// 			6 => 'fffar',
			// 		],
			// 	],
			// ];

			// $form->select('category_id')->options()->groups($group);

			//选项过多的ajax 方法加载方法
			// $form->select('category_id', '父类')->options(function ($id) {
			// 	$category = Category::find($id);
			// 	if ($category) {
			// 		return [$category->id => $category->name];
			// 	}
			// })->ajax('/api/v1/categories/ajax');

			$form->number('day', '天数')->min(1)->max(90)->default(1);
			$form->number('night', '晚数')->min(0);

			// $form->text('star', '评星')->attribute(['class' => 'rating', 'min' => 0, 'max' => 5, 'step' => 1, 'step' => 1, 'data-size' => 'sm', 'value=' => 2]);
			$form->starRating('star', '评星')->default(4);
			// $form->slider('star', '评星')->options(['max' => 5, 'min' => 1, 'step' => 0.5, 'postfix' => '星级']);
			$form->text('summary', '概述');
			$form->editor('content', '商品详情');
			$form->switch('active', '激活？');
			// $form->text('prices.price', '价格');
			// $form->date('prices.date', '日期');

			$cates = Catattr::where('parent_id', 1)->orderBy('order', 'desc')->get();
			// dd($cates->inputtype);
			foreach ($cates as $cate) {
				if ($cate->inputtype == 'checkbox') {
					$form->checkbox('catavalues', $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)->where('status', '1')->orderBy('order', 'asc')->pluck('attrvalue', 'id'));
				} elseif ($cate->inputtype == 'select') {
					$form->select('catavalues', $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)->pluck('attrvalue', 'id'));
				} elseif ($cate->inputtype == 'radio') {
					$form->radio('catavalues', $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)->pluck('attrvalue', 'id'));
				} else {
					$form->text('catavalues.attrvalue', $cate->name);
				}

			}

			$form->hasMany('prices', '价格', function (Form\NestedForm $form) {
				$form->text('taocan', '套餐名');
				$form->select('start', '出发地')->options(Area::where('active', 1)->pluck('areaName', 'id'))->default('2809');
				$form->text('dengji', '等级');
				$form->text('renqun', '成人|儿童|老年');
				$form->currency('price', '价格')->symbol('￥');
				$form->date('schedule', '出发日期');
				$form->text('quantity', '数量');
				$form->text('remark', '说明');
			});

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');

		});
	}

}