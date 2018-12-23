<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('分类管理');
			$content->description('列表');
			$content->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类列表']
			);

			$content->body($this->grid());
		});
	}

	public function show($id) {
		return Admin::content(function (Content $content) use ($id) {
			$content->header('分类');
			$content->description('详情');

			$content->body(Admin::show(Category::findOrFail($id), function (Show $show) {
				// $show->id('ID');
				$show->panel()->title('概览');
				$show->name('名称');
				$show->order('排序');
				$show->description('描述');

				$show->childcategories('所有子类', function ($child) {
					$child->resource('/admin/categories');
					$child->id('ID');
					$child->name('名称')->editable();
					$child->description('描述')->editable();
					$child->order('排序');
					$child->filter(function ($filter) {
						$filter->like('name');
					});
				});

				// $show->attrvalues('属性', function ($attrvalues) {
				// 	$attrvalues->resource('/admin/attrvalues');
				// 	$attrvalues->catattr()->name();
				// 	$attrvalues->attrvalue();
				// });

				// $show->sights('景区', function ($sight) {
				// 	$sight->resource('/admin/sights');
				// 	$sight->id();
				// 	$sight->name();
				// 	$sight->avatar()->lightbox();
				// 	$sight->rate();
				// });

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

			$content->header('分类管理');
			$content->description('编辑');
			$content->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类管理', 'url' => '/categories'],
				['text' => '编辑分类']
			);
			// $content->name();

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

			$content->header('分类');
			$content->description('新增');
			$content->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类管理', 'url' => '/categories'],
				['text' => '新增分类']
			);

			$content->body($this->form());
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Category::class, function (Grid $grid) {

			// $grid->model()->with('sights');
			// $grid->model()->with('attrvalues');
			$grid->actions(function ($actions) {
				$c_id = $actions->getKey();
				$actions->prepend("<a href='categories/create?parent_id=" . $c_id . "' title='添加子类'><i class='fa fa-plus-square'></i></a>&nbsp;");
			});
			$grid->id('ID')->sortable();
			$grid->name('名称')->editable();
			$grid->parentcategory('归属父类')->display(function ($parentcategory) {
				return "<span class='label label-info'>{$parentcategory['name']}</span>";
			});
			$grid->description('说明')->limit(60)->editable();

			// $grid->column('expand')->expand(function () {
			//           if (empty($this->sights)) {
			//               return '';
			//           }
			//           $sights = array_only($this->sights->toArray(), ['name', 'avatar']);
			//           return new Table([], $sights);
			//       }, 'Sights');

			$grid->parent_id('父类');
			$grid->childcategories('子类')->count()->label('danger');
			$grid->order('排序')->editable();
			$grid->filter(function ($filter) {
				// 设置created_at字段的范围查询
				$filter->between('created_at', 'Created Time')->datetime();
				$filter->equal('name')->select('/api/v1/categories/all');
			});

			//自定义action
			// $grid->actions(function ($actions) {
			// 	$cate_id = $actions->getKey();

			// 	$actions->append('<a href="/admin/categories/create?cate=' . $cate_id . '"><i class="fa fa-plus-square" aria-hidden="true"></i>');

			// 	$actions->prepend('<a href=""><i class="fa fa-paper-plane"></i></a>');

			// });

			// $grid->deleted_at();
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
		return Admin::form(Category::class, function (Form $form) {

			$form->tab('基本信息', function ($form) {
				// $p_id = request()->get('parent_id');
				$form->display('id', 'ID');
				$form->select('parent_id', '父类')->options(Category::pluck('name', 'id'))->default(0);
				// $form->select('categoryecountry','国家地区')->options(Country::pluck('cname','id'));
				$form->text('name', '分类名称')->rules('required|min:2|max:20')->help('请输入2-20个字符！');
				$next_id = DB::select("SHOW TABLE STATUS LIKE 'tx_categories'");
				$form->text('order', '排序')->value($next_id[0]->Auto_increment);
				$form->textarea('description', '说明')->help('请输入2-50个字符！');
			})->tab('子分类', function ($form) {
				$form->hasMany('childcategories', '子分类', function (Form\NestedForm $form) {
					$form->text('name');
					$form->text('order');
					$form->textarea('description');
				});

			});
			// ->tab('属性', function ($form) {
			// 	$form->hasMany('catattrs', '属性', function (Form\NestedForm $form) {
			// 		$form->text('name');
			// 		$form->text('note');
			// 		$form->text('parent_id');
			// 		$form->text('fieldname');
			// 		$form->text('inputtype');
			// 	});
			// });

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}

}