<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicles = Vehicle::all();
        return $vehicles;
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
            ['registration_plate' => 'required|string|max:8|unique:vehicles',
            'brand' => 'required|string|max:20',
            'model' => 'required|string|max:20',
            'driver_id' => 'required|integer'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $vehicle = new Vehicle();
        $vehicle->registration_plate = $request->input('registration_plate');
        $vehicle->brand = $request->input('brand');
        $vehicle->model = $request->input('model');
        $vehicle->driver_id = $request->input('driver_id');

        $vehicle->save();
        return $vehicle;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return $vehicle;
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
            ['registration_plate' => 'required|string|max:8',
            'brand' => 'required|string|max:20',
            'model' => 'required|string|max:20',
            'driver_id' => 'required|integer'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->registration_plate = $request->input('registration_plate');
        $vehicle->brand = $request->input('brand');
        $vehicle->model = $request->input('model');
        $vehicle->driver_id = $request->input('driver_id');

        $vehicle->save();
        return $vehicle;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        if($vehicle->delete())
        return $vehicle;
    }
}
