<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class sessionCheck
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
            if($role == 'all'){
                return $next($request);
            }
            else{
                $type = Session::get('user')->user_type;
                if($type == $role){
                    return $next($request);
                }
                else{
                    return redirect('/');  
                }
            }
        }
        //abort(403);
        return redirect()->route('loginForm');
    }
}
