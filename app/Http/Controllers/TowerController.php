<?php

namespace App\Http\Controllers;

use App\Tower;
use Illuminate\Http\Request;

class TowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tower::orderBy('title')->get();
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
        if ($request->isJson())
        {
            foreach ($request->all() as $tower)
            {
                $validator = \Validator::make($tower, [
                    'title' => 'required|string|max:255|min:3',
                    'position_X' => 'required|numeric',
                    'position_Y' => 'required|numeric',
                    'description' => 'required|string|min:3'
                ]);

                if ($validator->fails())
                {
                    return response()->json($validator->errors());
                }

                Tower::create($tower);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tower  $tower
     * @return \Illuminate\Http\Response
     */
    public function show(Tower $tower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tower  $tower
     * @return \Illuminate\Http\Response
     */
    public function edit(Tower $tower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tower  $tower
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tower $tower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tower  $tower
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tower $tower)
    {
        //
    }
}
