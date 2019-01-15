<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Controller;
// use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	//返回不含有子类的子类
	public function index() {

		return Category::whereDoesntHave('childcategories')->get(['id', 'name as text']);
		// return new CategoryCollection(Category::paginate());
	}

	// whereDoesntHave('childcategories', function ($query) {
	// 		$query->where('parent_id', '>', 0);
	// 	})->

	//返回所有父子层级资源
	public function list() {
		//
		return CategoryResource::collection(Category::all());
		// return new CategoryCollection(Category::paginate());
	}

	//返回所有父子层级，可用于select分组group options
	public function groups() {
		//
		// return Category::all(['id', 'name as text']);
		// $parents = Category::where('parent_id', 0)->get();
		// $labels = Category::where('parent_id', 0)->get([DB::RAW('name as label')]);

		// foreach ($parents as $parent) {
		// 	return ['label' => $parent->name, 'options' => [$parent->id => $parent->name]];
		// }
		// $parents = Category::with('childCategory')->get(['id', DB::RAW('name as text')]);
		$parents = Category::where('parent_id', '>', 0)->with('childcategories:id,name as text,parent_id')->get(['id', DB::RAW('name as label')]);
		return $parents;

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$category = Categroy::create($request->all());
		return response()->json($category, 201);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Category $category) {
		CategoryResource::withoutWrapping();
		return new CategoryResource($category);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Category $category) {
		$category->update($request->all());

		return response()->json($category, 200);
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

	//用于ajax的select联动api的子级返回选项
	public function children(Request $request) {
		$categoryId = $request->get('q');
		return Destination::where('id', $categoryId)->get(['id', DB::RAW('name as text')]);
	}

	//选项过多，可通过ajax方式动态分页载入选项
	public function categoryajax(Request $request) {
		$q = $request->get('q');
		return Category::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
	}

}
