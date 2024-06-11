<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspector;

class InspectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspectors = Inspector::all();
        return $inspectors;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inspector = new Inspector();
        $inspector->user_id = $request->input('user_id');
        $inspector->operator_code = $request->input('operator_code');
        $inspector->operator_id = $request->input('operator_id');

        $inspector->save();
        return $inspector;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inspector = Inspector::findOrFail($id);
        return $inspector;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inspector = Inspector::findOrFail($id);
        $inspector->user_id = $request->input('user_id');
        $inspector->operator_code = $request->input('operator_code');
        $inspector->operator_id = $request->input('operator_id');

        $inspector->save();
        return $inspector;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inspector = Inspector::findOrFail($id);
        if($inspector->delete())
        return $inspector;
    }
}
