<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\TransactionController;

class payment
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
      Session::reflash();
      $arr = array('amount' => ($request->money * 100), 'externalId' => Str::random(60), 'description' => ('doladowanie '.$request->money.' PLN'), 'buyer' => array('email'=> Session::get('driver')->email));

      $str = json_encode($arr);
              
      $calculatedHash = base64_encode(hash_hmac("sha256", $str, 'c8a063ae-10a0-4154-a306-082bb90e624d', true));


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sandbox.paynow.pl/v1/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$str,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Api-Key: 648b9e41-0567-432c-97c2-440a326a5a49',
          'Signature: '.$calculatedHash,
          'Idempotency-Key: '.Str::random(40)
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      $url = json_decode($response)->redirectUrl;
      $code = json_decode($response)->paymentId;

      $request->merge(['driver_id' => Session::get('driver')->id]);
      $request->merge(['amount' => $request->money]);
      $request->merge(['code' => $code]);
      $request->merge(['status' => 'NEW']);

      $trx = new TransactionController();
      $trx->store($request);

      return redirect()->away($url);
    }
}
