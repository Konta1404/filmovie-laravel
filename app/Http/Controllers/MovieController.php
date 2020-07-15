<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        return response()->json($movies);
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
            'name'=>'required',
            'year'=>'',
            'rating'=>'',
            'poster'=>'',
            'slider_image'=>'',
            'description'=>'',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Duplicated category']);
        }

        $movie = new Movie;
        $movie->name = $request->name;
        $movie->year = $request->year;
        $movie->rating = $request->rating;
        $movie->poster = $request->poster;
        $movie->slider_image = $request->slider_image;
        $movie->description = $request->description;

        $movie->save();
        return response()->json($movie);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {

        try {
            $oneMovie = Movie::findOrFail($id);
            return response()->json($oneMovie);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
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
        $data['year'] = $request->year;
        $data['rating'] = $request->rating;
        $data['poster'] = $request->poster;
        $data['slider_image'] = $request->slider_image;
        $data['description'] = $request->description;

        $movie = DB::table('movies')->where('id', $id)->update($data);

        if ($movie) {
            return response()->json(['message' => 'Successfully updated category!']);
        } else {
            return response()->json(['message' => 'Duplicated category!']);
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Movie::findOrFail($id)->delete();
        return response()->json(['message' => 'Movie is successfully deleted!']);
    }
}
