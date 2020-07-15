<?php

namespace App\Http\Controllers;

use App\Category;
use http\Env\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $validator = Validator::make($request->all(),[
                'name'=>'required|unique:categories'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Duplicated category']);
            }

            $category = new Category;
            $category->name = $request->name;

            $category->save();
            return response()->json($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {

        try {
            $oneCategory = Category::findOrFail($id);
            return response()->json($oneCategory);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $data = array();
        $data['name'] = $request->name;

        $category = DB::table('categories')->where('id', $id)->update($data);

        if ($category) {
            return response()->json(['message' => 'Successfully updated category!']);
        } else {
            return response()->json(['message' => 'Duplicated category!']);
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(['message' => 'Category is successfully deleted!']);
    }


}
