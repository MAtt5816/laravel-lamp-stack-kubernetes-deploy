<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stop;
use Validator;
use Illuminate\Support\Carbon;

class StopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stops = Stop::all();
        return $stops;
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
        $validator = Validator::make($request->all(),
            ['end_date' => 'nullable|date|after:'.Carbon::now()->setTimeZone('-1'),
            'parking_id' => 'required|integer'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $stop = new Stop();
        $stop->start_date = $request->input('start_date');
        $stop->end_date = $request->input('end_date');
        $stop->driver_id = $request->input('driver_id');
        $stop->vehicle_id = $request->input('vehicle_id');
        $stop->parking_id = $request->input('parking_id');

        $stop->save();
        return $stop;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stop = Stop::findOrFail($id);
        return $stop;
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
        $validator = Validator::make($request->all(),
            ['end_date' => 'nullable|date|after:'.Carbon::now()->setTimeZone('-1'),
            'parking_id' => 'required|integer'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $stop = Stop::findOrFail($id);
        $stop->start_date = $request->input('start_date');
        $stop->end_date = $request->input('end_date');
        $stop->driver_id = $request->input('driver_id');
        $stop->vehicle_id = $request->input('vehicle_id');
        $stop->parking_id = $request->input('parking_id');

        $stop->save();
        return $stop;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stop = Stop::findOrFail($id);
        if($stop->delete())
        return $stop;
    }
}
