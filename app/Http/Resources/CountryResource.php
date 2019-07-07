<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		// return parent::toArray($request);

		return [
			// 'id' => $this->id,
			// 'name' => $this->name,
			// 'country_code' => $this->country_code,
			'label' => $this->cname,
			'options' => WorldcityResource::collection($this->cities),
		];
	}
}
