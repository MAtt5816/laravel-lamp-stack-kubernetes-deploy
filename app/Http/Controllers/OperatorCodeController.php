<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\OperatorCode;
use Validator;

class OperatorCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $operatorCodes = OperatorCode::all();
        return $operatorCodes;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            ['name' => 'required|string|max:20',
            'surname' => 'required|string|max:25',
            'operator_code' => 'required|string|max:8',
            'operator_id' => 'required|integer'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $operatorCode = new OperatorCode();
        $operatorCode->name = $request->input("name");
        $operatorCode->surname = $request->input("surname");
        $operatorCode->operator_code = $this->randCode();
        $operatorCode->operator_id = $request->input("operator_id");

        $operatorCode->save();
        return $operatorCode;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $operatorCode = OperatorCode::findOrFail($id);
        return $operatorCode;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
            ['name' => 'required|string|max:20',
            'surname' => 'required|string|max:25',
            'operator_code' => 'required|string|max:8',
            'operator_id' => 'required|integer'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        $operatorCode = OperatorCode::findOrFail($id);
        $operatorCode->name = $request->input("name");
        $operatorCode->surname = $request->input("surname");
        $operatorCode->operator_code = $request->input("operator_code");
        $operatorCode->operator_id = $request->input("operator_id");

        $operatorCode->save();
        return $operatorCode;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $operatorCode = OperatorCode::findOrFail($id);
        if($operatorCode->delete()){
            return $operatorCode;
        }
    }

    /**
     * Generate a unique code for the inspector.
     *
     * @return \Illuminate\Support\Str
     */
    public function randCode()
    {
        $arr = $this->index();
        $check = false;
        do
        {
            $str = Str::random(8);
            foreach($arr as $item)
            {
                $json = json_decode($item);
                if($json->{'operator_code'} == $str){
                    $check = true;
                }
                else{
                    $check = false;
                }
            }
        } while($check);

        return $str;
    }
}
