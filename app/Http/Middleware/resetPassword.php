<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;
use App\Models\User;    
use App\Http\Controllers\AuthController;

class resetPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_name = Session::get('user')->login;

        $request->validate([
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $request->request->add(['login' => $user_name]);

        $auth = new AuthController();
        $user = $auth->resetPassword($request);

        if(! is_null($user)){
            return redirect()->back()->withSuccess(['Hasło zostało zmienione']); 
        }
        else{
            return back()->withErrors(['email' => [__($status)]]);
        }
    }
}
