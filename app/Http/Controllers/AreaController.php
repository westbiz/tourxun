<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AreaController;
use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}


	public function district(Request $request) {
		$cityid = $request->get('q');
		return Area::where('parent_id', $cityid)->get(['id', DB::RAW('areaName as text')]);
	}	


	public function city(Request $request) {
		$provinceid = $request->get('q');
		return Area::where('parent_id', $provinceid)->get(['id', DB::RAW('areaName as text')]);
	}


	public function province() {
		return Area::where('parent_id', -1)->get(['id', DB::RAW('areaName as text')]);
	}
}
