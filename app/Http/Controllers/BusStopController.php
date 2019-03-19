<?php

namespace App\Http\Controllers;

use App\BusStop;
use Illuminate\Http\Request;
use App\RoadSide;
use App\Region;
use App\Village;
use App\Parish;

class BusStopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BusStop::all();
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
            foreach ($request->all() as $busStop)
            {
                $validator = \Validator::make($busStop, [
                    'title' => 'required|string|max:255|min:2',
                    'stop_code' => 'required|numeric',
                    'common_code' => 'nullable|numeric',
                    'position_X' => 'required|numeric',
                    'position_Y' => 'required|numeric',
                    'road_side' => 'required|string|min:3|max:255',
                    'road' => 'nullable|string',
                    'street' => 'nullable|string',
                    'region' => 'nullable|string',
                    'parish' => 'nullable|string',
                    'village' => 'nullable|string'
                ]);

                if ($validator->fails())
                {
                    return response()->json($validator->errors());
                }
            }

            foreach ($request->all() as $busStop)
            {
                $roadSide = $busStop['road_side'];
                $region = $busStop['region'];
                $parish = $busStop['parish'];
                $village = $busStop['village'];

                $roadSideModel = null;
                $regionModel = null;
                $parishModel = null;
                $villageModel = null;

                if ($roadSide != null)
                {
                    $roadSideModel = RoadSide::firstOrCreate(['title' => $busStop['road_side']]);
                }

                if ($region != null)
                {
                    $regionModel = Region::firstOrCreate(['title' => $busStop['region']]);
                }

                if ($parish != null)
                {
                    $parishModel = Parish::firstOrCreate(['title' => $busStop['parish']]);
                }

                if ($village != null)
                {
                    $villageModel = Village::firstOrCreate(['title' => $busStop['village']]);
                }

                $newBusStop = [
                    'title' => $busStop['title'],
                    'stop_code' => $busStop['stop_code'],
                    'common_code' => $busStop['common_code'],
                    'position_X' => $busStop['position_X'],
                    'position_Y' => $busStop['position_Y'],
                    'road_side_id' => optional($roadSideModel)->id,
                    'road' => $busStop['road'],
                    'street' => $busStop['street'],
                    'region_id' => optional($regionModel)->id,
                    'parish_id' => optional($parishModel)->id,
                    'village_id' => optional($villageModel)->id
                ];

                BusStop::create($newBusStop);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BusStop  $busStop
     * @return \Illuminate\Http\Response
     */
    public function show(BusStop $busStop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BusStop  $busStop
     * @return \Illuminate\Http\Response
     */
    public function edit(BusStop $busStop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BusStop  $busStop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BusStop $busStop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BusStop  $busStop
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusStop $busStop)
    {
        //
    }
}
