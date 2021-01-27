<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use Auth;


class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return UserAddress::where('user_id',Auth::id())->get();
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


        $validatedData = $request->validate([
            'name' => 'required',
            'districts_id' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);


        $validatedData['user_id'] = Auth::id();

        $data = UserAddress::create($validatedData);

        return $data;
        
        //
    }

  
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //


        $validatedData = $request->validate([
            'name' => 'required',
            'districts_id' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);


        
            $update = UserAddress::find($id);

            if($update->user_id == Auth::id()){
                $update->name = $request->name;
                $update->districts_id = $request->districts_id;
                $update->address = $request->address;
                $update->phone = $request->phone;
                $update->save();
                return $update;
            }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
