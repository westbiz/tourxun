<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

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
			$grid->name('名称');
			// $grid->avatar('图片');

			$grid->avatar('图片')->display(function ($avatar) {
				return "<img src='http://tourxun.test/uploads/$avatar' alt='$this->name' height='10%' width='10%' class='img img-thumbnail'>";
			});
			$grid->graphs()->imageurl()->image('http://tourxun.test/uploads/', 50, 50);
			$grid->category('分类')->name();
			$grid->day('天数');
			$grid->night('晚数');
			$grid->hotel('酒店星级');
			$grid->star('评星');
			$grid->prices('价格');
			$grid->summary('概述');
			$grid->content('正文')->limit(30);
			$grid->active('激活');

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

			$form->image('avatar', '图片')->move('images')->fit(175, 256, function ($constraint) {
				// $constraint->aspectRatio();
				$constraint->upsize();
			})->removable();

			$form->multipleImage('graphs.imageurl', '多图')->removable();
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

			$categories = Category::whereDoesntHave('childcategories')->pluck('name', 'id');
			$form->select('category_id', '分类')->options($categories);
			// $form->select('category_id', '分类')->options('/api/v1/categories/all');

			$form->number('day', '天数')->min(1)->max(90)->default(1);
			$form->number('night', '晚数')->min(0);
			$form->number('hotel', '酒店星级')->min(3)->max(5)->default(3);

			// $form->text('star', '评星')->attribute(['class' => 'rating', 'min' => 0, 'max' => 5, 'step' => 1, 'step' => 1, 'data-size' => 'sm', 'value=' => 2]);
			$form->slider('star', '评星')->options(['max' => 5, 'min' => 1, 'step' => 0.5, 'postfix' => '星级']);
			$form->text('summary', '概述');
			$form->editor('content', '正文');
			$form->switch('active', '激活？');
			// $form->text('prices.price', '价格');
			// $form->date('prices.date', '日期');

			$form->hasMany('prices', function (Form\NestedForm $form) {
				$form->currency('price', '价格')->symbol('￥');
				$form->datetime('date', '日期');
				$form->text('remark', '说明');
			});

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');

		});
	}

}