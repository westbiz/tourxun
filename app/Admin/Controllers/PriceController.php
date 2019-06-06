<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attrvalue;
use App\Models\Catattr;
use App\Models\Price;
use App\Models\Worldcity;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class PriceController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('header');
			$content->description('description');
			$content->breadcrumb(
				['text' => '价格列表', 'url' => 'prices']
			);

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

			$content->header('header');
			$content->description('description');
			$content->breadcrumb(
				['text' => '价格列表', 'url' => 'prices'],
				['text' => '价格编辑']
			);

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

			$content->header('header');
			$content->description('description');

			$content->body($this->form());
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Price::class, function (Grid $grid) {

			// $grid->model()->with('product.category');
			$grid->actions(function ($actions) {
				$p_id = $actions->getKey();

				$c_id = $actions->row->product->category_id;
				// $d_id = request()->get('d_id');
				// $d_id = $actions->row->destinations()->pivot()->destination_id;
				// dd($actions->row->product->category_id);
				$actions->prepend("<a href='prices/" . $p_id . "/edit?c_id=" . $c_id . "' title='添加价格'><i class='fa fa-plus-square'></i></a>&nbsp;");
			});

			$grid->id('ID')->sortable();
			$grid->product()->avatar('图片')->display(function ($avatar) {
				return "<img src='http://tourxun.test/uploads/$avatar' alt='$this->name' height='10%' width='10%' class='img img-thumbnail'>";
			});
			$grid->column('类别')->display(function ($category) {
				return $this->product->category->name;
			});
			$grid->product()->name('商品名称');
			$grid->departure_id('出发地');
			$grid->price('价格');
			$grid->schedule('日期');
			$grid->remark('说明');

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
		return Admin::form(Price::class, function (Form $form) {

			$c_id = request()->get('c_id');
			// $form->display('id', 'ID');

			$form->text('name', '套餐名|属性名...');
			$form->select('departure_id', '出发地')->options(Worldcity::chinacities()->departure()->pluck('cn_name', 'id'));
			$form->image('product.avatar');
			$form->currency('price', '价格')->symbol('￥');
			$form->currency('childprice', '儿童价格')->symbol('￥');
			$form->date('schedule', '团期');
			$form->textarea('remark', '说明');
			$form->embeds('properties', '价格属性', function ($form) {
				$cates = Catattr::with('categories')->where('parent_id', 2)
					->whereHas('categories', function ($query) {
						$c_id = request()->get('c_id');
						$query->where('category_id', '=', $c_id);
					})->get();
				foreach ($cates as $cate) {
					if ($cate->inputtype == 'checkbox') {

						$form->checkbox($cate->description, $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)
								->where('status', '1')
								->orderBy('order', 'asc')->pluck('attrvalue', 'id'));
					} elseif ($cate->inputtype == 'select') {
						$form->select($cate->description, $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)->pluck('attrvalue', 'id'));
					} elseif ($cate->inputtype == 'radio') {
						$form->radio($cate->description, $cate->name)->options(Attrvalue::where('catattr_id', $cate->id)->pluck('attrvalue', 'id'));
					} else {
						$form->text($cate->description, $cate->name);
					}
				}
			});

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}
}
