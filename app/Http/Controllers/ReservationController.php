<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Validator;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::all();
        return $reservations;
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
            ['start_date' => 'required|date|after_or_equal:'.Carbon::now()->setTimeZone('-1'),
            'end_date' => 'required|date|after:'.Carbon::now()->setTimeZone('-1'),
            'parking_id' => 'required|integer'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $reservation = new Reservation();
        $reservation->start_date = $request->input('start_date');
        $reservation->end_date = $request->input('end_date');
        $reservation->driver_id = $request->input('driver_id');
        $reservation->vehicle_id = $request->input('vehicle_id');
        $reservation->parking_id = $request->input('parking_id');

        $reservation->save();
        return $reservation;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return $reservation;
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
            ['start_date' => 'required|date|after_or_equal:'.Carbon::now()->setTimeZone('-1'),
            'end_date' => 'required|date|after:'.Carbon::now()->setTimeZone('-1'),
            'parking_id' => 'required|integer'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $reservation = Reservation::findOrFail($id);
        $reservation->start_date = $request->input('start_date');
        $reservation->end_date = $request->input('end_date');
        $reservation->driver_id = $request->input('driver_id');
        $reservation->vehicle_id = $request->input('vehicle_id');
        $reservation->parking_id = $request->input('parking_id');

        $reservation->save();
        return $reservation;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        if($reservation->delete())
        return $reservation;
    }
}
