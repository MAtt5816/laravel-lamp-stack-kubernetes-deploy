<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Driver;
use App\Models\Operator;
use App\Models\Inspector;
use App\Models\Balance;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OperatorCodeController;
use Validator;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),
            ['login' => 'required|string|max:15|unique:users|regex:/^[A-Za-z0-9._%+-]{1,15}$/',
            'password' => 'required|string|min:8|max:50|confirmed',
            'user_type' => 'in:driver,operator,inspector'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $user = User::create(['login' => $request->login, 'password' => Hash::make($request->password),
            'user_type' => $request->user_type]);
        $token = $user->createToken('auth_token')->plainTextToken;

        $obj = null;
        $check = false;
        switch ($request->user_type) {
            case 'driver': {
                [$check, $obj] = $this->registerDriver($request, $user->id);
                break;
            }
            case 'operator': {
                [$check, $obj] = $this->registerOperator($request, $user->id);
                break;
            }
            case 'inspector': {
                [$check, $obj] = $this->registerInspector($request, $user->id);
                break;
            }
        }

        if($check){
            if($request->user_type == 'driver') $balance = Balance::create(['balance' => 0, 'driver_id' => $obj->id]);
            return response()->json(['data' => $user, 'details' => $obj, 'access_token' => $token, 'token_type' => 'Bearer', ]);
        }
        else{
            $tmp = new UserController();
            $tmp->destroy($user->id);
            return response()->json($obj, 400);
        }
    }

    public function logout(Request $request){
        if ($request->user()) { 
            auth()->user()->tokens()->delete();
            return [ 
                'message' => 'Logged out'
            ];
        }
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'login' => 'required|string|max:15',
            'password' => 'required|string|max:50'
        ]);
        $user = User::where('login', $fields['login'])->first(); // sprawdzenie loginu
        if(!$user || !Hash::check($fields['password'], $user->password)){ // sprawdzenie hasła
            return response([
                'message' => 'Bad creds'
            ], 401);
        }
        $token = $user->createToken('myapptoketn')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function resetPassword(Request $request){
        $fields = $request->validate([
            'login' => 'required|string|max:15',
            'old_password' => 'required|string|max:50',
            'new_password' => 'required|string|max:50',
        ]);
        $user = User::where('login', $fields['login'])->first(); // sprawdzenie loginu
        if(!$user || !Hash::check($fields['old_password'], $user->password)){ // sprawdzenie hasła
            return response([
                'message' => 'Bad creds'
            ], 401);
        }
        else{
            $user = User::where('login', $fields['login'])->update(['password' => Hash::make($fields['new_password'])]);
            $response = [
                'user' => $user
            ];
            return response($response, 201);
        }
    }

    private function registerDriver(Request $request, int $user_id) {
        $validator = Validator::make($request->all(), 
            ['name' => 'required|string|max:20', 
                'email' => 'required|string|email|max:30|unique:drivers', 
                'surname' => 'required|string|max:25',
                'city' => 'required|string|max:30',
                'street' => 'required|string|max:25',
                'house_number' => 'required|string|max:7',
                'postal_code' => 'required|string|max:6',
                'phone' => 'required|string|max:11',
            ]);
        if ($validator->fails())
        {
            return [false, response()->json($validator->errors())];
        }
        $driver = Driver::create(['name' => $request->name, 'email' => $request->email, 'surname' => $request->surname, 'city' => $request->city,
            'street' => $request->street, 'house_number' => $request->house_number, 'postal_code' => $request->postal_code, 
            'user_id' => $user_id, 'phone' => $request->phone]);
        return [true, $driver];
    }

    private function registerInspector(Request $request, int $user_id) {
        $validator = Validator::make($request->all(), 
            ['operator_code' => 'required|string|max:8|unique:inspectors']);
        if ($validator->fails())
        {
            return [false, response()->json($validator->errors())];
        }
        $codes = new OperatorCodeController();
        $codes = $codes->index();
        $operator_id = null;
        foreach($codes as $code){
            if($code->operator_code == $request->operator_code){
                $operator_id = $code->operator_id;
                break;
            }
        }
        if($operator_id === null){
            return [false, response()->json(['err', 'Niepoprawny kod operatora'])]; // bad operator code
        }
        $inspector = Inspector::create(['operator_code' => $request->operator_code, 'operator_id' => $operator_id, 'user_id' => $user_id]);
        return [true, $inspector];
    }
    
    private function registerOperator(Request $request, int $user_id) {
        $validator = Validator::make($request->all(), 
        ['email' => 'required|string|email|max:25|unique:operators', 
            'tin' => 'required|string|max:12',
            'phone' => 'required|string|max:11',
        ]);
        if ($validator->fails())
        {
            return [false, response()->json($validator->errors())];
        }
        $operator = Operator::create(['email' => $request->email, 'tin' => $request->tin, 'phone' => $request->phone,
            'user_id' => $user_id]);
        return [true, $operator];
    }
}
