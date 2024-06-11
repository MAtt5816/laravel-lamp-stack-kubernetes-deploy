<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\OperatorCodeController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\BalanceController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class getFromDB
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if(Session::has('token')){
            $uid = Session::get('user')->id;

            $driver_roles = array('cars','reservations','stops','balance');
            $operator_roles = array('parkings', 'operator_id', 'inspectors','allParkings');

            if(in_array($role,$driver_roles)){
                $driver = new DriverController();
                $drivers = $driver->index();
                $driver_id = null;
                foreach($drivers as $item){
                    if($item->user_id == $uid){
                        $driver_id = $item->id;
                        break;
                    }
                }
                $request->request->add(['driver_id' => $driver_id]);
            }
            else if(in_array($role,$operator_roles)){
                $operator_id = null;
                if(Session::get('user')->user_type == 'operator'){
                    $operator = new OperatorController();
                    $operators = $operator->index();
                    foreach($operators as $item){
                        if($item->user_id == $uid){
                            $operator_id = $item->id;
                            break;
                        }
                    }
                }
                else if(Session::get('user')->user_type == 'inspector'){
                    $inspector = new InspectorController();
                    $inspectors = $inspector->index();
                    foreach($inspectors as $item){
                        if($item->user_id == $uid){
                            $operator_id = $item->operator_id;
                            break;
                        }
                    }
                }
                $request->request->add(['operator_id' => $operator_id]);
            }

            switch($role){
                case 'cars': {
                    $vehicle = new VehicleController();
                    $vehicles = $vehicle->index();
                    $arr = array();
                    $arr1 = array();
                    foreach($vehicles as $item){
                        if($item->driver_id == $driver_id){
                            array_push($arr, $item->registration_plate);
                            array_push($arr1, $item->id);    
                        }
                    }
                    session(['cars' => $arr]);
                    session(['cars_id' => $arr1]);

                    break;
                }
                case 'balance': {
                    $balances = new BalanceController();
                    $balances = $balances->index();
                    $balance = 0;
                    foreach($balances as $item){
                        if($item->driver_id == $driver_id){
                            $balance = strval($item->balance);    
                        }
                    }
                    session(['balance' => $balance]);

                    break;
                }
                case 'verify': {
                    $vehicle = new VehicleController();
                    $vehicle = $vehicle->index();
                    $stops = new StopController();
                    $stops = $stops->index();
                    $reservations = new ReservationController();
                    $reservations = $reservations->index();

                    foreach($vehicle as $car){
                        if($request->registration_plate == $car->registration_plate){
                            foreach($stops as $stop){
                                if($stop->vehicle_id == $car->id && $stop->start_date < Carbon::now()){
                                    if($stop->end_date === null){
                                        Session::flash('verify', 0); // start-stop
                                        return $next($request);
                                    }
                                    else if($stop->end_date > Carbon::now()){
                                        Session::flash('verify', 1); // all is OK
                                        return $next($request);    
                                    }
                                    else{
                                        Session::flash('verify_date', Carbon::parse($stop->end_date)->setTimeZone('Europe/Warsaw')->format('Y-m-d H:i:s'));
                                    }
                                }
                            }
                            foreach($reservations as $reservation){
                                if($reservation->vehicle_id == $car->id && $reservation->start_date < Carbon::now() && $reservation->end_date > Carbon::now()){
                                    Session::flash('verify', 1); // all is OK
                                    return $next($request);
                                }
                            }
                        }
                    }
                    Session::flash('verify', -1); // no payment

                    break;
                }
                case 'reservations': {
                    $reservation = new ReservationController();
                    $reservations = $reservation->index();
                    $arr = array();
                    $arr1 = array();
                    foreach($reservations as $item){
                        if($item->driver_id == $driver_id){
                            array_push($arr, $item->start_date);
                            array_push($arr1, $item->id);  
                        }
                    }
                    session(['reservations' => $arr]);
                    session(['reservations_id' => $arr1]);

                    break;
                }
                case 'stops': {
                    $stop = new StopController();
                    $stops = $stop->index();
                    $arr = array();
                    $arr1 = array();
                    $end_date = array();
                    foreach($stops as $item){
                        if($item->driver_id == $driver_id){
                            array_push($arr, $item->start_date);
                            array_push($arr1, $item->id);  
                            array_push($end_date, $item->end_date);  
                        }
                    }
                    session(['stops' => $arr]);
                    session(['stops_id' => $arr1]);
                    session(['end_date' => $end_date]);

                    break;
                }
                case 'inspectors': {
                    $inspector = new OperatorCodeController();
                    $inspectors = $inspector->index();
                    $arr = array();
                    $arr1 = array();
                    $arr2 = array();
                    foreach($inspectors as $item){
                        if($item->operator_id == $operator_id){
                            array_push($arr, $item->name);
                            array_push($arr1, $item->surname);   
                            array_push($arr2, $item->id); 
                        }
                    }
                    session(['inspectors_n' => $arr]);
                    session(['inspectors_s' => $arr1]);
                    session(['inspectors_id' => $arr2]);

                    break;
                }
                case 'operator_id': {
                    Session::flash('operator_id', $operator_id);
                }
                case 'parkings': {
                    $parking = new ParkingController();
                    $stop = new StopController();
                    $reservation = new ReservationController();        
                    $parkings = $parking->index();
                    $stops = $stop->index();
                    $reservations = $reservation->index();        
                    $arr = array();
                    $arr1 = array();
                    $total = array();
                    $free = array();
                    $location = array();
                    $oid = array();
                    foreach($parkings as $item){
                        if($item->operator_id == $operator_id){
                            array_push($arr, $item->name);
                            array_push($arr1, $item->id);
                            array_push($total, $item->parking_spaces);
                            array_push($location, $item->location);  
                            $busy = 0;
                            foreach($stops as $elem){
                                if(($elem->end_date == null || $elem->end_date > Carbon::now()) && $elem->parking_id == $item->id){
                                    $busy++;
                                }    
                            }
                            foreach($reservations as $el){
                                if($el->end_date > Carbon::now() && $el->start_date <= Carbon::now() && $el->parking_id == $item->id){
                                    $busy++;
                                }
                            }
                            if($item->parking_spaces >= $busy){
                                array_push($free, $item->parking_spaces - $busy);
                            }
                            else{
                                array_push($free, 0);
                            }  
                            if ($item->operator_id == $request->input('operator_id')){
                                array_push($oid, true);
                            } else {
                                array_push($oid, false);
                            }
                        }
                    }
                    session(['parkings' => $arr]);
                    session(['parkings_id' => $arr1]);
                    session(['total' => $total]);
                    session(['free' => $free]);
                    session(['locations' => $location]);
                    session(['operators' => $oid]);

                    break;
                }
                case 'allParkings': break;
                default:
                    return redirect('/');
            }
        }

        if($role == 'allParkings'){
            $parking = new ParkingController();
            $stop = new StopController();
            $reservation = new ReservationController();
            $parkings = $parking->index();
            $stops = $stop->index();
            $reservations = $reservation->index();
            $arr = array();
            $arr1 = array();
            $total = array();
            $free = array();
            $location = array();
            $oid = array();
            foreach($parkings as $item){
                array_push($arr, $item->name);
                array_push($arr1, $item->id);
                $busy = 0;
                foreach($stops as $elem){
                    if(($elem->end_date == null || $elem->end_date > Carbon::now()) && $elem->parking_id == $item->id){
                        $busy++;
                    }    
                }
                foreach($reservations as $el){
                    if($el->end_date > Carbon::now() && $el->start_date <= Carbon::now() && $el->parking_id == $item->id){
                        $busy++;
                    }
                }
                if($item->parking_spaces >= $busy){
                    array_push($free, $item->parking_spaces - $busy);
                }
                else{
                    array_push($free, 0);
                }
                array_push($total, $item->parking_spaces);
                array_push($location, $item->location);
                if($request->input('operator_id') !== null){
                    if ($item->operator_id == $request->input('operator_id')){
                        array_push($oid, true);
                    } else {
                        array_push($oid, false);
                    }
                }
            }
            session(['parkings' => $arr]);
            session(['parkings_id' => $arr1]);
            session(['total' => $total]);
            session(['free' => $free]);
            session(['locations' => $location]);
            session(['operators' => $oid]);
        }
                    
        return $next($request);
    }
}
