<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use Validator;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parkings = Parking::all();
        return $parkings;
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
            ['name' => 'required|string|max:30',
            'price' => 'required|regex:/^\d+([\.\,]\d{1,2})?$/',
            'location' => 'required|string|max:40|regex:/^\d+\.\d+\,\d+\.\d+$/',
            'opening_hours' => 'required|string|max:20',
            'additional_services' => 'required|string|max:40',
            'facilities' => 'required|string|max:40',
            'parking_spaces' => 'required|integer|min:1'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $parking = new Parking();
        $parking->name = $request->input('name');
        $parking->price = $request->input('price');
        $parking->location = $request->input('location');
        $parking->opening_hours = $request->input('opening_hours');
        $parking->additional_services = $request->input('additional_services');
        $parking->facilities = $request->input('facilities');
        $parking->operator_id = $request->input('operator_id');
        $parking->parking_spaces = $request->input('parking_spaces');

        $parking->save();
        return $parking;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parking = Parking::findOrFail($id);
        return $parking;
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
            ['name' => 'required|string|max:30',
            'price' => 'required|regex:/^\d+([\.\,]\d{1,2})?$/',
            'location' => 'required|string|max:40|regex:/^\d+\.\d+\,\d+\.\d+$/',
            'opening_hours' => 'required|string|max:20',
            'additional_services' => 'required|string|max:40',
            'facilities' => 'required|string|max:40',
            'parking_spaces' => 'required|integer|min:1'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $parking = Parking::findOrFail($id);
        $parking->name = $request->input('name');
        $parking->price = $request->input('price');
        $parking->location = $request->input('location');
        $parking->opening_hours = $request->input('opening_hours');
        $parking->additional_services = $request->input('additional_services');
        $parking->facilities = $request->input('facilities');
        $parking->operator_id = $request->input('operator_id');
        $parking->parking_spaces = $request->input('parking_spaces');

        $parking->save();
        return $parking;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $parking = Parking::findOrFail($id);
        if($parking->delete())
        return $parking;
    }
}
