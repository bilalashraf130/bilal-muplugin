<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

use WPWhales\Subscriptions\Models\Feature;
class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $features = Feature::all();
        return view('feature_form')->with(['features'=>$features]);
//        return view('feature_form',['features'=>$features]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $data = $this->validate($request, [
            'name'        => 'required',


        ]);

        $content = new Feature;
        $content->name         = $request->name;
        $content->description  = $request->description;
        $content->slug         = $request->name;
        $content->save();
        return redirect(route('crud_index'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->query('id');
        $content = Feature::find($id);
        return view('feature_edit_form')->with(['content'=>$content]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->validate($request, [
            'name'        => 'required',

        ]);
        $content = Feature::find($request->id);
        $content->name         = $request->name;
        $content->description  = $request->description;
        $content->slug         = $request->name;

        $content->save();
        return redirect(route('crud_index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->query('id');
        $delete = Feature::destroy($id);
        return redirect(route('crud_index'));
    }
}
