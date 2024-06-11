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

class updateDB
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

        if($role == 'user'){
            if(Session::get('user')->user_type == 'driver'){
                array_push($driver_roles, 'user');
            }
            else if(Session::get('user')->user_type == 'operator'){
                array_push($operator_roles, 'user');
            }
        }

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

        switch($role){
            case 'vehicle': {
                $request->merge(['registration_plate' => $request->input('registration_plate')]);
                $request->merge(['brand' => $request->input('brand')]);
                $request->merge(['model' => $request->input('model')]);
                $vehicle = new VehicleController();
                try {
                    $vehicle = $vehicle->update($request, $request->input('id'));
                    if(method_exists($vehicle,'status') && $vehicle->status() >= 400){
                        return back()->withErrors(json_decode($vehicle->content()));
                    }
                } catch (Throwable $th) {
                    return Redirect::back()->withErrors(['err: ',$th->getMessage()]);
                }
                
                break;
            }
            case 'parking': {
                $request->merge(['name' => $request->input('name')]);
                $request->merge(['price' => $request->input('price')]);
                $request->merge(['location' => $request->input('location')]);
                $request->merge(['opening_hours' => $request->input('opening_hours')]);
                $request->merge(['additional_services' => $request->input('additional_services')]);
                $request->merge(['facilities' => $request->input('facilities')]);
                $parking = new ParkingController();
                $parking = $parking->update($request, $request->input('id'));
                if(method_exists($parking,'status') && $parking->status() >= 400){
                    return back()->withErrors(json_decode($parking->content()));
                }

                break;
            }
            case 'inspector': {
                $request->merge(['name' => $request->input('name')]);
                $request->merge(['surname' => $request->input('surname')]);
                $request->merge(['operator_code' => Session::get('inspector')->operator_code]);
                $inspector = new OperatorCodeController();
                $inspector = $inspector->update($request, $request->input('id'));
                if(method_exists($inspector,'status') && $inspector->status() >= 400){
                    return back()->withErrors(json_decode($inspector->content()));
                }

                break;
            }
            case 'stop': {
                Session::reflash();
                $request->merge(['start_date' => Carbon::parse(Session::get('stop')->start_date)->format('Y-m-d H:i:s')]);
                $request->merge(['end_date' => Carbon::now()->format('Y-m-d H:i:s')]);
                $request->merge(['driver_id' => Session::get('stop')->driver_id]);
                $request->merge(['vehicle_id' => Session::get('stop')->vehicle_id]);
                $request->merge(['parking_id' => Session::get('stop')->parking_id]);

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
                $balance_val -= round(($parking->price * (Carbon::parse($request->end_date)->diffInMinutes($request->start_date) + 1) / 60), 2);
                $request->merge(['balance' => $balance_val]);
                $request->merge(['driver_id' => $driver_id]);
                $balance->update($request, $bid);

                $stop = new StopController();
                $stop = $stop->update($request, Session::get('stop')->id);
                if(method_exists($stop,'status') && $stop->status() >= 400){
                    return back()->withErrors(json_decode($stop->content()));
                }

                break;
            }
            case 'reservation': {
                $reservation = new ReservationController();
                $old = $reservation->show($request->input('id'));
                $old = Carbon::parse($old->end_date)->diffInMinutes($old->start_date) + 1;

                $parking = new ParkingController();
                $parking = $parking->show($request->input('parking_id'));
                $balance = new BalanceController();
                $balances = $balance->index();
                foreach($balances as $ba){
                    if($ba->driver_id == $driver_id){
                        $balance_val = $ba->balance;
                        $bid = $ba->id;
                        break;
                    }
                }
                $diff = Carbon::parse($request->end_date)->diffInMinutes($request->start_date) + 1;
                if($old >= $diff){
                    $balance_val += round(($parking->price * ($old-$diff) / 60), 2);
                }
                $balance_val -= round(($parking->price * ($diff-$old) / 60), 2);
                $request->merge(['balance' => $balance_val]);
                $request->merge(['driver_id' => $driver_id]);
                $balance->update($request, $bid);

                if($request->start_date > $request->end_date){
                    return back()->withErrors(['err','Data zakończenia nie może być wcześniejsza od daty rozpoczęcia']);   // The end date cannot be earlier than the start date
                }
                else if($request->start_date == $request->end_date){
                    return back()->withErrors(['err','Rezerwacja musi być dłuższa od 0 s']);   // The reservation must be longer than 0 seconds
                }
                $request->merge(['start_date' => Carbon::parse($request->start_date)->setTimeZone('-1')->format('Y-m-d H:i:s')]);
                $request->merge(['end_date' => Carbon::parse($request->end_date)->setTimeZone('-1')->format('Y-m-d H:i:s')]);
                $request->merge(['vehicle_id' => $request->input('vehicle_id')]);
                $request->merge(['parking_id' => $request->input('parking_id')]);

                $reservation = $reservation->update($request, $request->input('id'));
                if(method_exists($reservation,'status') && $reservation->status() >= 400){
                    return back()->withErrors(json_decode($reservation->content()));
                }

                break;
            }
            case 'user': {
                if(Session::get('user')->user_type == 'driver'){
                    $request->merge(['name' => $request->input('name')]);
                    $request->merge(['surname' => $request->input('surname')]);
                    $request->merge(['city' => $request->input('city')]);
                    $request->merge(['street' => $request->input('street')]);
                    $request->merge(['house_number' => $request->input('house_number')]);
                    $request->merge(['postal_code' => $request->input('postal_code')]);
                    $request->merge(['phone' => $request->input('phone')]);
                    $request->merge(['email' => $request->input('email')]);
                    $request->merge(['user_id' => $request->input('user_id')]);
                    $driver = new DriverController();
                    $res = $driver->update($request, $driver_id);
                }
                else if(Session::get('user')->user_type == 'operator'){
                    $request->merge(['user_id' => $request->input('user_id')]);
                    $request->merge(['email' => $request->input('email')]);
                    $request->merge(['phone' => $request->input('phone')]);
                    $request->merge(['tin' => $request->input('tin')]);
                    $operator = new OperatorController();
                    $operator->update($request, $operator_id);
                }

                break;
            }
            default: {
                return redirect('/');
            }
        }

        return $next($request)->withSuccess(['Pomyślnie zaktualizowano']);
    }
}
