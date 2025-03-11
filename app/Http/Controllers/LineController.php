<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lines = Line::with('warehouse')->orderBy('name')->paginate(10);
        return view('lines.index', compact('lines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::orderBy('name')->get();
        return view('lines.create', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:carton,loose,mixed',
            'max_allowed_capacity' => 'required|numeric|min:0',
        ]);

        Line::create($validated);

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
        $warehouses = Warehouse::orderBy('name')->get();
        return view('lines.edit', compact('line', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Line $line): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:carton,loose,mixed',
            'max_allowed_capacity' => 'required|numeric|min:0',
        ]);

        $line->update($validated);

        return redirect()->route('lines.index')
            ->with('success', 'Line updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Line $line): RedirectResponse
    {
        // Check if line has any racks
        if ($line->racks()->exists()) {
            return redirect()->route('lines.index')
                ->with('error', 'Cannot delete line that has racks.');
        }

        $line->delete();

        return redirect()->route('lines.index')
            ->with('success', 'Line deleted successfully.');
    }
}
