<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Rack;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $racks = Rack::all();
        return view('racks.index', compact('racks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        $lines = Line::all();
        return view('racks.create', compact('warehouses', 'lines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
       // dd($request->all());
        $request->validate([
            'serial_number' => 'required|string|max:255',
            'capacity' => 'required',
            'line_id' => 'required|exists:lines,id',
        ]);

        $rack = new Rack();
        $rack->serial_number = $request->serial_number;
        $rack->line_id = $request->line_id;
        $rack->capacity = $request->capacity;
        $rack->save();

        return redirect()->route('racks.index')
                         ->with('success', 'Rack created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rack $rack)
    {
        return view('racks.show', compact('rack'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rack $rack)
    {
        $warehouses = Warehouse::all();
        return view('racks.edit', compact('rack', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rack $rack): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $rack = Rack::findOrFail($rack->id);
        $rack->warehouse_id = $request->warehouse_id;
        $rack->name = $request->name;
        $rack->save();
        return redirect()->route('racks.index')
                         ->with('success', 'Rack updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rack $rack): RedirectResponse
    {
        $rack->delete();

        return redirect()->route('racks.index')
                         ->with('success', 'Rack deleted successfully.');
    }
}
