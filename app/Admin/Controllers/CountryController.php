<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Continent;
use App\Models\Country;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CountryController extends Controller {
	use HasResourceActions;

	public function assign(Request $request) {
		foreach (Country::find($request->get('ids')) as $assign) {
			$assign->released = $request->get('action');
			$assign->save();
		}
	}

	/**
	 * Index interface.
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function index(Content $content) {
		return $content
			->header('国家|地区管理')
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
			->header('Detail')
			->description('description')
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
			->header('Edit')
			->description('description')
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
			->header('Create')
			->description('description')
			->body($this->form());
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		$grid = new Grid(new Country);

		$grid->tools(function ($tools) {
			$tools->batch(function ($batch) {
				// $batch->disableDelete();
				// $batch->add('归类到', new AssignCountry(1));
				// $batch->

			});
		});

		$grid->filter(function ($filter) {
			$filter->disableIdFilter();

			// 	$filter->expand()->where(function ($query) {

			// 		$query->whereHas('continent', function ($query) {
			// 			$query->where('cn_name', 'like', "%{$this->input}%");
			// 		});
			// 	}, '洲名');
			$filter->column(3 / 4, function ($filter) {
				$continents = Continent::where('parent_id', '0')->pluck('cn_name', 'id');
				$filter->expand()->equal('continent_id', '大洲')->select($continents);
				$filter->expand()->where(function ($query) {
					$query->where('cname', 'like', "%{$this->input}%")
						->orWhere('full_cname', 'like', "%{$this->input}%")
						->orWhere('name', 'like', "%{$this->input}%")
						->orWhere('country_code', 'like', "%{$this->input}%");
					// $query->whereHas('country', function ($query){
					// 	$query->where('cname', 'like', "%{$this->input}%");
					// });
				}, '关键字');
			});

			// $filter->like('cname', '名称');
		});
		$grid->id('ID');
		$grid->cname('中文')->display(function () {
			// $id = $actions->getKey();
			return "<a href='worldcities?&country_id=" . $this->id . "'>" . $this->cname . "</a>";
		});
		// $grid->alias('别名');
		$grid->continent()->cn_name('大洲')->label('info');
		$grid->continentlocation('地理位置')->pluck('cn_name')->label('danger');
		// $grid->categorycountry('归类')->pluck('name')->label();
		$grid->name('英文')->limit(15);
		// $grid->lower_name('en小写');
		$grid->country_code('代码');
		// $grid->full_name('en全称');
		$grid->full_cname('全称')->limit(15);
		$grid->remark('简介')->limit(25);
		$states = [
			'on' => ['value' => 1, 'text' => '是', 'color' => 'primary'],
			'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
		];
		$grid->is_island('海岛')->switch($states);
		$grid->active('激活')->switch($states);
		$grid->promotion('推荐')->switch($states);
		// $grid->created_at('Created at');
		// $grid->updated_at('Updated at');

		return $grid;
	}

	/**
	 * Make a show builder.
	 *
	 * @param mixed $id
	 * @return Show
	 */
	protected function detail($id) {
		$show = new Show(Country::findOrFail($id));

		$show->id('ID');
		$show->cname('中文名称');
		$show->continent_id('大洲');
		$show->name('英文名称');
		$show->lower_name('en小写');
		$show->country_code('代码');
		$show->full_name('英文全称');
		$show->full_cname('中文全称');
		$show->remark('简介')->limit(30);

		$show->cities('城市', function ($cities) {
			$cities->resource('/admin/worldcities');
			$cities->id('ID');
			$cities->cn_name('名称');
			$cities->name('EN名称');
			$cities->country()->cname('国家')->label('info');
			$cities->cn_state('省/州');
			$cities->state('EN省/州');
			$cities->lower_name('en小写');
		});
		// $show->created_at('Created at');
		// $show->updated_at('Updated at');

		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		$form = new Form(new Country);

		$form->display('ID');
		$form->text('cname', '中文名称');
		// $form->text('alias','别名');
		$continents = Continent::pluck('cn_name', 'id');
		$form->select('continent_id', '大洲')->options($continents);
		$form->multipleSelect('continentlocation', '地理位置')->options(Continent::where('parent_id', '>', '0')->pluck('cn_name', 'id'));
		// $form->multipleSelect('categorycountry', '目的地归类')->options(Category::where('parent_id', 0)->pluck('name', 'id'));
		$form->text('name', 'EN名称');
		$form->text('lower_name', '小写');
		$form->text('country_code', '代码');
		$form->text('full_name', 'EN全称');
		$form->text('full_cname', '中文全称');
		$form->textarea('remark', '简介');
		$states = [
			'on' => ['value' => 1, 'text' => '打开', 'color' => 'success'],
			'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
		];
		$form->switch('active', '激活')->states($states)->default(0);
		$form->switch('is_island', '海岛')->states($states)->default(0);
		$form->switch('promotion', '推荐')->states($states)->default(0);
		// $form->display('Created at');
		// $form->display('Updated at');

		return $form;
	}
}
