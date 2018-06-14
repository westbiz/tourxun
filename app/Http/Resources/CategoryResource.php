<?php

namespace App\Http\Resources;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource {
	use CategoryCollection;
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		return [
			'id' => $this->id,
			'text' => $this->name, //用于select的options
			'parents' => Category::collection($this->whenLoaded('parentcategory')),
			'level' => $this->level,
			'description' => $this->descritpion,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'deleted_at' => $this->deleted_at,
		];
		//return parent::toArray($request);
	}
}
