<?php

namespace App\Http\Resources;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource {
	// use CategoryCollection;
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		return [
			'id' => $this->id,
			'label' => $this->name, //用于select的options
			'parent_id' => $this->parent_id,
			// 'options' => new CategoryCollection($this->childcategory),
			'children' => CategoryResource::collection($this->childcategories),
			// 'level' => $this->level,
			// 'description' => $this->description,
			// 'created_at' => $this->created_at,
			// 'updated_at' => $this->updated_at,
			// 'deleted_at' => $this->deleted_at,
		];
		//return parent::toArray($request);
	}

	//with方法，可以获取数据库记录以外的其他相关数据
	public function with($request) {
		return [
			'link' => [
				'self' => url('/api/v1/categories/' . $this->id),
			],
		];
	}
}
