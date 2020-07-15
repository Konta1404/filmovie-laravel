<?php

namespace App\Http\Controllers;

use App\Actor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActorController extends Controller
{
    public function index()
    {
        $actors = Actor::all();
        return response()->json($actors);
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

        $actor = new Actor;
        $actor->first_name = $request->first_name;
        $actor->last_name = $request->last_name;
        $actor->date_of_birth = $request->date_of_birth;
        $actor->biography = $request->biography;
        $actor->photo = $request->photo;

        $actor->save();
        return response()->json($actor);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {

        try {
            $oneActor = Actor::findOrFail($id);
            return response()->json($oneActor);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function edit(Actor $actor)
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

        $actor = DB::table('actors')->where('id', $id)->update($data);

        if ($actor) {
            return response()->json(['message' => 'Successfully updated category!']);
        } else {
            return response()->json(['message' => 'Duplicated category!']);
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Actor::findOrFail($id)->delete();
        return response()->json(['message' => 'Actor is successfully deleted!']);
    }
}
