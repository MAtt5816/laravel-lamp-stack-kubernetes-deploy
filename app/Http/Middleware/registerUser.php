<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Session;

class registerUser
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
        if(! Session::has('token')){
            $user = new AuthController();
            $user = $user->register($request);

            $json = json_decode($user->content());
            if($user->status() >= 400){
                return back()->withErrors($json);
            }
            session(['user' => $json->data]);
            session(['token' => $json->access_token]);
        }
        return redirect('/');
    }

}
