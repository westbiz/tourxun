<?php

use App\Admin\Extensions\Popover;
use App\Admin\Extensions\WangEditor;
use Encore\Admin\Form;
use Encore\Admin\Grid\Column;

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Admin::js('/vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js');
Admin::js('/vendor/laravel-admin/AdminLTE/plugins/select2/i18n/zh-CN.js');

Encore\Admin\Form::forget(['map', 'editor']);

Column::extend('color', function ($value, $color) {
	return "<span style='color:$color'>{$value}</span>";
});

Form::extend('editor', WangEditor::class);

Column::extend('popover', Popover::class);