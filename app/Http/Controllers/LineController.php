<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lines = Line::all();
        return view('lines.index', compact('lines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $racks = Rack::all();
        return view('lines.create', compact('racks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rack_id' => 'required|exists:racks,id',
            'type' => 'required|in:carton,loose,mixed',
            'max_allowed_capacity' => 'required|integer',
        ]);

       $line = new Line();
       $line->name = $request->name;
       $line->rack_id = $request->rack_id;
       $line->type = $request->type;
       $line->max_allowed_capacity = $request->max_allowed_capacity;
       $line->save();

        return redirect()->route('lines.index')
                         ->with('success', 'Line created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Line $line)
    {
        return view('lines.show', compact('line'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Line $line)
    {
        $racks = Rack::all();
        return view('lines.edit', compact('line', 'racks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Line $line): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rack_id' => 'required|exists:racks,id',
            'type' => 'required|in:carton,loose,mixed',
            'max_allowed_capacity' => 'required|integer',
        ]);

     $line = Line::findOrFail($line->id);
     $line->name = $request->name;
     $line->rack_id = $request->rack_id;
     $line->type = $request->type;
     $line->max_allowed_capacity = $request->max_allowed_capacity;
     $line->save();

        return redirect()->route('lines.index')
                         ->with('success', 'Line updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Line $line): RedirectResponse
    {
        $line->delete();

        return redirect()->route('lines.index')
                         ->with('success', 'Line deleted successfully.');
    }
}
