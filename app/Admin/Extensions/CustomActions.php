<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Displayers\Actions;

class CustomActions extends Actions {
	// 重写renderEdit方法加上`编辑`两个字
	protected function renderEdit() {
		return <<<EOT
<a href="{$this->getResource()}/{$this->getKey()}/edit?c_id={$this->row->category_id}">
    <i class="fa fa-edit"></i> 编辑
</a>
EOT;
	}
}