<?php

namespace App\Http\Controllers;

use App\Models\QualityMark;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class QualityMarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $qualityMarks = QualityMark::all();
        return view('quality_marks.index', compact('qualityMarks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('quality_marks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:quality_marks,name',
            'description' => 'nullable|string|max:500',
        ]);

        $qualityMark = new QualityMark();
        $qualityMark->name = $request->name;
        $qualityMark->description = $request->description;
        $qualityMark->save();

        return redirect()->route('quality-marks.index')
                         ->with('success', 'Quality Mark created successfully.');
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
    public function update(Request $request, QualityMark $qualityMark): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('quality_marks')->ignore($qualityMark->id),
            ],
            'description' => 'nullable|string|max:500',
        ]);

        $qualityMark->name = $request->name;
        $qualityMark->description = $request->description;
        $qualityMark->save();

        return redirect()->route('quality-marks.index')
                         ->with('success', 'Quality Mark updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityMark $qualityMark): RedirectResponse
    {
        $qualityMark->delete();

        return redirect()->route('quality-marks.index')
                         ->with('success', 'Quality Mark deleted successfully.');
    }
}
