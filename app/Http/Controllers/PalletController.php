<?php

namespace App\Http\Controllers;

use App\Models\Pallet;
use App\Models\Rack;
use App\Models\Line;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class PalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pallets = Pallet::with(['rack.line.warehouse'])->get();
        return view('pallets.index', compact('pallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $racks = Rack::with('line.warehouse')->get();
        $lines = Line::all();
        return view('pallets.create', compact('racks', 'lines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());    
        $request->validate([
            'serial_number' => 'required|string|max:255|unique:pallets,serial_number',
            'rack_id' => 'required|exists:racks,id',
            'max_weight' => 'required|numeric|min:0',
        ]);

        Pallet::create($request->all());

        return redirect()->route('pallets.index')
                         ->with('success', 'Pallet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pallet $pallet)
    {
        $pallet->load(['rack.line.warehouse', 'packages']);
        return view('pallets.show', compact('pallet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pallet $pallet)
    {
        $racks = Rack::with('line.warehouse')->get();
        $lines = Line::all();
        return view('pallets.edit', compact('pallet', 'racks', 'lines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pallet $pallet): RedirectResponse
    {
        $request->validate([
            'serial_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pallets')->ignore($pallet->id),
            ],
            'rack_id' => 'required|exists:racks,id',
            'capacity' => 'required|numeric|min:0',
            'quality_mark' => 'nullable|string|max:255',
        ]);

        $pallet->update($request->all());

        return redirect()->route('pallets.index')
                         ->with('success', 'Pallet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pallet $pallet): RedirectResponse
    {
        if ($pallet->packages()->exists()) {
            return redirect()->route('pallets.index')
                           ->with('error', 'Cannot delete pallet that has packages.');
        }

        $pallet->delete();

        return redirect()->route('pallets.index')
                         ->with('success', 'Pallet deleted successfully.');
    }
}
