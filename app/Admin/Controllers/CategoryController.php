<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller {
	use HasResourceActions;

	/**
	 * Index interface.
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function index(Content $content) {
		return $content
			->header('分类管理')
			->description('列表')
			->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类列表']
			)
			->body($this->rootlist());
	}

	public function lines(Content $content) {
		return $content
			->header('分类管理')
			->description('列表')
			->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类列表']
			)
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
			->header('分类')
			->description('详情')
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

			->header('分类管理')
			->description('编辑')
			->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类管理', 'url' => '/categories'],
				['text' => '编辑分类']
			)
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
			->header('分类')
			->description('新增')
			->breadcrumb(
				['text' => '首页', 'url' => '/'],
				['text' => '分类管理', 'url' => '/categories'],
				['text' => '新增分类']
			)
			->body($this->form());
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		$grid = new Grid(new Category);

		// $grid->filter(function ($filter) {
		// 	$filter->disableIdFilter();
		// 	$categories = Category::where('parent_id', 1)->pluck('name', 'id');
		// 	$filter->expand()->equal('parent_id', '选择分类')->select($categories);
		// });

		$grid->model()->where('parent_id', '>', '0');
		// $grid->model()->with('attrvalues');
		$grid->actions(function ($actions) {
			$c_id = $actions->getKey();
			$actions->prepend("<a href='categories/create?parent_id=" . $c_id . "' title='添加子类'><i class='fa fa-plus-square'></i></a>&nbsp;");
		});
		$grid->id('ID')->sortable();
		$grid->name('名称')->editable();
		$grid->parentcategory('父类')->display(function ($parentcategory) {
			return "<span class='label label-info'>{$parentcategory['name']}</span>";
		});
		$grid->childcategories('线路')->display(function ($categories) {
			$categories = array_map(function ($category) {
				return "<a href='categories?{$category['id']}'><span class='label label-danger'>{$category['name']}</span></a>";
			}, $categories);
			return join('&nbsp;', $categories);
		})->style('max-width:200px;line-height:1.5em;word-break:break-all;');

		// $grid->description('说明')->limit(60)->editable();

		// $grid->column('expand')->expand(function () {
		//           if (empty($this->sights)) {
		//               return '';
		//           }
		//           $sights = array_only($this->sights->toArray(), ['name', 'avatar']);
		//           return new Table([], $sights);
		//       }, 'Sights');

		$grid->parent_id('父类');
		$states = [
			'on' => ['value' => 1, 'text' => '是', 'color' => 'primary'],
			'off' => ['value' => 2, 'text' => '否', 'color' => 'default'],
		];
		$grid->promotion('推荐')->switch($states);
		// $grid->childcategories('子类')->count()->label('danger');
		$grid->order('排序')->editable();

		//自定义action
		// $grid->actions(function ($actions) {
		//  $cate_id = $actions->getKey();

		//  $actions->append('<a href="/admin/categories/create?cate=' . $cate_id . '"><i class="fa fa-plus-square" aria-hidden="true"></i>');

		//  $actions->prepend('<a href=""><i class="fa fa-paper-plane"></i></a>');

		// });

		// $grid->deleted_at();
		// $grid->created_at();
		// $grid->updated_at();

		return $grid;
	}

	//rootcategories
	protected function rootlist() {
		$grid = new Grid(new Category);

		// $grid->filter(function ($filter) {
		// 	$filter->disableIdFilter();
		// 	$categories = Category::where('parent_id', 1)->pluck('name', 'id');
		// 	$filter->expand()->equal('parent_id', '选择分类')->select($categories);
		// });

		$grid->model()->where('parent_id', '0')
			->orWhere('parent_id', null);
		// $grid->model()->with('attrvalues');
		$grid->actions(function ($actions) {
			$c_id = $actions->getKey();
			$actions->prepend("<a href='categories/create?parent_id=" . $c_id . "' title='添加子类'><i class='fa fa-plus-square'></i></a>&nbsp;");
		});
		$grid->id('ID')->sortable();
		$grid->name('名称')->editable();
		$grid->parentcategory('父类')->display(function ($parentcategory) {
			return "<span class='label label-info'>{$parentcategory['name']}</span>";
		});
		$grid->childcategories('目的地')->display(function ($categories) {
			$categories = array_map(function ($category) {
				return "<a href='products/create?category={$category['id']}'><span class='label label-danger'>{$category['name']}</span></a>";
			}, $categories);
			return join('&nbsp;', $categories);
		})->style('max-width:200px;line-height:1.5em;word-break:break-all;');

		// $grid->childcategories('子类')->count()->label('danger');
		$grid->order('排序')->editable();

		// $grid->deleted_at();
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
		$show = new Show(Category::findOrFail($id));

		$show->panel()->title('概览');
		$show->name('名称');
		// $show->countries()->cname();
		// $show->order('排序');
		// $show->description('描述');

		$show->childcategories('所有子类', function ($child) {
			$child->resource('/admin/categories');
			$child->id('ID');
			$child->name('名称')->editable();
			$child->description('描述')->limit(50)->editable();
			$child->order('排序');
			$child->parentcategory()->name('类目');
			$parent = request()->route()->parameters('categories');
			// dd($parent['category']);
			if ($parent['category'] > 1) {
				$states = [
					'on' => ['value' => 1, 'text' => '是', 'color' => 'primary'],
					'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
				];
				$child->promotion('推荐')->switch($states);
			}

			// $child->promotion('推荐')->using(['0' => '否', '1' => '是']);
			$child->filter(function ($filter) {
				$filter->disableIdFilter();
				$filter->like('name');
			});
		});

		// $show->attrvalues('属性', function ($attrvalues) {
		//  $attrvalues->resource('/admin/attrvalues');
		//  $attrvalues->catattr()->name();
		//  $attrvalues->attrvalue();
		// });

		// $show->sights('景区', function ($sight) {
		//  $sight->resource('/admin/sights');
		//  $sight->id();
		//  $sight->name();
		//  $sight->avatar()->lightbox();
		//  $sight->rate();
		// });

		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		$form = new Form(new Category);

		$form->tab('基本信息', function ($form) {
			$p_id = request()->get('parent_id');
			$form->display('id', 'ID');

			$form->text('name', '分类名称')->rules('required|min:2|max:20')->help('请输入2-20个字符！');
			$form->select('parent_id', '父类')->options(Category::pluck('name', 'id'))->default($p_id);
			$form->multipleSelect('countries', '目的地')->options(Country::pluck('cname', 'id'));
			$next_id = DB::select("SHOW TABLE STATUS LIKE 'tx_categories'");
			$form->text('order', '排序')->value($next_id[0]->Auto_increment);
			$states = [
				'on' => ['value' => 1, 'text' => '打开', 'color' => 'success'],
				'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
			];
			$form->switch('promotion', '推荐')->states($states);
			$form->textarea('description', '说明')->help('请输入2-50个字符！');
		})->tab('子分类', function ($form) {
			$form->hasMany('childcategories', '子分类', function (Form\NestedForm $form) {
				$form->text('name');
				$form->text('order');
				$form->textarea('description');
			});

		});
		// ->tab('属性', function ($form) {
		//  $form->hasMany('catattrs', '属性', function (Form\NestedForm $form) {
		//      $form->text('name');
		//      $form->text('note');
		//      $form->text('parent_id');
		//      $form->text('fieldname');
		//      $form->text('inputtype');
		//  });
		// });

		// $form->display('created_at', 'Created At');
		// $form->display('updated_at', 'Updated At');

		return $form;
	}
}
