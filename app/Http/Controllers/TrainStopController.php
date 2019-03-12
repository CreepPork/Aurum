<?php

namespace App\Http\Controllers;

use App\TrainStop;
use Illuminate\Http\Request;
use App\Line;

class TrainStopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TrainStop::all();
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

            foreach ($request->all() as $trainStop)
            {
                $validator = \Validator::make($trainStop, [
                    'title' => 'required|string|max:255|min:3',
                    'position_X' => 'required|numeric',
                    'position_Y' => 'required|numeric',
                    'line' => 'required|string|min:3|max:255'
                ]);

                if ($validator->fails())
                {
                    return response()->json($validator->errors());
                }

                $line = Line::firstOrCreate(['title' => $trainStop['line']]);

                $newTrainStop = [
                    'title' => $trainStop['title'],
                    'position_X' => $trainStop['position_X'],
                    'position_Y' => $trainStop['position_Y'],
                    'line_id' => $line->id
                ];

                TrainStop::create($newTrainStop);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TrainStop  $trainStop
     * @return \Illuminate\Http\Response
     */
    public function show(TrainStop $trainStop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TrainStop  $trainStop
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainStop $trainStop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TrainStop  $trainStop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainStop $trainStop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TrainStop  $trainStop
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainStop $trainStop)
    {
        //
    }
}
