<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = Driver::all();
        return $drivers;
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
        $driver = new Driver();
        $driver->name = $request->input('name');
        $driver->surname = $request->input('surname');
        $driver->city = $request->input('city');
        $driver->street = $request->input('street');
        $driver->house_number = $request->input('house_number');
        $driver->postal_code = $request->input('postal_code');
        $driver->phone = $request->input('phone');
        $driver->email = $request->input('email');
        $driver->user_id = $request->input('user_id');

        $driver->save();
        return $driver;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = Driver::findOrFail($id);
        return $driver;
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
        $driver = Driver::findOrFail($id);
        $driver->name = $request->input('name');
        $driver->surname = $request->input('surname');
        $driver->city = $request->input('city');
        $driver->street = $request->input('street');
        $driver->house_number = $request->input('house_number');
        $driver->postal_code = $request->input('postal_code');
        $driver->phone = $request->input('phone');
        $driver->email = $request->input('email');
        $driver->user_id = $request->input('user_id');

        $driver->save();
        return $driver;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        if($driver->delete())
        return $driver;
    }
}
