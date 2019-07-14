<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CountryController;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Models\Worldcity;
use Illuminate\Http\Request;

class CountryController extends Controller {
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
	public function countryajax(Request $request) {
		$q = $request->get('q');
		return Country::where('cname', 'like', "%$q%")
			->orWhere('name', 'like', "%$q%")
			->orWhere('country_code', 'like', "%$q%")
		// ->orWhere('cn_state', 'like', "%$q%")
			->paginate(null, ['id', 'cname as text']);
	}


	public function getcitiesbycountry(Request $request) {
		$q = $request->get('q');
		return Worldcity::where('country_id', $q)
			->get(['id', 'cn_name as text']);
	}

	public function getcities(Request $request) {

		return CountryResource::collection(Country::all());
	}

}
