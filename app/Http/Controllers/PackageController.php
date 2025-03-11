<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $packages = Package::all();
        return view('packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255|unique:packages,tracking_number',
            'weight' => 'required|numeric',
            'destination' => 'required|string|max:255',
            'pallet_id' => 'required|exists:pallets,id',
        ]);

        $package = new Package();
        $package->tracking_number = $request->tracking_number;
        $package->weight = $request->weight;
        $package->destination = $request->destination;
        $package->pallet_id = $request->pallet_id;
        $package->save();

        return redirect()->route('packages.index')
                         ->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package): RedirectResponse
    {
        $request->validate([
            'tracking_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('packages')->ignore($package->id),
            ],
            'weight' => 'required|numeric',
            'destination' => 'required|string|max:255',
            'pallet_id' => 'required|exists:pallets,id',
        ]);

        $package->tracking_number = $request->tracking_number;
        $package->weight = $request->weight;
        $package->destination = $request->destination;
        $package->pallet_id = $request->pallet_id;
        $package->save();

        return redirect()->route('packages.index')
                         ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return redirect()->route('packages.index')
                         ->with('success', 'Package deleted successfully.');
    }
}
