<?php

namespace App\Http\Controllers;

use App\Category;
use App\Movie;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $movies = DB::table('movies')->paginate(5);

        foreach ($movies as $movie) {
            $movie->categories = DB::table('belongs')->where('movie_id', 'LIKE', $movie->id)->get();
            Log::alert($movie->categories);
            foreach ($movie->categories as $category) {
                $category->category = DB::table('categories')->where('id', 'LIKE', $category->category_id)->get();
            }
            $movie->actors = DB::table('acts')->where('movie_id', 'LIKE', $movie->id)->get();
            if ($movie->actors !== []) {
                foreach ($movie->actors as $actor) {
                    $actor->actor = DB::table('actors')->where('id', 'LIKE', $actor->actor_id)->get();
                }
            }
            $movie->directors = DB::table('directs')->where('movie_id', 'LIKE', $movie->id)->get();
            foreach ($movie->directors as $director) {
                $director->director = DB::table('directors')->where('id', 'LIKE', $director->director_id)->get();
            }
        }

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

        if ($request->get('actors') != []) {
            $actors = $request->get('actors');
            foreach ($actors as $actor) {
                $acts = DB::table('acts')->insert(
                    array(
                        'movie_id' => $movie->id,
                        'actor_id' => $actor['id']
                    )
                );
            }
        }

        if ($request->get('directors')) {
            $directors = $request->get('directors');

            foreach ($directors as $director) {
                $belongs = DB::table('directs')->insert(
                    array('movie_id' => $movie->id, 'director_id' => $director['id'])
                );
            }
        }

        if ($request->get('categories')) {
            $categories = $request->get('categories');

            foreach ($categories as $category) {
                $belongs = DB::table('belongs')->insert(
                    array('movie_id' => $movie->id, 'category_id' => $category['id'])
                );
            }
        }

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
            $oneMovie->categories = DB::table('belongs')->where('movie_id', 'LIKE', $id)->get();
            foreach ($oneMovie->categories as $category) {
                $category->category = DB::table('categories')->where('id', 'LIKE', $category->category_id)->get();
            }
            $oneMovie->actors = DB::table('acts')->where('movie_id', 'LIKE', $id)->get();
            foreach ($oneMovie->actors as $actor) {
                $actor->actor = DB::table('actors')->where('id', 'LIKE', $actor->actor_id)->get();
            }
            $oneMovie->directors = DB::table('directs')->where('movie_id', 'LIKE', $id)->get();
            foreach ($oneMovie->directors as $director) {
                $director->director = DB::table('directors')->where('id', 'LIKE', $director->director_id)->get();
            }
            return response()->json($oneMovie);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

    }

    public function comment(Request $request) {
        $userId = $request->get('user_id');
        $movieId = $request->get('movie_id');
        $comment = $request->get('comment');

        $user = User::where('id', 'LIKE', $userId)->first();
        $movie = Movie::where('id', 'LIKE', $movieId)->first();

        $user->comment($movie, $comment, 3);

        return response()->json(['message' => 'Comment successfully created!']);
    }

    public function getComments($id) {

        $comments = DB::table('comments')->where('commentable_id', 'LIKE', $id)->orderBy('created_at', 'asc')->get();

        foreach ($comments as $comment) {
            $comment->commented_id = User::findOrFail($comment->commented_id);
        }

        return response()->json($comments);
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

    public function search(Request $request) {
        $query = $request->get('query');

        if ($query != '') {
            $movies = Movie::query()
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->orWhere('year', 'LIKE', "%{$query}%")
                ->orWhere('rating', 'LIKE', "%{$query}%")
                ->paginate(5);

            return response()->json($movies);
        } else {
            $movies = DB::table('movies')->paginate(5);
            return response()->json($movies);
        }
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

        if ($request->get('actors') != []) {
            $actors = $request->get('actors');
            foreach ($actors as $actor) {
                $acts = DB::table('acts')->insert(
                    array(
                        'movie_id' => $id,
                        'actor_id' => $actor['id']
                    )
                );
            }
        }

        if ($request->get('directors')) {
            $directors = $request->get('directors');

            foreach ($directors as $director) {
                $belongs = DB::table('directs')->insert(
                    array('movie_id' => $id, 'director_id' => $director['id'])
                );
            }
        }

        if ($request->get('categories')) {
            $categories = $request->get('actors');

            foreach ($categories as $category) {
                $belongs = DB::table('belongs')->insert(
                    array('movie_id' => $id, 'category_id' => $category['id'])
                );
            }
        }

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
