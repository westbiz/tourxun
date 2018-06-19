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
			$grid->category_id('分类');
			$grid->day('天数');
			$grid->night('晚数');
			$grid->hotel('酒店星级');
			$grid->comment_id('评论');
			$grid->price_id('价格id');
			$grid->star('评星');
			$grid->summary('概述');
			$grid->content('正文');
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
			// $parents = Category::all()->pluck('name', 'id');
			$group = [];
			$parents = Category::with('childcategory')->get();
			// $group = [];
			foreach ($parents as $category) {
				if ($category->parent_id == 0) {
					$group[]['label'] = $category->name;
				}

				// if ($category->parent_id == 1) {
				foreach ($category as $key => $value) {
					$group[$key]['options'] = $category->name;

					// }
				}

			}

			dd($group);
			$form->select('category_id', '父类')->options($parents)->load('children', '/api/v1/categories/children');
			$form->select('children', '分类');

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

			// $form->select('category_id', '父类')->options(function ($id) {
			// 	$category = Category::find($id);
			// 	if ($category) {
			// 		return [$category->id => $category->name];
			// 	}
			// })->ajax('/api/v1/categories/ajax');

			// $form->select('category_id', '分类')->options('/api/v1/categories/list');

			$form->number('day', '天数')->min(1)->max(90)->default(1);
			$form->number('night', '晚数')->min(0);
			$form->number('hotel', '酒店星级')->min(3)->max(5)->default(3);
			$form->text('comment_id', '评论');
			$form->text('price_id', '价格id');
			// $form->text('star', '评星')->attribute(['class' => 'rating', 'min' => 0, 'max' => 5, 'step' => 1, 'step' => 1, 'data-size' => 'sm', 'value=' => 2]);
			$form->slider('star', '评星')->options(['max' => 5, 'min' => 1, 'step' => 0.5, 'postfix' => '星级']);
			$form->text('summary', '概述');
			$form->editor('content', '正文');
			$form->switch('active', '激活？');
			$form->display('created_at', 'Created At');
			$form->display('updated_at', 'Updated At');
		});
	}
	public function categories(Request $request) {
		$q = $request->get('q');
		return Category::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
	}
}