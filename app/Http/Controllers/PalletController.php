<?php

namespace App\Http\Controllers;

use App\Models\Pallet;
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
        $pallets = Pallet::all();
        return view('pallets.index', compact('pallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lines = Line::all();
        return view('pallets.create', compact('lines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'serial_number' => 'required|string|max:255|unique:pallets,serial_number',
            'line_id' => 'required|exists:lines,id',
            'capacity' => 'required|integer',
            'quality_mark' => 'nullable|string|max:255',
        ]);

        $pallet = new Pallet();
        $pallet->serial_number = $request->serial_number;
        $pallet->line_id = $request->line_id;
        $pallet->capacity = $request->capacity;
        $pallet->quality_mark = $request->quality_mark;
        $pallet->save();

        return redirect()->route('pallets.index')
                         ->with('success', 'Pallet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pallet $pallet)
    {
        return view('pallets.show', compact('pallet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pallet $pallet)
    {
        $lines = Line::all();
        return view('pallets.edit', compact('pallet', 'lines'));
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
            'line_id' => 'required|exists:lines,id',
            'capacity' => 'required|integer',
            'quality_mark' => 'nullable|string|max:255',
        ]);

        $pallet->serial_number = $request->serial_number;
        $pallet->line_id = $request->line_id;
        $pallet->capacity = $request->capacity;
        $pallet->quality_mark = $request->quality_mark;
        $pallet->save();

        return redirect()->route('pallets.index')
                         ->with('success', 'Pallet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pallet $pallet): RedirectResponse
    {
        $pallet->delete();

        return redirect()->route('pallets.index')
                         ->with('success', 'Pallet deleted successfully.');
    }
}
