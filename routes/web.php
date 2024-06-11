<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home')->middleware(['getFromDB:allParkings','getFromDB:balance','increaseBalance']);

Route::get('/api/docs', function() {
    return redirect()->away('https://documenter.getpostman.com/view/20222408/2s84LLxCSL');
});

Route::get('/login', function() {
    return view('login');
})->name('loginForm');

Route::post('/login', function(Request $request) {
    $auth = new AuthController();
    $response = $auth->login($request);
    $json = json_decode($response->content());
    if($response->status() == 201){
        $request->session()->put('user', $json->{'user'});
        $request->session()->put('token', $json->{'token'});
        return redirect('/');
    }
    else{
        return redirect()->back()->withErrors($json);
    }
});

Route::get('/signup', function() {
    return view('signup');
});

Route::post('/signup', function(Request $request) {
    if($request->has('user')){
        switch($request->get('user')){
            case 'kierowca':
                return view('user/signup_k');
            case 'operator':
                return view('user/signup_o');
            case 'kontroler':
                return view('user/signup_i');
        }
    }
    return redirect()->back();
});

Route::get('/signup_driver', function() {
    return view('user/signup_k');
});

Route::post('/signup_driver', function() {
    return redirect('/');
})->middleware('registerUser:driver');

Route::get('/signup_operator', function() {
    return view('user/signup_o');
});

Route::post('/signup_operator', function() {
    return redirect('/');
})->middleware('registerUser:driver');

Route::get('/signup_inspector', function() {
    return view('user/signup_i');
});

Route::post('/signup_inspector', function() {
    return redirect('/');
})->middleware('registerUser:inspector');

Route::get('/logout', function() {
    Session::flush();
    return view('logout');
});

Route::get('/account_removed', function() {
    Session::flush();
    return view('postmortem');
});


Route::group(['middleware' => 'sessionCheck:all'], function() {
    
    Route::get('/change_password', function() {
        return view('ustawienia')->with('option', 'change_password');
    });
    
    Route::get('/settings', function() {
        return view('ustawienia')->with('option', 'settings');
    })->middleware('showFromDB:user');

    Route::get('/delete_account', function() {
        return view('ustawienia')->with('option', 'delete_account');
    });

    Route::post('/settings', function() {
        return redirect('/settings');
    })->middleware('updateDB:user');
    
    Route::post('/change_password', function() {
        return view('zmiana_hasla');
    })->middleware('resetPassword');

    Route::post('/delete_account', function() {
        return view('ustawienia')->with('option', 'delete_account');
    })->middleware('deleteFromDB:user');

    Route::get('/show_parking/{id}', function() {
        return back();
    })->middleware('showFromDB:parking');
});


Route::group(['middleware' => 'sessionCheck:driver'], function() {
    
    Route::get('/vehicle', function() {
        return view('pojazd');
    });
    
    Route::get('/vehicles', function() {
        return view('pojazdy');
    })->middleware('getFromDB:cars');
    
    Route::get('/reservation', function() {
        return view('rezerwacja');
    })->middleware(['getFromDB:cars','getFromDB:allParkings']);
    
    Route::get('/reservations', function() {
        return view('rezerwacje');
    })->middleware('getFromDB:reservations');

    Route::get('/topup', function() {
        return view('doladuj');
    })->middleware('showFromDB:user');

    Route::post('/pay', function() {
        return redirect('/');
    })->middleware('payment');

    
    Route::get('/stop', function() {
        return view('postoj');
    })->middleware(['getFromDB:cars','getFromDB:allParkings']);
    
    Route::get('/stops', function() {
        return view('postoje');
    })->middleware('getFromDB:stops');

    Route::get('/edit_vehicle/{id}', function() {
        return view('edytuj_pojazd');
    })->middleware('showFromDB:vehicle');

    Route::get('/edit_reservation/{id}', function() {
        return view('edytuj_rezerwacja');
    })->middleware(['getFromDB:cars','getFromDB:allParkings','showFromDB:reservation']);

    Route::group([], function() {
        Route::post('/vehicle', function() {
            return redirect('/vehicles');
        })->middleware('addToDB:vehicle');

        Route::post('/stop', function() {
            return redirect('/stops');
        })->middleware('getFromDB:allParkings','addToDB:stop');

        Route::post('/reservation', function() {
            return redirect('/reservations');
        })->middleware('getFromDB:allParkings','addToDB:reservation');

        Route::post('/update_vehicle', function() {
            return redirect('/vehicles');
        })->middleware('updateDB:vehicle');

        Route::post('/update_reservation', function() {
            return redirect('/reservations');
        })->middleware('updateDB:reservation');
    });

    Route::get('/delete_stop/{id}', function($id){
        return redirect('/stops');
    })->middleware('deleteFromDB:stop');

    Route::get('/delete_reservation/{id}', function() {
        return redirect('/reservations');
    })->middleware('deleteFromDB:reservation');

    Route::get('/delete_vehicle/{id}', function() {
        return redirect('/vehicles');
    })->middleware('deleteFromDB:vehicle');

    Route::get('/show_reservation/{id}', function() {
        return redirect('/reservations');
    })->middleware('showFromDB:reservation');

    Route::get('/show_vehicle/{id}', function() {
        return redirect('/vehicles');
    })->middleware('showFromDB:vehicle');

    Route::get('/show_stop/{id}', function($id){
        return redirect('/stops');
    })->middleware('showFromDB:stop');

    Route::get('/info_stop/{id}', function($id){
        return redirect('/stops');
    })->middleware(['showFromDB:stop', 'stopInfo']);

    Route::get('/end_stop/{id}', function(){
        return redirect('/stops');
    })->middleware(['showFromDB:stop','updateDB:stop']);
});

Route::group(['middleware' => 'sessionCheck:operator'], function() {

    Route::get('/add_parking', function() {
        return view('dodaj_parking');
    });

    Route::get('/edit_parking/{id}', function() {
        return view('edytuj_parking');
    })->middleware('showFromDB:parking');

    Route::get('/edit_inspector/{id}', function() {
        return view('user/edytowanie_kontrolera');
    })->middleware('showFromDB:inspector');

    Route::get('/add_inspector', function() {
        return view('user/dodanie_kontrolera');
    })->middleware('getFromDB:operator_id');

    Route::post('/add_inspector', function() {
        return redirect('/inspectors');
    })->middleware('addToDB:code');

    Route::get('/parkings', function() {
        return view('parkingi');
    })->middleware('getFromDB:parkings');

    Route::get('/inspectors', function() {
        return view('kontrolerzy');
    })->middleware('getFromDB:inspectors');

    Route::group(['middleware' => 'addToDB:parking'], function() {
        Route::post('/add_parking', function() {
            return redirect('/parkings');
        });
    });

    Route::get('/show_inspector/{id}', function() {
        return back();
    })->middleware('showFromDB:inspector');
    
    Route::post('/update_inspector', function() {
        return redirect('/inspectors');
    })->middleware('updateDB:inspector');

    Route::get('/delete_inspector/{id}', function() {
        return redirect('/inspectors');
    })->middleware('deleteFromDB:inspector');

    Route::post('/update_parking', function() {
        return redirect('/parkings');
    })->middleware('updateDB:parking');

    Route::get('/delete_parking/{id}', function() {
        return redirect('/parkings');
    })->middleware('deleteFromDB:parking');
});

Route::group(['middleware' => 'sessionCheck:inspector'], function() {

    Route::get('/verify', function() {
        return view('weryfikator');
    });

    Route::post('/verify', function() {
        return view('weryfikator');
    })->middleware('getFromDB:verify');
});