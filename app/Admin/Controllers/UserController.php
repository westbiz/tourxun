<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserController extends Controller {
	use HasResourceActions;

	/**
	 * Index interface.
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function index(Content $content) {
		return $content
			->header('Index')
			->description('description')
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
		$grid = new Grid(new User);

		$grid->id('Id');
		$grid->avatar('头像')->lightbox(['http://tourxun.test/uploads/', 'width' => 50, 'height' => 50, 'zooming' => true]);
		$grid->name('用户名');
		$grid->profile()->nickname('昵称');
		$grid->email('Email');
		$grid->profile()->cityid('城市');
		$grid->profile()->age('年龄');
		$grid->profile()->gender('性别');


		// $grid->password('Password');
		// $grid->remember_token('Remember token');
		// $grid->created_at('创建时间');
		// $grid->updated_at('更新时间');

		return $grid;
	}

	/**
	 * Make a show builder.
	 *
	 * @param mixed $id
	 * @return Show
	 */
	protected function detail($id) {
		$show = new Show(User::findOrFail($id));

		$show->id('Id');
		$show->name('用户名');
		$show->email('Email');
		$show->avatar('头像')->image();
		// $show->password('Password');
		// $show->remember_token('Remember token');
		$show->created_at('创建时间');
		$show->updated_at('更新时间');

		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		$form = new Form(new User);

		$form->text('name', '用户名');
		// $form->image('avatar', '头像')
		$form->cropper('avatar', '头像')
		//强制剪裁尺寸，请使用（注意该尺寸就是最后得到的图片尺寸 非“比例”）
		//->cRatio($width,$height)
			->cRatio(180, 180)
		//自定义存储路径
			->move('images/users/avatars');

		$form->email('email', 'Email');
		$form->text('profile.age', '年龄');
		$form->date('profile.birthdate', '出生日期');
		$form->password('password', '密码')->rules('required|confirmed');
		$form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
			->default(function ($form) {
				return $form->model()->password;
			});
		$form->ignore(['password_confirmation']);
		// $form->text('remember_token', 'Remember token');

		$form->saving(function (Form $form) {
			if ($form->password && $form->model()->password != $form->password) {
				$form->password = bcrypt($form->password);
			}
		});

		return $form;
	}
}
