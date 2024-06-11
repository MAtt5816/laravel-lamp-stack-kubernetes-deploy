<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OperatorCodeController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class addToDB
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
        $operator_roles = array('parking','code');

        $uid = Session::get('user')->id;

        if(in_array($role,$driver_roles)){
            if($request->input('driver_id') !== null){
                return back()->withErrors(['err','Podejrzenie modyfikacji danych!']);   // suspicious request body
            }
            $driver = new DriverController();
            $drivers = $driver->index();
            foreach($drivers as $item){
                if($item->user_id == $uid){
                    $driver_id = $item->id;
                    break;
                }
            }
            $request->request->add(['driver_id' => $driver_id]);
        }
        else if(in_array($role,$operator_roles)){
            if($request->input('operator_id') !== null){
                return back()->withErrors(['err','Podejrzenie modyfikacji danych!']);   // suspicious request body
            }
            $operator = new OperatorController();
            $operators = $operator->index();
            foreach($operators as $item){
                if($item->user_id == $uid){
                    $operator_id = $item->id;
                    break;
                }
            }
            $request->request->add(['operator_id' => $operator_id]);
        }

        if($role == 'stop' || $role == 'reservation'){
            foreach(Session::get('parkings_id') as $key=>$pid){
                if($pid == $request->parking_id){
                    $total = Session::get('total')[$key];
                }
            }
        }

        switch($role){
            case 'vehicle': {
                $vehicle = new VehicleController();
                $vehicle = $vehicle->store($request);
                if(method_exists($vehicle,'status') && $vehicle->status() >= 400){
                    return back()->withErrors(json_decode($vehicle->content()));
                }

                break;
            }
            case 'parking': {
                $parking = new ParkingController();
                $parking = $parking->store($request);
                if(method_exists($parking,'status') && $parking->status() >= 400){
                    return back()->withErrors(json_decode($parking->content()));
                }

                break;
            }
            case 'code': {
                $code = new OperatorCodeController();
                $request->merge(['operator_code' => $code->randCode()]);
                $code = $code->store($request);
                if(method_exists($code,'status') && $code->status() >= 400){
                    return back()->withErrors(json_decode($code->content()));
                }

                break;
            }
            case 'stop': {
                $stop = new StopController();
                $reservation = new ReservationController();
                $stops = $stop->index();
                $reservations = $reservation->index();
                $busy = 0;
                foreach($stops as $elem){
                    if(($elem->end_date == null || $elem->end_date > $request->end_date) && $elem->parking_id == $request->parking_id){
                        $busy++;
                    }    
                }
                foreach($reservations as $el){
                    if($el->end_date > $request->end_date && $el->start_date <= Carbon::now() && $el->parking_id == $request->parking_id){
                        $busy++;
                    }
                }
                if($total < $busy){
                    return back()->withErrors(['err','Brak wolnych miejsc dla podanych parametrów']);   // No empty spaces for parameters
                }

                $parking = new ParkingController();
                $parking = $parking->show($request->parking_id);
                $balance = new BalanceController();
                $balances = $balance->index();
                foreach($balances as $ba){
                    if($ba->driver_id == $driver_id){
                        $balance_val = $ba->balance;
                        $bid = $ba->id;
                        break;
                    }
                }
                if($request->end_date !== null){
                    $end = Carbon::parse($request->end_date)->setTimeZone('-1')->format('Y-m-d H:i:s');
                    $balance_val -= round(($parking->price * (Carbon::now()->diffInMinutes($end) + 1) / 60), 2);
                    $request->merge(['balance' => $balance_val]);
                    $request->merge(['driver_id' => $driver_id]);
                    $balance->update($request, $bid);
                }

                $request->merge(['start_date' => Carbon::now()->format('Y-m-d H:i:s')]);
                if(!is_null($request->input('end_date'))){
                    $request->merge(['end_date' => Carbon::parse($request->end_date)->setTimeZone('-1')->format('Y-m-d H:i:s')]);
                } else {
                    $request->merge(['end_date' => null]);
                }

                $stop = $stop->store($request);
                if(method_exists($stop,'status') && $stop->status() >= 400){
                    return back()->withErrors(json_decode($stop->content()));
                }

                break;
            }
            case 'reservation': {
                $stop = new StopController();
                $reservation = new ReservationController();
                $stops = $stop->index();
                $reservations = $reservation->index();
                $busy = 0;
                foreach($stops as $elem){
                    if(($elem->end_date == null || $elem->end_date > $request->end_date) && $elem->parking_id == $request->parking_id){
                        $busy++;
                    }    
                }
                foreach($reservations as $el){
                    if($el->end_date > $request->end_date && $el->start_date <= $request->start_date && $el->parking_id == $request->parking_id){
                        $busy++;
                    }
                }
                if($total < $busy){
                    return back()->withErrors(['err','Brak wolnych miejsc dla podanych parametrów']);   // No empty spaces for parameters
                }

                if($request->start_date > $request->end_date){
                    return back()->withErrors(['err','Data zakończenia nie może być wcześniejsza od daty rozpoczęcia']);   // The end date cannot be earlier than the start date
                }
                else if($request->start_date == $request->end_date){
                    return back()->withErrors(['err','Rezerwacja musi być dłuższa od 0 s']);   // The reservation must be longer than 0 seconds
                }

                $parking = new ParkingController();
                $parking = $parking->show($request->parking_id);
                $balance = new BalanceController();
                $balances = $balance->index();
                foreach($balances as $ba){
                    if($ba->driver_id == $driver_id){
                        $balance_val = $ba->balance;
                        $bid = $ba->id;
                        break;
                    }
                }
                $balance_val -= round(($parking->price * (Carbon::parse($request->start_date)->diffInMinutes($request->end_date) + 1) / 60), 2);
                $request->merge(['balance' => $balance_val]);
                $request->merge(['driver_id' => $driver_id]);
                $balance->update($request, $bid);

                $request->merge(['start_date' => Carbon::parse($request->start_date)->setTimeZone('-1')->format('Y-m-d H:i:s')]);
                $request->merge(['end_date' => Carbon::parse($request->end_date)->setTimeZone('-1')->format('Y-m-d H:i:s')]);

                $reservation = $reservation->store($request);
                if(method_exists($reservation,'status') && $reservation->status() >= 400){
                    return back()->withErrors(json_decode($reservation->content()));
                }

                break;
            }
            default: {
                return redirect('/');
            }
        }

        return $next($request)->withSuccess(['Pomyślnie dodano']);
    }
}
