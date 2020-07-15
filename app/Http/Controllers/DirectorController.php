<?php

namespace App\Http\Controllers;

use App\Director;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DirectorController extends Controller
{
    public function index()
    {
        $directors = Director::all();
        return response()->json($directors);
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
            'first_name'=>'required',
            'last_name'=>'',
            'date_of_birth'=>'',
            'biography'=>'',
            'photo'=>''
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Duplicated category']);
        }

        $director = new Director;
        $director->first_name = $request->first_name;
        $director->last_name = $request->last_name;
        $director->date_of_birth = $request->date_of_birth;
        $director->biography = $request->biography;
        $director->photo = $request->photo;

        $director->save();
        return response()->json($director);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Director  $director
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {

        try {
            $oneDirector = Director::findOrFail($id);
            return response()->json($oneDirector);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Director  $director
     * @return \Illuminate\Http\Response
     */
    public function edit(Director $director)
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
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['date_of_birth'] = $request->date_of_birth;
        $data['biography'] = $request->biography;
        $data['photo'] = $request->photo;

        $director = DB::table('directors')->where('id', $id)->update($data);

        if ($director) {
            return response()->json(['message' => 'Successfully updated category!']);
        } else {
            return response()->json(['message' => 'Duplicated category!']);
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Director  $director
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Director::findOrFail($id)->delete();
        return response()->json(['message' => 'Director is successfully deleted!']);
    }
}
