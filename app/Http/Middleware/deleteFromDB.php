<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OperatorCodeController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Carbon;

class deleteFromDB
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
        $driver_roles = array('vehicle','stop','reservation');
        $operator_roles = array('parking','inspector');

        $uid = Session::get('user')->id;
        $id = $request->route()->parameter('id');

        if($role == 'user'){
            if(Session::get('user')->user_type == 'driver'){
                array_push($driver_roles, 'user');
            }
            else if(Session::get('user')->user_type == 'operator'){
                array_push($operator_roles, 'user');
            }
        }

        if(in_array($role,$driver_roles)){
            $driver = new DriverController();
            $drivers = $driver->index();
            foreach($drivers as $item){
                if($item->user_id == $uid){
                    $driver_id = $item->id;
                    break;
                }
            }
        }
        else if(in_array($role,$operator_roles)){
            $operator = new OperatorController();
            $operators = $operator->index();
            foreach($operators as $item){
                if($item->user_id == $uid){
                    $operator_id = $item->id;
                    break;
                }
            }
        }

        switch($role){
            case 'vehicle': {
                $vehicle = new VehicleController();
                $json = json_decode($vehicle->show($id));
                if($json->driver_id != $driver_id){
                    return back()->withErrors(['err','Zalogowano na niewłaściwe konto']);   // bad user ID
                }
                $stops = new StopController();
                $stops = json_decode($stops->index());
                foreach($stops as $stop){
                    if($json->id == $stop->vehicle_id && $stop->end_date === null){
                        return back()->withErrors(['err','Masz niezakończone postoje!']);   // open stops
                    }
                }

                $request->session()->forget('cars');
                $request->session()->forget('cars_id');

                $vehicle->destroy($id);

                break;
            }
            case 'parking': {
                $parking = new ParkingController();
                $json = json_decode($parking->show($id));
                if($json->operator_id != $operator_id){
                    return back()->withErrors(['err','Zalogowano na niewłaściwe konto']);   // bad user ID
                }
                $stops = new StopController();
                $stops = json_decode($stops->index());
                foreach($stops as $stop){
                    if($json->id == $stop->parking_id && $stop->end_date === null){
                        return back()->withErrors(['err','Masz niezakończone postoje!']);   // open stops
                    }
                }

                $request->session()->forget('parkings');
                $request->session()->forget('parkings_id');
                $request->session()->forget('locations');
                $request->session()->forget('total');
                $request->session()->forget('free');
                $request->session()->forget('operators');

                $parking->destroy($id);

                break;
            }
            case 'inspector': {
                $code = new OperatorCodeController();
                $json = json_decode($code->show($id));
                if($json->operator_id != $operator_id){
                    return back()->withErrors(['err','Zalogowano na niewłaściwe konto']);   // bad user ID
                }

                $inspector = new InspectorController();
                $inspector = $inspector->index();
                foreach($inspector as $item){
                    if($json->operator_code == $item->operator_code){
                        $user = new UserController();
                        $user->destroy($item->user_id);
                        break;
                    }
                }

                $request->session()->forget('inspectors_n');
                $request->session()->forget('inspectors_s');
                $request->session()->forget('inspectors_id');

                $code->destroy($id);

                break;
            }
            case 'stop': {  // cannot to remove stops
                $stop = new StopController();
                $json = json_decode($stop->show($id));
                if($json->driver_id != $driver_id){
                    return back()->withErrors(['err','Zalogowano na niewłaściwe konto']);   // bad user ID
                }

                // $request->session()->forget('stops');
                // $request->session()->forget('stops_id');

                // $stop->destroy($id);

                break;
            }
            case 'reservation': {
                $reservation = new ReservationController();
                $json = json_decode($reservation->show($id));
                if($json->driver_id != $driver_id){
                    return back()->withErrors(['err','Zalogowano na niewłaściwe konto']);   // bad user ID
                }
                if($json->start_date < Carbon::now()){
                    return back()->withErrors(['err','Nie można usunąć trwającej rezerwacji']);   // cannot to delete reservation in progress
                }
                
                $parking = new ParkingController();
                $parking = $parking->show($json->parking_id);
                $balance = new BalanceController();
                $balances = $balance->index();
                foreach($balances as $ba){
                    if($ba->driver_id == $driver_id){
                        $balance_val = $ba->balance;
                        $bid = $ba->id;
                        break;
                    }
                }
                $balance_val += round(($parking->price * (Carbon::parse($json->start_date)->diffInMinutes($json->end_date) + 1) / 60), 2);
                $request->merge(['balance' => $balance_val]);
                $request->merge(['driver_id' => $driver_id]);
                $balance->update($request, $bid);

                $request->session()->forget('reservations');
                $request->session()->forget('reservations_id');

                $reservation->destroy($id);

                break;
            }
            case 'user': {
                $user = new UserController();
                $stops = new StopController();
                $stops = json_decode($stops->index());

                if(Session::get('user')->user_type == 'driver'){
                    foreach($stops as $stop){
                        if($driver_id == $stop->driver_id && $stop->end_date === null){
                            return back()->withErrors(['err','Masz niezakończone postoje!']);   // open stops
                        }
                    }
                    
                    $driver = new DriverController();
                    $driver->destroy($driver_id);
                }
                else if(Session::get('user')->user_type == 'operator'){
                    $parkings = new ParkingController();
                    $parkings = json_decode($parkings->index());
                    foreach($stops as $stop){
                        foreach($parkings as $parking){
                            if($operator_id == $parking->operator_id){
                                if(($stop->parking_id == $parking->id) && ($stop->end_date === null)){
                                    return back()->withErrors(['err','Masz niezakończone postoje!']);   // open stops
                                }
                            }
                        }
                    }

                    $operator = new OperatorController();
                    $operator->destroy($operator_id);
                }

                $request->session()->flush();
                $user->destroy($uid);
                return redirect('/account_removed');

                break;
            }
            default: {
                return redirect('/');
            }
        }


        return $next($request)->withSuccess(['Pomyślnie usunięto']);;
    }
}
