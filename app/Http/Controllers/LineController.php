<?php

namespace App\Http\Controllers;

use App\Line;
use Illuminate\Http\Request;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newLines = [];

        $lines = Line::orderBy('title')->get();

        foreach ($lines as $line)
        {
            $newLine = [
                'id' => $line->id,
                'title' => $line->title,
                'stops' => $line->stops,
                'created_at' => $line->created_at,
                'updated_at' => $line->updated_at
            ];

            array_push($newLines, $newLine);
        }

        return response()->json($newLines);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function show(Line $line)
    {
        return response()->json([
            'id' => $line->id,
            'title' => $line->title,
            'stops' => $line->stops,
            'created_at' => $line->created_at,
            'updated_at' => $line->updated_at
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function edit(Line $line)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Line $line)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function destroy(Line $line)
    {
        //
    }
}
