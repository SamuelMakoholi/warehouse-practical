<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use App\Models\Line;
use Illuminate\Http\Request;

class RacksController extends Controller
{
    public function index()
    {
        $racks = Rack::with(['line.warehouse'])
            ->orderBy('serial_number')
            ->paginate(10);

        return view('racks.index', compact('racks'));
    }

    public function create()
    {
        $lines = Line::with('warehouse')->get();
        return view('racks.create', compact('lines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'line_id' => 'required|exists:lines,id',
            'serial_number' => 'required|string|unique:racks,serial_number',
            'capacity' => 'required|numeric|min:0'
        ]);

        Rack::create($validated);

        return redirect()->route('racks.index')
            ->with('success', 'Rack created successfully.');
    }

    public function edit(Rack $rack)
    {
        $lines = Line::with('warehouse')->get();
        return view('racks.edit', compact('rack', 'lines'));
    }

    public function update(Request $request, Rack $rack)
    {
        $validated = $request->validate([
            'line_id' => 'required|exists:lines,id',
            'serial_number' => 'required|string|unique:racks,serial_number,' . $rack->id,
            'capacity' => 'required|numeric|min:0'
        ]);

        $rack->update($validated);

        return redirect()->route('racks.index')
            ->with('success', 'Rack updated successfully.');
    }

    public function destroy(Rack $rack)
    {
        // Check if rack has any pallets
        if ($rack->pallets()->exists()) {
            return redirect()->route('racks.index')
                ->with('error', 'Cannot delete rack that has pallets.');
        }

        $rack->delete();

        return redirect()->route('racks.index')
            ->with('success', 'Rack deleted successfully.');
    }
}
