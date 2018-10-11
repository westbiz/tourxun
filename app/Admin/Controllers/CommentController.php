<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CommentController extends Controller {
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('评论');
			$content->description('详情');

			$content->body($this->grid());
		});
	}

	public function show($id) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('评论');
			$content->description('详情');

			$content->body(Admin::show(Comment::findOrFail($id), function (Show $show) {

				$show->id('ID');
				$show->content('内容');
				$show->pictureuri('图片');
				$show->release_at();
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

			$content->header('评论');
			$content->description('表单');

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

			$content->header('评论');
			$content->description('表单');

			$content->body($this->form());
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid(Comment::class, function (Grid $grid) {

			$grid->id('ID')->sortable();
			$grid->content('内容');
			$grid->pictureuri('图片');

			$grid->created_at();
			$grid->updated_at();
		});
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		return Admin::form(Comment::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('content');
			$form->text('pictureuri');

			// $form->display('created_at', 'Created At');
			// $form->display('updated_at', 'Updated At');
		});
	}
}
