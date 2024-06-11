<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BalanceController;

class increaseBalance
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
        if($request->paymentId !== null){
            $trx = new TransactionController();
            $trxs = $trx->index();
            foreach($trxs as $t){
                if($t->code == $request->paymentId && $request->paymentStatus == 'CONFIRMED' && $t->status == 'NEW'){
                    $did = $t->driver_id;
                    $balance = new BalanceController();
                    $balances = $balance->index();
                    foreach($balances as $ba){
                        if($ba->driver_id == $did){
                            $request->merge(['balance' => ($ba->balance + $t->amount)]);
                            $request->merge(['driver_id' => ($ba->driver_id)]);
                            $request->merge(['amount' => $t->amount]);
                            $request->merge(['code' => $t->code]);
                            $request->merge(['status' => 'CONFIRMED']);
                            $trx->update($request, $t->id);
                            $balance->update($request, $ba->id);
                        }
                    }
                }
            }
        }
    
        return $next($request);
    }
}
