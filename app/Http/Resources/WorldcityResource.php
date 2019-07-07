<?php

namespace App\Http\Resources;

use App\Models\Country;
use App\Http\Resources\CountryResource;
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
			// 'name' => $this->name,
			'cn_name' => $this->cn_name,
			// 'country_id'=>$this->country_id,
			// 'country_name' => CountryResource::collection($this->country_id),
			// 'created_at' => $this->created_at,
			// 'updated_at' => $this->updated_at,
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
