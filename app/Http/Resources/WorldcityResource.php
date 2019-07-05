<?php

namespace App\Http\Resources;

use App\Models\Country;
use Illuminate\Http\Resources\Json\JsonResource;

class WorldcityResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		// return parent::toArray($request);
		return [
			'id' => $this->id,
			'label' => $this->name,
			'cn_city' => $this->cn_city,
			'country' => Country::collection($this->cname),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}

	//with方法，可以获取数据库记录以外的其他相关数据
	public function with($request) {
		return [
			'link' => [
				'self' => url('api/v1/worldcities/ajax' . $this->id),
			],
		];
	}

}
