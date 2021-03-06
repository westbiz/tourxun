<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WorldcityController;
use App\Models\Worldcity;
use App\Http\Resources\WorldcityResource;
// use App\Http\Controllers\Worldcity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorldcityController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
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

	//选项过多，可通过ajax方式动态分页载入选项
	public function getcities(Request $request) {
		$q = $request->get('q');
		return Worldcity::where('country_id', $q)->get(['id', DB::raw('cn_name as text')]);
	}

	//选项过多，可通过ajax方式动态分页载入选项
	public function allcities(Request $request) {
		$q = $request->get('q');
		// return Worldcity::chinacities()
		return Worldcity::where('cn_name', 'like', "%$q%")
			->orWhere('name', 'like', "%$q%")
			->orWhere('city_code', 'like', "%$q%")
			->orWhere('cn_state', 'like', "%$q%")
			->paginate(null, ['id', 'cn_name as text']);
	}

	//选项过多，可通过ajax方式动态分页载入选项
	public function chinacities(Request $request) {
		$q = $request->get('q');
		// return Worldcity::chinacities()
		return Worldcity::
			where('country_id',101)->where('cn_name', 'like', "%$q%")
			->orWhere('country_id',101)->where('cn_state', 'like', "%$q%")
			->paginate(null, ['id', 'cn_name as text']);

			
		// ->orWhere('name', 'like', "%$q%")
		// ->orWhere('city_code', 'like', "%$q%")
		//可搜索省名
		
			
	}

	//选项过多，可通过ajax方式动态分页载入选项
	public function worldcities(Request $request) {
		$q = $request->get('q');
		return Worldcity::worldcities()
			->where('cn_name', 'like', "%$q%")
		->orWhere('state_code', 'like', "%$q%")
		->orWhere('city_code', 'like', "%$q%")
		->orWhere('cn_state', 'like', "%$q%")
			->paginate(null, ['id', 'cn_name as text']);
	}

	public function aroundcities(Request $request) {
		$q = $request->get('q');
		$prv = Worldcity::where('id',$q)->pluck('cn_state');
		return Worldcity::where('cn_state', $prv)->get(['id', DB::raw('cn_name as text')]);
	}

	public function getprovince(Request $request) {
		$q = $request->get('q');
		
		return Worldcity::chinacities()->get(['id', DB::raw('cn_name as text'),DB::raw('cn_state')])->groupBy('cn_state')->all();
	}

	public function getcitieswithcountry(Request $request) {
		$q = $request->get('q');
		return WorldcityResource::collection(Worldcity::all());
	}

}
