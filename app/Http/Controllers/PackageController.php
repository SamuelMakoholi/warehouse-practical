<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Pallet;
use App\Models\QualityMark;
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
        $packages = Package::with(['pallet', 'qualityMark'])->latest()->get();
        return view('packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pallets = Pallet::all();
        $quality_marks = QualityMark::all();
        return view('packages.create', compact('pallets', 'quality_marks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'serial_number' => 'required|string|max:255|unique:packages,serial_number',
            'type' => 'required|in:loose,carton',
            'mass' => 'required|numeric|min:0',
            'barcode' => 'required|string|max:255',
            'pallet_id' => 'nullable|exists:pallets,id',
            'quality_mark_id' => 'required|exists:quality_marks,id',
        ]);

        $package = new Package();
        $package->serial_number = $request->serial_number;
        $package->type = $request->type;
        $package->mass = $request->mass;
        $package->barcode = $request->barcode;
        $package->pallet_id = $request->pallet_id;
        $package->quality_mark_id = $request->quality_mark_id;
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
            'serial_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('packages')->ignore($package->id),
            ],
            'type' => 'required|in:loose,carton',
            'mass' => 'required|numeric|min:0',
            'barcode' => 'required|string|max:255',
            'pallet_id' => 'nullable|exists:pallets,id',
            'quality_mark_id' => 'required|exists:quality_marks,id',
        ]);

        $package->serial_number = $request->serial_number;
        $package->type = $request->type;
        $package->mass = $request->mass;
        $package->barcode = $request->barcode;
        $package->pallet_id = $request->pallet_id;
        $package->quality_mark_id = $request->quality_mark_id;
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
